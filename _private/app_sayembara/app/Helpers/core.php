<?php

/*
|--------------------------------------------------------------------------
| BACKEND URL
|--------------------------------------------------------------------------
*/
function BeUrl($path='')
{
    return config('app.be_url').($path ? ( $path=='/' ? '' : '/'.$path ) : '');
}

/*
|--------------------------------------------------------------------------
| FRONTEND URL
|--------------------------------------------------------------------------
*/
function FeUrl($path='')
{
    if ( $path=="current" )
    {
        $temp_ = str_replace('#!', '', config('app.fe_url'));

        return str_replace($temp_, config('app.fe_url'), Request::url());
    }
    else
    {
        return config('app.fe_url').($path ? ( $path=='/' ? '' : '/'.$path ) : '');
    }
}

/*
|--------------------------------------------------------------------------
| Image URL
|--------------------------------------------------------------------------
*/
function imgUrl($path, $size='')
{

    if ( $size )
    {
        $arrPath = explode('/', $path);
        $arrPath_= explode('-', end($arrPath));
        $oriSize = explode('.', end($arrPath_))[0];
        $path    = str_replace($oriSize, $size, $path);
    }

    return url('media/'.$path);
}



/*
|--------------------------------------------------------------------------
| DEBUGING
|--------------------------------------------------------------------------
*/
function echoPre($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/*
|--------------------------------------------------------------------------
| CONVERT Minute -> Hour
|--------------------------------------------------------------------------
*/
function convertToHoursMins($time, $returnOnlyHours=false) 
{   
    if ( $time<=0 ) return "-";
    $hours = floor($time / 3600);
    $minutes = floor(($time / 60) % 60);
    $seconds = $time % 60;
    $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
    return $returnOnlyHours ? $hours : "<span class='".($minutes?'time-val':'')."'>$hours:$minutes</span>";//:$seconds
}
function getHoursDate($start, $end)
{
    return floor(((strtotime($end)-strtotime($start))/3600)*2)/2;
}

/*
|--------------------------------------------------------------------------
| PACKER JS
|--------------------------------------------------------------------------
*/
function packerJs($js)
{
    include(__DIR__.'/Packer.php');
    $packer = new JavaScriptPacker($js, 'Numeric', true, false);
   	echo $packer->pack();
}

/*
|--------------------------------------------------------------------------
| DATE SQL
|--------------------------------------------------------------------------
| to format date before save to SQL
*/
function dateSQL($date=null)
{
    if (!$date) return date("Y-m-d H:i:s");
    
    return date("Y-m-d H:i:s", strtotime($date));
}

/*
|--------------------------------------------------------------------------
| CREATE LINK
|--------------------------------------------------------------------------
*/
function createLink($url, $title, $target='_blank', $class='')
{
    return '<a href="'.$url.'" target="'.$target.'" class="'.$class.'">'.$title.'</a>';
}
/*
|--------------------------------------------------------------------------
| CREATE IMAGE
|--------------------------------------------------------------------------
*/
function createImage($url, $size='')
{
    $size = $size ? explode('x', $size) : null;

    return '<img src="'.url($url).'" '.(isset($size[0]) ? 'width='.$size[0] : '').' '.(isset($size[1]) ? 'height='.$size[1] : '').'/>';
}
/*
|--------------------------------------------------------------------------
| CREATE OPTION
|--------------------------------------------------------------------------
*/
function createOption($array, $selected='')
{
    $opt = '';
    if ( !empty($array) )
    {
        foreach( $array as $id=>$name )
        {
            $opt.='<option value="'.$id.'" '.($selected==$id ? 'selected=1' : '').'>'.$name.'</option>';
        }
    }
    return $opt;
}



/*
|--------------------------------------------------------------------------
| SET LOG
|--------------------------------------------------------------------------
| Here is where you can register all of the routes for an application.
*/
function setLog($activity, $link='', $userID='')
{
    App\Models\System\Log::insert([
        'activity' => trim(str_replace('| '.config('app.title'), '', $activity)),
        'link' => $link,
        'method' => \Request::isMethod('get') ? 'GET' : 'POST',
        'created_at' => date("Y-m-d H:i:s"),
        'created_by' => $userID ? $userID : (\Session::has('ses_userid') ? \Session::get('ses_userid') : ''),
    ]);
}

function formatDate($fullDate=null, $format=1)
{
    if ( !$fullDate ) $fullDate = date("Y-m-d");    
    
    $date = intval(date("d", strtotime($fullDate)));
    $month = intval(date("m", strtotime($fullDate)));
    $year = intval(date("Y", strtotime($fullDate)));
    
    switch( $format )
    {
        case 1 : $ret = $date.' '. trans('global.month_long')[$month-1].' '.$year; break; //d month year (indo) LONG
        case 2 : $ret = date("d M Y"); break; //d month year
        case 3 : $ret = $date.' '. trans('global.month_short')[$month-1].' '.$year; break; //d month year (indo) LONG
        case 4 : $ret = trans('global.month_long')[$month-1].' '.$year; break; //d month year (indo) LONG
        case 5 : $ret = $date.' '. trans('global.month_long')[$month-1].' '.$year .' ' .date("H:i:s", strtotime($fullDate)); break; //d month year (indo) LONG
        default : $ret = $fullDate; break; //Y-m-d
    }
    
    return $ret;
}

function formatNumber($number, $dec=0, $currency=false, $symbol='Rp ', $isPrefix=true)
{
    $number = $number ? $number : 0;
    
    if ( $isPrefix )
        return ($currency ? $symbol : '').number_format($number, $dec, ',', '.');
    else
        return number_format($number, $dec, ',', '.').($currency ? $symbol : '');
}
function stringToNumber($string, $isDec=false)
{
    if ( $isDec )
    {
        $string = str_replace('.', '', $string);
        return str_replace(',', '.', $string);
    }
    else
    {
        return doubleval(str_replace('.', '', $string));
    }
}

function formatTokenInput($array, $id, $name)
{
    $ret = [];

    if ( $row = getRowArray($array, $id, $name) )
    {
        foreach( $row as $rId=>$rName )
        {
            $ret[] = ['id'=>$rId, 'name'=>$rName];
        }
    }

    return json_encode($ret);
}

function toRomawi($angka) 
{
  $desc = [1,4,5,9,10,40,50,90,100,400,500,900,1000];
  $roma = ["I","IV","V","IX","X","XL","L","XC","C","CD","D","CM","M"];
  $hasil='';
  
  for($i=12; $i>=0; $i--) 
  {
     while($angka >= $desc[$i]) 
     {
    	$angka-= $desc[$i];
    	$hasil.= $roma[$i];
     }
  }
			
  return $hasil;
}

//date '2016-01-01'
function getWeekRange($date)
{
    $startMonth = strtotime($date);
    // Get the ISO week number for the 1st day of the requested month
    $startWeek = date('W',$startMonth);
    
    // Get timestamp for the last day of the requested month (using current year)
    $endMonth = strtotime('+1 Month -1 Day',$startMonth);
    // Get the ISO week number for the last day of the requested month
    $endWeek = date('W',$endMonth);
    
    // get a range of weeks from the start week to the end week
    if ($startWeek > $endWeek) {
        // start week for january in previous year
        $weekRange = range(1,$endWeek);
        array_unshift($weekRange,intval($startWeek));
    } else {
        $weekRange = range($startWeek,$endWeek);
    }
    return $weekRange;
} 

function getDay($y, $m, $d)
{
    return new DatePeriod(
        new DateTime("first $d of $y-$m"),
        DateInterval::createFromDateString("next $d"),
        new DateTime("last day of $y-$m")
    );
}

function dateToDay($date)
{
    return isset( trans('global.day_long')[date("D", strtotime($date))] ) ? trans('global.day_long')[date("D", strtotime($date))] : '';
}

function linkable($text, $link='', $target='_self', $class='')
{
    if ( !$link ) return $text;

    return createLink($link, $text, $target, $class);
}

/**
* condition value 
* ex : val($m, 'name')
* ex : val($m, 'info.version') subkey separated by dot
**/
function val($row, $key, $default='')
{
    //sub key
    if ( strpos($key, '.') )
    {
        $arr = explode('.', $key);
        for( $i=0; $i<count($arr)-1;$i++ )
        {
            $row = val($row, $arr[$i], $default);   
        }
        return val($row, end($arr), $default);
    }
    else
    {
        return isset($row[$key]) && $row[$key] ? $row[$key] : $default; 
    }
}

/**
* text span 
* params : $text string text
* params : $enable enabling class
* params : $isEnable enabling class
* params : $enabledClass class on enabled mode
* params : $disabledClass class on enabled mode
**/
function text($text, $isEnable=false, $enabledClass='', $disabledClass='')
{
    if ( $isEnable )
        return '<span class="'.$enabledClass.'">'.$text.'</span>';
    else
        return '<span class="'.$disabledClass.'">'.$text.'</span>';
}


function getDayInWeek($date, $returnOnlyDate=false)
{
    $date = substr($date, strpos($date, '] ')+2);
    $param = explode("/", $date);
    $date = $param[2].'-'.$param[1].'-'.$param[0];

    $begin = new DateTime( date("Y-m-d", strtotime("last Monday", strtotime($date))) );
    $end = new DateTime( date("Y-m-d", strtotime("next Monday", strtotime($date))) );
    
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);
    
    $ret = [];
    foreach ( $period as $dt )
    {
        if ( $returnOnlyDate )
        {
            $ret[] = $dt->format( "Y-m-d" );
        }
        else
        {
            $ret[] = [
                'jam' => substr(timeInOut($dt->format( "Y-m-d" ),'in'),0,-3).' - '.substr(timeInOut($dt->format( "Y-m-d" ),'out'),0,-3),
                'dt'  => $dt->format( "Y-m-d" ),
                'tgl' => formatDate($dt->format( "Y-m-d" ), 3),
                'hari' => trans('global.day_long')[$dt->format( "D" )],
            ];
        }
    }
    
    return $ret;
}


function dateStep($start, $end)
{
    $startTime = strtotime( $start );
    $endTime = strtotime( $end );
    $ret = array();
    // Loop between timestamps, 24 hours at a time
    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
      $ret[] = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
    }
      
    return $ret;
}

function Terbilang ( $x )
{
  if ( !$x ) return '';
    
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

/*
|--------------------------------------------------------------------------
| Build array listing by custom index
|--------------------------------------------------------------------------
*/
function getRowArray($row, $primary, $name='*', $subKey='')
{
    $ret = [];
    
    if ( $row && !empty($row) )
    {
        if ( is_object($row) ) $row = json_decode(json_encode($row), true);
        
        foreach( $row as $k=>$r )
        {
            if ( is_array($primary) )
            {
                $ret[$r[$primary[0]]][ ( $subKey ? ( isset($r[$subKey]) ? $r[$subKey] : $k ) : $k ) ] = ($name=='*') ? $r : (isset($r[$name]) ? $r[$name] : null);
            }
            else
            { 
                $ret[$r[$primary]] = ($name=='*') ? $r : (isset($r[$name]) ? $r[$name] : null);
            } 
        }
    }

    return $ret;
}

/*
|--------------------------------------------------------------------------
| Read Module Json
|--------------------------------------------------------------------------
*/
function readModulInfo($fileJson)
{
    if ( file_exists($fileJson) )
    {
        return json_decode( file_get_contents($fileJson) ,true);    
    }
}

/*
|--------------------------------------------------------------------------
| PAGING START, END, LIMIT
|--------------------------------------------------------------------------
*/
function paggingInfo($obj)
{
    return [
        'start' => (($obj->currentPage()-1) * $obj->perPage() + 1),
        'end' => (($obj->currentPage()*$obj->perPage()) > $obj->total() ? $obj->total() : ($obj->currentPage()*$obj->perPage())),
        'total' => $obj->total()
    ];
}

/*
|--------------------------------------------------------------------------
| ROUND DOWN
|--------------------------------------------------------------------------
*/
function roundDown($number, $nearest=0.5)
{
    return $number ? ($number - fmod($number, $nearest)) : 0;
}

/*
|--------------------------------------------------------------------------
| ROUND UP
|--------------------------------------------------------------------------
*/
function roundUp($number, $nearest=0.5)
{
    return $number ? ($number + ($nearest - fmod($number, $nearest))) : 0;
}

/*
|--------------------------------------------------------------------------
| ToAlphabet
|--------------------------------------------------------------------------
*/
function toAlpha($num){
    return chr(substr("000".($num+65),-3));
}
