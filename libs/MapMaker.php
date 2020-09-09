<?php

// Created on 2006/08/23
class MapMaker {

	protected $matrix = null;
	protected $matrixSize = 0;
	protected $matrixData = null;

	protected function getMatrixValue($_x, $_y) {
		return $this->matrix[$_y * $this->matrixSize + $_x];
	}

	protected function setMatrixOneValue($_x, $_y, $_word) {
		$this->matrix[$_y * $this->matrixSize + $_x] = $_word;
	}

	protected function setMatrixValue($_x, $_y, $_horizontal, $_word) {
		$len = strlen($_word);
		if ($_horizontal) {
			for ($i = 0; $i < $len; $i++) {
				$this->matrix[$_y * $this->matrixSize + $_x + $i] = $_word[$i];
			}
		} else {
			for ($i = 0; $i < $len; $i++) {
				$this->matrix[($_y + $i) * $this->matrixSize + $_x] = $_word[$i];
			}
		}
	}

	protected function checkMatrix($_horizontal, $_x, $_y, $_len) {
		$ret = null;
		if ($_horizontal) {

			for ($i = $_y; $i < $_len + $_y; $i++) {
				if (($_x == 0 || $this->getMatrixValue($_x -1, $i) == null) && ($_x == $this->matrixSize - 1 || $this->getMatrixValue($_x +1, $i) == null)) {
					$min = $max = $_x;
					// 小さい方
					for ($j = $_x -1; 0 <= $j; $j--) {
						// 自身
						$valid = ($this->getMatrixValue($j, $i) == null);
						// 上
						if ($valid && $i != 0 && $this->getMatrixValue($j, $i -1) != null) {
							$valid = false;
						}
						// 下
						if ($valid && $i != $this->matrixSize - 1 && $this->getMatrixValue($j, $i +1) != null) {
							$valid = false;
						}
						// 左
						if ($valid && $j != 0 && $this->getMatrixValue($j -1, $i) != null) {
							$valid = false;
						}
						if ($valid) {
							$min = $j;
						} else {
							break;
						}
					}
					// 大きいほう
					for ($j = $_x +1; $j < $this->matrixSize; $j++) {
						// 自身
						$valid = ($this->getMatrixValue($j, $i) == null);
						// 上
						if ($valid && $i != 0 && $this->getMatrixValue($j, $i -1) != null) {
							$valid = false;
						}
						// 下
						if ($valid && $i != $this->matrixSize - 1 && $this->getMatrixValue($j, $i +1) != null) {
							$valid = false;
						}
						// 右
						if ($valid && $j != $this->matrixSize - 1 && $this->getMatrixValue($j +1, $i) != null) {
							$valid = false;
						}
						if ($valid) {
							$max = $j;
						} else {
							break;
						}
					}
					if (0 < $max - $min) {

						if ($ret == null) {
							$ret = array ();
						}
						array_push($ret, array (
							'index' => $i,
							'min' => $min,
							'max' => $max
						));
					}
				}

			}

		} else {

			for ($i = $_x; $i < $_len + $_x; $i++) {
				if (($_y == 0 || $this->getMatrixValue($i, $_y -1) == null) && ($_y == $this->matrixSize - 1 || $this->getMatrixValue($i, $_y +1) == null)) {
					$min = $max = $_y;
					// 小さい方
					for ($j = $_y -1; 0 <= $j; $j--) {
						// 自身
						$valid = ($this->getMatrixValue($i, $j) == null);
						// 左
						if ($valid && $i != 0 && $this->getMatrixValue($i -1, $j) != null) {
							$valid = false;
						}
						// 右
						if ($valid && $i != $this->matrixSize - 1 && $this->getMatrixValue($i +1, $j) != null) {
							$valid = false;
						}
						// 上
						if ($valid && $j != 0 && $this->getMatrixValue($i, $j -1) != null) {
							$valid = false;
						}
						if ($valid) {
							$min = $j;
						} else {
							break;
						}
					}
					// 大きいほう
					for ($j = $_y +1; $j < $this->matrixSize; $j++) {
						// 自身
						$valid = ($this->getMatrixValue($i, $j) == null);
						// 左
						if ($valid && $i != 0 && $this->getMatrixValue($i -1, $j) != null) {
							$valid = false;
						}
						// 右
						if ($valid && $i != $this->matrixSize - 1 && $this->getMatrixValue($i +1, $j) != null) {
							$valid = false;
						}
						// 下
						if ($valid && $j != $this->matrixSize - 1 && $this->getMatrixValue($i, $j +1) != null) {
							$valid = false;
						}
						if ($valid) {
							$max = $j;
						} else {
							break;
						}
					}

					if (0 < $max - $min) {
						if ($ret == null) {
							$ret = array ();
						}
						array_push($ret, array (
							'index' => $i,
							'min' => $min,
							'max' => $max
						));
					}
				}

			}
		}
		return $ret;
	}

	public function getMatrix($_size) {

		// 初期設定
		$this->matrixSize = $_size;
		$this->matrix = array ();
		$this->matrixData = array ();
		$used = array ();
		$done = array();

		// 最初の１ワードは、横で5文字以上、つまり$_sizeは5以上でなければなない
		$horizontal = true;
		$x = rand(0, $_size -5);
		$y = rand(0, $_size -1);
		$wSize = $_size - $x;
		$wSize = rand(5, $wSize);
		$pattern = null;
		for ($ii = 0; $ii < $wSize; $ii++) {
			$pattern .= '1';
		}
		$this->setMatrixValue($x, $y, $horizontal, $pattern);
		$u = array (
			'x' => $x,
			'y' => $y,
			'h' => $horizontal,
			's' => $wSize,
		);
		array_push($used, $u);

		$usedCount = 0;
		for (;;) {

			$horizontal = !$horizontal;
			$ret = null;
			if( !$done[$y*$this->matrixSize+$x] ) {
				$ret = $this->checkMatrix($horizontal, $x, $y, $wSize);
			}

			if ($ret != null) {

				$pattern = null;

				// 下のforeachとdoでx,yが上書きされてしまうので退避
				$xx = $x;
				$yy = $y;

				$adp = $ret[rand(0, count($ret) - 1)];

				// リトライの際に、オリジナルのx,yを復元
				$x = $xx;
				$y = $yy;
				if ($horizontal) {
					// 横
					$y = $adp['index'];
					$r = $this->getPosition($adp, $x);
					$x = $r['piv'];
					$wSize = $r['size'];
				} else {
					// 縦
					$x = $adp['index'];
					$r = $this->getPosition($adp, $y);
					$y = $r['piv'];
					$wSize = $r['size'];
				}

				if ($horizontal) {
					for ($ii = 0; $ii < $wSize; $ii++) {
						$value = $this->getMatrixValue($x + $ii, $y);
						$pattern .= $value == null ? '1' : $value;
					}
				} else {
					for ($ii = 0; $ii < $wSize; $ii++) {
						$value = $this->getMatrixValue($x, $y + $ii);
						$pattern .= $value == null ? '1' : $value;
					}
				}

				$usedCount = 0;
				$this->setMatrixValue($x, $y, $horizontal, $pattern);
				$wData = array (
					'x' => $x,
					'y' => $y,
					'h' => $horizontal,
					's' => $wSize
				);
				array_push($used, $wData);

			}
			elseif ($usedCount == count($used)) {
				break;
			} else {
				$done[$y*$this->matrixSize+$x] = true;
				$u = $used[$usedCount++];
				$x = $u['x'];
				$y = $u['y'];
				$wSize = $u['s'];
				$horizontal = $u['h'];
			}

		}

		return $this->matrix;
	}

	protected function getPosition($_adp, $_piv) {
		if ($_adp['min'] == $_piv) {
			$min = $_piv;
			$max = rand($_piv +1, $_adp['max']);
			$piv = $min;
		}
		elseif ($_adp['max'] == $_piv) {
			$max = $_adp['max'];
			$min = rand($_adp['min'], $_piv -1);
			$piv = $min;
		} else {
			$min = rand($_adp['min'], $_piv);
			$max = rand((int) ($_piv == $min ? $_piv +1 : $_piv), $_adp['max']);
			$piv = $min;
		}
		$size = $max - $piv +1;
		return array (
			'piv' => $piv,
			'size' => $size
		);
	}

	public function getMatrixData($_size) {
		$size = $_size -2;

		$matrix = $this->getMatrix($size);

		$html = '1';
		for ($x = 0; $x < $size; $x++) {
			$html .= "\t1";
		}
		$html .= "\t1\n";

		for ($y = 0; $y < $size; $y++) {
			for ($x = 0; $x < $size; $x++) {
				$value = $this->getMatrixValue($x, $y);
				if ($value == null) {
					$html .= $x==0?"1\t1":"\t1";
				} else {
					$html .= $x==0?"1\t0":"\t0";
				}
			}
			$html .= "\t1\n";
		}
		$html .= '1';
		for ($x = 0; $x < $size; $x++) {
			$html .= "\t1";
		}
		$html .= "\t1\n";
		return $html;
	}

}
