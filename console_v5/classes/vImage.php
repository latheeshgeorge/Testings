<? 
class vImage{

	var $numChars = 3;
	var $w;
	var $h = 14;
	var $colBG = "248 248 248";
	var $colTxt = "0 0 0";
	var $colBorder = "125 125 125";
	var $charx = 10;
	var $numCirculos = 1;
		
	function __construct()
	{
		session_start();
	}
	function setbgcolor($clr)
	{
		$this->colBG = $clr;
	}
	function setbordercolor($clr)
	{
		$this->colBorder = $clr;
	}
	function settextcolor($clr)
	{
		$this->colTxt = $clr;
	}
	function setcircle_cnt($cnt)
	{
		$this->numCirculos = $cnt;
	}
	function gerText($num,$name='vImageCodS')
	{
		if (($num != '')&&($num > $this->numChars)) $this->numChars = $num;		
		$this->texto = $this->gerString();
		$_SESSION['Sess_'.$name] = $this->texto;
	}
	
	function loadCodes($name='vImageCodP'){
		$this->postCode		= $_POST[$name];
		$this->sessionCode 	= $_SESSION['Sess_'.$name];
	}
	
	function checkCode($name)
	{
		
		if (isset($this->postCode)) $this->loadCodes($name);
		if (strtolower($this->postCode) == strtolower($this->sessionCode))
			return true;
		else
			return false;
	}
	
	function showCodBox($mode=0,$name='vImageCodP',$extra='')
	{
		$str = "<input type=\"text\" name=\"$name\" ".$extra." /> ";
		
		if ($mode)
			echo $str;
		else
			return $str;
	}
	function showImage()
	{
		$this->gerImage();
		
		header("Content-type: image/png");
		ImagePng($this->im);
	}
	
	function gerImage()
	{
		$this->w = ($this->numChars*$this->charx) + 40; #5px de cada lado, 4px por char
		$this->im = imagecreatetruecolor($this->w, $this->h); 
		imagefill($this->im, 0, 0, $this->getColor($this->colBorder));
		imagefilledrectangle ( $this->im, 1, 1, ($this->w-2), ($this->h-2), $this->getColor($this->colBG) );

		for ($i=1;$i<=$this->numCirculos;$i++)
		{
			$randomcolor = imagecolorallocate ($this->im , rand(100,255), rand(100,255),rand(100,255));
			imageellipse($this->im,rand(0,$this->w-10),rand(0,$this->h-3), rand(20,60),rand(20,60),$randomcolor);
		}
		$ident = 20;
		for ($i=0;$i<$this->numChars;$i++)
		{
			$char = substr($this->texto, $i, 1);
			$font = 5;//rand(4,5);
			$y = round(($this->h-15)/2);
			$col = $this->getColor($this->colTxt);
			//if (($i%2) == 0){
				imagechar ( $this->im, $font, $ident, $y, strtoupper($char), $col );
			//}else{
			//	imagecharup ( $this->im, $font, $ident, $y+10, $char, $col );
			//}
			$ident = $ident+$this->charx;
		}

	}
	
	function getColor($var)
	{
		$rgb = explode(" ",$var);
		$col = imagecolorallocate ($this->im, $rgb[0], $rgb[1], $rgb[2]);
		return $col;
	}
	
	function gerString()
	{
		rand(0,time());
		$possible="AGHacefhjkrStVxY124579";
		while(strlen($str)<$this->numChars)
		{
				$str.=substr($possible,(rand()%(strlen($possible))),1);
		}
		$txt = $str;
		return $txt;
	}
} 
?>
