<?php
namespace oum;

error_reporting(E_ERROR);
require_once "libs/MapMaker.php";
require_once "libs/MapFormatter.php";

$mm = new MapMaker();
$mm->generate(20);
$mf = new MapFormatter();
echo $mf->format($mm);
