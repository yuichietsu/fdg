<?php
error_reporting(E_ERROR);

require_once "libs/MapMaker.php";

$mm = new MapMaker();
echo $mm->getMatrixData(20);
