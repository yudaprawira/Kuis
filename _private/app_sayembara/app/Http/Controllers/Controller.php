<?php

namespace App\Http\Controllers;

use Session, Image,
    
    Modules\Pesanan\Models\Pesanan, Mail,
    Modules\Pesanan\Models\PesananKonfirmasi,

    Illuminate\Foundation\Bus\DispatchesJobs,
    Illuminate\Routing\Controller as BaseController,
    Illuminate\Foundation\Validation\ValidatesRequests,
    Illuminate\Foundation\Auth\Access\AuthorizesRequests,
    Illuminate\Foundation\Auth\Access\AuthorizesResources;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Global varible to build Main Page 
    **/
    public $dataView;
    
    /**
     * Variable template 
    **/
    public $tmpl;
    
    /**
     * Variable Current path 
    **/
    public $currentPath;

    /**
     * Variable Breadcrumbs
    **/
    public $rowBreadcrumbs;

    function __construct() 
    {

    }

    /*
    |--------------------------------------------------------------------------
    | Notif
    |--------------------------------------------------------------------------
    | @param $text String, berisi teks yang akan ditampilkan sebagai notif
    | @param $type String, [success|warning|danger|info]
    | @param $align String, [left|center|right]
    | @param $width String, [auto|150px]
    | @param $close boolean, [true|false]
    | @param $name String, nama elemen dari tag HTML
    */
    public function setNotif($text, $type='warning', $align='center', $width='auto', $close='false', $name='')
    {
        Session::push('ses_notif', [
            'text' => $text,
            'type' => $type,
            'align'=> $align,
            'width'=> $width,
            'close'=> $close,
            'name' => $name,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */
    public function _buildNotification($encrypt=false)
    {
        $notif = Session::get('ses_notif');
        
        Session::forget('ses_notif');
        
        $mesage = view('global.notif', ['notif'=>$notif]);
        
        return $encrypt ? base64_encode($mesage) : $mesage;
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD IMAGE
    |--------------------------------------------------------------------------
    |@param : $img Object file
    |@param : $mainDir String name parent directories ex:book
    |@param : $size Array ex:['600x800', '200x300']
    |@param : $urlName String ex:abc 
    |@param : $aspectRatio Boolean ex:true 
    |@return : abc-600x800.jpg 
    */
    public function _uploadImage($img, $mainDir, $size, $urlName='', $aspectRatio=true)
    {
        $ret = array();

        if ( $img && $img!='undefined' && !empty($size) )
        {
            $mainPath = 'media/';

            $path = $mainDir."/".date("Y/m/d/");

            //mkdir
            if ( !file_exists( public_path($mainPath.$path) ) ) mkdir( public_path($mainPath.$path), 0777, true );

            foreach ( $size as $s )
            {
                $arrSize = explode('x', $s);

                if ( isset($arrSize[1]) )
                {
                    $urlName = strtolower($urlName ? $urlName : uniqid());

                    $filename  = $urlName.'-'.$s.'.'.$img->getClientOriginalExtension();

                    $realPath = public_path($mainPath . $path . $filename);

                    if ($r = Image::make($img->getRealPath())->resize($arrSize[0], $arrSize[1], function ($constraint) use($aspectRatio) {
                        if ($aspectRatio) $constraint->aspectRatio();
                    })->interlace(true)->save($realPath))
                    {
                        $ret[$s] = $path.$r->basename;
                    }
                }
            }
        }
        
        return $ret;
    }

    /*
    |--------------------------------------------------------------------------
    | Validate with error
    |--------------------------------------------------------------------------
    */
    public function setValidator($error, $type='warning', $align='right')
    {
        if ( $error )
        {
            foreach( $error->toArray() as $name=>$arrayError )
            {
                foreach($arrayError as $e)
                {
                    $this->setNotif($e, $type, $align, 'auto', false, $name);
                }
            }
        }
    }
    
    

    /*
    |--------------------------------------------------------------------------
    | CLEAR CACHE
    |--------------------------------------------------------------------------
    |@param : $cacheKey String Key of Cache
    */
    public function clearCache($cacheKey)
    {
        //clear cache
        if(\Cache::has($cacheKey)) 
        {
            \Cache::forget($cacheKey); 
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SEND EMAIL
    |--------------------------------------------------------------------------
    |@param : $inv String Number Invoice
    |@param : $sendTo String target send to admin or member
    |@param : $type String type email to send, invoice, konfirmasi, pengiriman
    |@param : $params Array [
              'to_email'=> name@domain.com  
              'to_name' => name  
              'subject' => Email Invoice  
    ]
    */
    public function _sendEmail($inv, $sendTo='admin', $type='invoice', $params=[])
    {
        $template = config('app.template');

        $this->dataView['row'] = Pesanan::join('mod_pesanan_pembeli', 'mod_pesanan.id', '=', 'mod_pesanan_pembeli.pesanan_id')
                                        ->with('detail')
                                        ->where('invoice', $inv)
                                        ->first();  

        if ( !$this->dataView['row'] ) abort(404);

        $this->dataView['rowConfirm'] = PesananKonfirmasi::where('pesanan_id', val($this->dataView['row'], 'id'))->with('bank')->first();                        

        $this->dataView['emailSendTo'] = $sendTo;

        $this->dataView['_detailTrans'] = view($template . 'email/_detail', $this->dataView);

        if ( $type=='invoice' )
            $ret = view($template . 'email/invoice', $this->dataView);
        elseif ( $type=='konfirmasi' )
            $ret = view($template . 'email/konfirmasi', $this->dataView);
        elseif ( $type=='dikirim' )
            $ret = view($template . 'email/dikirim', $this->dataView);
        
        if ( $params )
        {
            $this->dataView['emailContent'] = $ret;

            Mail::send($template . 'email/_main', $this->dataView, function ($m) use($params){
                $m->from(cfg('email'), cfg('title'))
                  ->to($params['to_email'], $params['to_email'])
                  ->subject($params['subject'])
                  ->bcc('yuda.prawira4@gmail.com');;
            });
        }

        return $ret;
    }

}
