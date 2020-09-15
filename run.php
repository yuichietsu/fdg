<?php
namespace oum;

error_reporting(E_ERROR);
require_once "libs/MapMaker.php";
require_once "libs/MapFormatter.php";

exit(main(parseArgv($argv, 'v')));

function main($args)
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
    $mm->args = $args;
    $mm->generate($size);
    $mf = new MapFormatter();
    $mf->args = $args;
    echo $mf->simple($mm);
    if (method_exists($mf, $method)) {
        echo $mf->$method($mm, $opts);
    } else {
        echo "method not found: $method\n";
        $r = 1;
    }
    return $r;
}

function parseArgv($argv, $flags = '')
{
    $args = [];
    $opts = [];
    $k = null;
    for ($i = 1, $n = count($argv); $i < $n; $i++) {
        $v = $argv[$i];
        if (preg_match('/^--(\\S+)=(.+)$/', $v, $m)) {
            if ($k !== null) {
                $opts[$k] = null;
            }
            $k = null;
            $opts[$m[1]] = $m[2];
        } elseif (preg_match('/^-(\\S)$/', $v, $m)) {
            if ($k !== null) {
                $opts[$k] = null;
            }
            $k = $m[1];
            if (strpos($flags, $k) !== false) {
                $opts[$k] = null;
                $k = null;
            }
        } elseif ($k !== null) {
            $opts[$k] = $v;
            $k = null;
        } else {
            $args[] = $v;
        }
    }
    return ['args' => $args, 'opts' => $opts];
}
