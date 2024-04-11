<?
class Image {
		var $img, $dimg, $simg;
		var $x1, $x2, $y1, $y2;
		var $picName;
		var $spColor;


		function Image($x=400, $y=350){                                 ///     이미지 생성
				//$this->img = imagecreatetruecolor($x, $y);
				$this->img = imagecreate($x, $y);
		}

		function color($rgb=0){                                         ///     색 지정
				$rgb = str_pad($rgb, 6, 0, STR_PAD_LEFT);
				$r = substr($rgb, 0, 2);
				$g = substr($rgb, 2, 2);
				$b = substr($rgb, 4, 2);

				return imagecolorallocate($this->img, hexdec($r), hexdec($g), hexdec($b));
		}


		function line($x1, $y1, $x2, $y2){                              ///     선 만들기
				imageline($this->img, $x1, $y1, $x2, $y2, $this->spColor);
		}


		function ellipse($x, $y, $w, $h){                               ///     타원 만들기
				//imageellipse($this->img, $x, $y, $w, $h, $this->spColor);
				imagefilledellipse($this->img, $x, $y, $w, $h, $this->spColor);
		}

		function draw(){                                                ///     이미지 그리기
				ImagePNG($this->img);
				ImageDestroy($this->img);
		}

}
?>