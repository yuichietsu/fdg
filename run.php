<?php
namespace oum;

error_reporting(E_ERROR);
require_once "libs/MapMaker.php";
require_once "libs/MapFormatter.php";

exit(main());

function main()
{
    $r = 0;
    $opts = getopt('t:');
    $method = 'simple';
    if (isset($opts['t'])) {
        $method = $opts['t'];
    }

    $mm = new MapMaker();
    $mm->generate(20);
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
