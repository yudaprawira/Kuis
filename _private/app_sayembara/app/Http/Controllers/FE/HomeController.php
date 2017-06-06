<?php
namespace App\Http\Controllers\FE;

use App\Http\Requests, DB,
    Modules\Soal\Models\Soal,
    Modules\Kategori\Models\Kategori,
    Modules\Soal\Models\Hasil;

use Lang, Route, Session, Request, Cookie, Redirect, Hash, Input;

class HomeController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Parent Construct
    |-------------------------------------------------------------------------
    */
    function __construct() 
    {
        parent::__construct();
    }
    
    function logout()
    {
        session::flush();

        return redirect(url('login'));
    }
    
    function ping()
    {
        if ( session::has('ses_fe_start') )
        {
            $_start = session::get('ses_fe_start');
            $_end = time();
            $_second = abs($_end - $_start);
            $_spent = gmdate("H:i:s", $_second);
            
            return Response()->json([ 
                'time_server' => dateSQL(), 
                'time_spent'=> $_spent
            ]);
        }
    }
    
    function index() 
    {
        if ( !session::has('ses_feuserid') )
        {
            return redirect(url('login'));
        }
        
        return view($this->tmpl . 'index', $this->dataView);
    }

    function start() 
    {
        if ( !session::has('ses_feuserid') )
        {
            return redirect(url('login'));
        }
        if ( $row = Hasil::where('member_id', session::get('ses_feuserid'))->orderBy('tanggal', 'DESC')->first() )
        {
            if ( abs(time()-strtotime(val($row, 'tanggal')))<=86400 )
            {
                $valid = strtotime(val($row, 'tanggal'))+86400;

                return Redirect(url('?msg='.base64_encode(json_encode([
                    'title' => 'The exam can be retried on <br/>'.date("d M Y H:i:s", $valid),
                    'html' => true,
                    'type' => 'info'
                ]))));exit;
            }
        }
        if ( !session::has('ses_fe_start') )
        {
            session::put('ses_fe_start', time());
        }

        $limit = 10;
        $categories = Kategori::where('status', '1')->with('rel_soal')->get();

        $this->dataView['rows'] = array();

        if ( !empty($categories) )
        {
            foreach( $categories as $cVal )
            {
                $soal = [];
                if ( !empty($cVal) )
                {
                    foreach( $cVal->rel_soal as $sV )
                    {
                        $soal[$sV->id] = $sV->toArray();
                    }
                    $soal = array_slice($soal, 0, $limit);
                }
                $this->dataView['rows'] = array_merge($this->dataView['rows'], $soal);
            }

            shuffle($this->dataView['rows']);
        }

        return view($this->tmpl . 'question', $this->dataView);
    }

    function saveAnswer()
    {
        if ( !session::has('ses_feuserid') )
        {
            return redirect(url('login'));
        }

        $input  = Input::except('_token');
        $answers = val($input, 'answers');
        $questions = json_decode(val($input, '_questions'), true);

        if ( !empty($questions) )
        {
            $rows = getRowArray(Soal::whereIn('id', $questions)->with('rel_kategori')->get(), 'id', '*');

            $result = [];
            foreach( $rows as $soalID=>$soalVal )
            {
                $soalPilihan = json_decode(val($soalVal, 'pilihan'), true);
                
                //correct
                if ( val($soalPilihan, 'jawaban') == val($answers, $soalID)  )
                {
                    //correct by categori
                    if ( isset($result['category'][val($soalVal, 'rel_kategori.kategori')]['correct']) )
                        $result['category'][val($soalVal, 'rel_kategori.kategori')]['correct']+= 1;
                    else
                        $result['category'][val($soalVal, 'rel_kategori.kategori')]['correct'] = 1;
                    
                    //correct global
                    if ( isset($result['all']['correct']) )
                        $result['all']['correct']+= 1;
                    else
                        $result['all']['correct'] = 1;                    
                }

                //total by category
                if ( isset($result['category'][val($soalVal, 'rel_kategori.kategori')]['total']) )
                    $result['category'][val($soalVal, 'rel_kategori.kategori')]['total']+= 1;
                else
                    $result['category'][val($soalVal, 'rel_kategori.kategori')]['total'] = 1;

                //total global
                if ( isset($result['all']['total']) )
                    $result['all']['total']+= 1;
                else
                    $result['all']['total'] = 1;

                //detail
                $result['detail'][$soalID] = [
                    'question' => val($soalVal, 'soal'),
                    'answer' => val($soalPilihan, 'soal.'.val($answers, $soalID)),
                    'status'=> val($soalPilihan, 'jawaban') == val($answers, $soalID) ? true : false
                ];
            }
            
            //percentage
            $result['all']['percentage'] = val($result, 'all.correct') ? ($result['all']['correct'] / $result['all']['total'] * 100) : 0;
            foreach( $result['category'] as $cID=>$cVal )
            {
                $result['category'][$cID]['percentage'] = val($result, 'category.'.$cID.'.correct') ? ($result['category'][$cID]['correct'] / $result['category'][$cID]['total'] * 100) : 0;
            }

            //timing
            $result['timing']['start'] = session::get('ses_fe_start');
            $result['timing']['end'] = time();
            $result['timing']['time']['second'] = abs($result['timing']['end'] - $result['timing']['start']);
            $result['timing']['time']['string'] = gmdate("H:i:s", $result['timing']['time']['second']);

            //simpan hasil
            $hasilID = Hasil::insertGetId([
                'member_id' => session::get('ses_feuserid'),
                'nilai' => $result['all']['percentage'],
                'tanggal' => date("Y-m-d H:i:s", $result['timing']['start']),
                'durasi' => $result['timing']['time']['second'],
                'hasil' => json_encode($result),
                'created_by' => session::get('ses_feuserid'),
                'created_at' => dateSQL(),
            ]);
            
            session::forget('ses_fe_start');

            return Redirect(url('hasil/'.$hasilID));
        }

        return Redirect(url());
    }

    function hasil($id=null)
    {
        if ( !session::has('ses_feuserid') )
        {
            return redirect(url('login'));
        }

        if ( $id )
        {
            if ( $row = Hasil::where('id', $id)->where('member_id', session::get('ses_feuserid'))->first() )
            {
                $this->dataView['row'] = $row;

                return view($this->tmpl . 'hasil_detail', $this->dataView);
            }
            else abort(404);
        }
        else
        {
            if ( $rows = Hasil::where('member_id', session::get('ses_feuserid'))->orderBy('tanggal', 'DESC')->get() )
            {
                $this->dataView['rows'] = $rows;

                return view($this->tmpl . 'hasil', $this->dataView);
            }
            else abort(404);
        }
    }

    function ranking()
    {
        $prf = DB::getTablePrefix();
        $this->dataView['rows'] = Hasil::where('status', '1')
                                       ->where('nilai', function($q) use($prf) {
                                            $q->from('mod_hasil as a')
                                              ->where('status', '1')  
                                              ->select(DB::raw('MAX(nilai) as nilai_max'))
                                              ->whereRaw($prf.'mod_hasil.member_id='.$prf.'a.member_id');
                                       })
                                       ->where('durasi', function($q) use($prf) {
                                            $q->from('mod_hasil as b')
                                              ->where('status', '1')  
                                              ->select(DB::raw('MIN(durasi) as durasi_min'))
                                              ->whereRaw($prf.'mod_hasil.member_id='.$prf.'b.member_id AND '.$prf.'mod_hasil.nilai='.$prf.'b.nilai');
                                       })
                                       ->groupBy('member_id')
                                       ->orderBy('nilai', 'DESC')
                                       ->orderBy('durasi')
                                       ->orderBy('tanggal')
                                       ->with('member')->get();
                                              
        return view($this->tmpl . 'ranking', $this->dataView);
    }

}