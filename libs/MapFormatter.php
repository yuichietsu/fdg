<?php

namespace oum;

class MapFormatter
{
    public $delimiter = '';
    public $path = ' ';
    public $wall = '#';

    public function format($map)
    {
        $text = '';
        $size = $map->getMatrixSize();
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $value = $map->getMatrixValue($x, $y) ? $this->path : $this->wall;
                $text .= $x == 0 ? $value : "{$this->delimiter}{$value}";
            }
            $text .= "\n";
        }
        return $text;
    }
}
