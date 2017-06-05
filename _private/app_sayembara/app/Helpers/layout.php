<?php
function getClassBgTrans()
{
    $data = ['navbgtransblue', 'navbgtransgreen', 'navbgtransblack', 'navbgtransred', 'navbgtransyellow', 'navbgtransviolet', 'navbgtranspink'];

    return $data[rand(0, count($data)-1)];
}
?>