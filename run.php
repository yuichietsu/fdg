<?php
namespace oum;

error_reporting(E_ERROR);
require_once "libs/MapMaker.php";
require_once "libs/MapFormatter.php";

exit(main());

function main()
{
    $r = 0;
    $size = 20;
    $method = 'simple';
    $opts = getopt('t:s:');
    if (isset($opts['t'])) {
        $method = $opts['t'];
    }
    if (isset($opts['s'])) {
        $size = $opts['s'];
    }
    $mm = new MapMaker();
    $mm->generate($size);
    $mf = new MapFormatter();
    echo $mf->simple($mm);
    if (method_exists($mf, $method)) {
        echo $mf->$method($mm);
    } else {
        echo "method not found: $method\n";
        $r = 1;
    }
    return $r;
}
