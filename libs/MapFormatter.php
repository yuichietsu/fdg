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
        $text = '';
        $size = $map->getMatrixSize();
        for ($y = 1; $y < $size - 1; $y++) {
            $strip = [];
            for ($x = 1; $x < $size - 1; $x++) {
                $v = 256;
                if ($map->getMatrixValue($x, $y)) {
                    $v = 0;
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

    public function androidResource($map)
    {
        $name = $this->args['opts']['name'] ?: 'map';
        $size = $map->getMatrixSize();
        $data = [];
        for ($y = 1; $y < $size - 1; $y++) {
            for ($x = 1; $x < $size - 1; $x++) {
                $v = 256;
                if ($map->getMatrixValue($x, $y)) {
                    $v = 0;
                    !$map->getMatrixValue($x, $y - 1) && $v += 1;
                    !$map->getMatrixValue($x + 1, $y) && $v += 2;
                    !$map->getMatrixValue($x, $y + 1) && $v += 4;
                    !$map->getMatrixValue($x - 1, $y) && $v += 8;
                }
                $data[] = $v;
            }
        }
        foreach ([512, 1024] as $s) {
            $assigned = false;
            while (!$assigned) {
                for ($i = 0, $n = count($data); $i < $n; $i++) {
                    if ($data[$i] === 0 && rand(0, $n) === $i) {
                        $data[$i] = $s;
                        $assigned = true;
                        break;
                    }
                }
            }
        }
        $xml = sprintf('<integer-array name="%s">', $name);
        for ($i = 0, $n = count($data); $i < $n; $i++) {
            $xml .= "<item>{$data[$i]}</item>";
        }
        $xml .= '</integer-array>';
        return $xml;
    }
}
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
Otehrs
256: In Walls
512: UP
1024: DOWN
*/
