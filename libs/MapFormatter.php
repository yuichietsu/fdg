<?php

namespace oum;

class MapFormatter
{
    public $delimiter = '';
    public $path = ' ';
    public $wall = '#';

    public function simple($map)
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

    public function rpg($map)
    {
        /*
        Wall
          1: U
          2: R
          4: D
          8: L
        Door
         16: U
         32: R
         64: D
        128: L
         */
        $text = '';
        $size = $map->getMatrixSize();
        for ($y = 1; $y < $size - 1; $y++) {
            $strip = [];
            for ($x = 1; $x < $size - 1; $x++) {
                $v = 0;
                if ($map->getMatrixValue($x, $y)) {
                    !$map->getMatrixValue($x, $y - 1) && $v += 1;
                    !$map->getMatrixValue($x + 1, $y) && $v += 2;
                    !$map->getMatrixValue($x, $y + 1) && $v += 4;
                    !$map->getMatrixValue($x - 1, $y) && $v += 8;
                }
                $strip[] = $v;
            }
            $text .= implode(',', $strip) . ",\n";
        }
        return $text;
    }
}
