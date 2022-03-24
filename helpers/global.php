<?php
/**
 * global.php file.
 * Global shorthand functions for commonly used Yii methods.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function app()
{
    return Yii::$app;
}

function customLog($data, $logName = 'custom.log')
{
    $dt = new DateTime;
    $nowDateTime = $dt->format('Y-m-d H:i:s');
    
    $xRealIp = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '';
    $remoteAddrTmp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $xForwardedForTmp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';

    $remoteAddr = $xRealIp ? $xRealIp : $remoteAddrTmp;
    $xForwardedFor = $xForwardedForTmp;
    // if ($_SERVER['HTTP_X_REAL_IP']) {

    // } elseif ()


    $path = Yii::getAlias('@webroot');
    file_put_contents($path."/../runtime/{$logName}", $nowDateTime.' ['.$remoteAddr.'] '.'['.$xForwardedFor.'] '.$data.PHP_EOL, FILE_APPEND | LOCK_EX);
}

function var_dump_ret($mixed = null) {
  ob_start();
  var_dump($mixed);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}


function var_dump_pre($mixed = null) {
  echo '<pre>';
  var_dump($mixed);
  echo '</pre>';
  return null;
}


function stringToArray($str, $delimiter = ',')
{
    $arr = explode($delimiter, $str);
    return $arr;
}

function arrayToString($arr, $delimiter = ',')
{
    $str = '';
    for ($i=0; $i < count($arr); $i++) {
        $str .= $arr[$i];
        if ($i != count($arr) - 1) {
            $str .= $delimiter;
        }
    }

    return $str;
}


/**
 * 無條件捨去浮點數到指定位數
 */
function floorDec($val, $precision = 0)
{
    if (($pos = strpos($val, '.')) !== false) {
        $val = floatval(substr($val, 0, $pos + 1 + $precision));
    }
    return $val;
}

function floatEqual($a, $b, $epsilon = 0.00000001)
{
    // 含有有小數點才去掉最右邊的所有0
    $filteredA = $a;
    if (strpos($a, '.') !== false) {
        $filteredA = rtrim($a, 0);
    }
    
    $filteredB = $b;
    if (strpos($b, '.') !== false) {
        $filteredB = rtrim($b, 0);
    }

    if ((string) $filteredA == (string) $filteredB) {
        return true;
    } else {
        return false;
    }
}



function lcwords($str, $delimiter) {
    $temp = '';
    $words = explode($delimiter, $str);
    foreach ($words as $word) {
        $word = $delimiter . lcfirst($word);
        $temp .= $word;
    }
    return ltrim($temp, $delimiter);
}
