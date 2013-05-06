<?php
/**
 * SpeedPHP验证码生成类
 */
class spVerifyCode {
	protected $width = 60; //宽度
	protected $height = 20; //高度
	protected $len = 4; //字符长度
	protected $backcolor = '#FFFFFF'; //背景色
	protected $noisenum = 50 ; //杂点数量
	protected $textsize = 22; //字体大小
	protected $font = "font.ttf"; //自定义字体
	protected $format = 'png'; //输出图片格式
	protected $fond = 'font.ttf';
	
	protected $imagename;
	protected $image;
	protected $backcolorRGB;
	protected $bordercolorRGB = NULL;
	protected $size;
	protected $sizestr2str;

	public function  __construct() {
		$params = spExt('spVerifyCode');
		if( !empty($params['width']) )$this->width = $params['width'];
		if( !empty($params['height']) )$this->height = $params['height'];
		if( !empty($params['length']) )$this->len = $params['length'];
		if( !empty($params['bgcolor']) )$this->backcolor = $params['bgcolor'];
		if( !empty($params['noisenum']) )$this->noisenum = $params['noisenum'];
		if( !empty($params['fontsize']) )$this->textsize = $params['fontsize'];
		if( !empty($params['fontfile']) )$this->font = str_replace('\\', '/', dirname(__FILE__)).'/'.$params['fontfile'];
		if( !empty($params['format']) )$this->format = strtolower($params['format']);
	}

	public function display() {
		spClass('spSession')->put('vcode', $this->make_img());
		$this->show_img();
		exit();
	}

	public function verify($var) {
	    if(empty($var))
	        return false;
	        
		$text = spClass('spSession')->get('vcode');
		if($var != $text)
		    return false;
		else {
		    return true;
	    }
	}

	public function show_img() {
		@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		@header("Cache-Control: no-store, no-cache, must-revalidate");
		@header("Pragma: no-cache");
		if($this->format == 'png') {
			@header("Content-type: image/png");
			imagepng($this->image);
		}elseif($this->format == 'jpg') {
			@header("Content-type: image/jpeg");
			imagejpeg($this->image);
		}else{
			@header("Content-type: image/gif");
			imagegif($this->image);
		}
		imagedestroy($this->image);
	}

	public function make_img() {
		$this->image = imageCreate($this->width, $this->height); //创建图片
		$this->backcolorRGB = $this->getcolor($this->backcolor);   //将#ffffff格式的背景色转换成RGB格式
		imageFilledRectangle($this->image, 0, 0, $this->width, $this->height, $this->backcolorRGB); //画一矩形 并填充
		$this->size = $this->width/$this->len; //宽度除以字符数 = 每个字符需要的宽度
		if($this->size>$this->height) $this->size=$this->height; //如果 每个字符需要的宽度 大于图片高度 则 单字符宽度=高度(正方块)
		$this->sizestr2str = $this->size/10 ; //以每个字符的1/10宽度为 字符间距
		$left = ($this->width-$this->len*($this->size+$this->sizestr2str))/$this->size;   // (验证码图片宽度 - 实际需要的宽度)/每个字符的宽度 = 距离左边的宽度
		for($i = 0; $i < 3; $i++) {  //生成干扰线
			$linecolor = imagecolorallocate($this->image, rand(0,255), rand(0,255), rand(0,255));
			imageline($this->image, rand(0,30), rand(0,30), rand(30,80), rand(0,30), $linecolor);
		}
		
		$text = '';
		for($i=0; $i<$this->len; $i++) {
			$randtext = rand(0, 9);  //验证码数字 0-9随机数
			$text .= $randtext; //写入session的数字
			$textColor = imageColorAllocate($this->image, rand(50, 155), rand(50, 155), rand(50, 155)); //图片文字颜色
			if (!isset($this->textsize) ) $this->textsize = rand(($this->size-$this->size/10), ($this->size + $this->size/10)); //如果未定义字体大小 则取随机大小
			$location = $left + ($i*$this->size+$this->size/10);
			imagettftext($this->image, $this->textsize, rand(-18,18), $location, rand($this->size-$this->size/10, $this->size+$this->size/10), $textColor, $this->font, $randtext); //生成单个字体图象
		}
		if(isset($this->noisenum)) $this->setnoise(); //杂点处理

		if(isset($this->bordercolor)){
			$this->bordercolorRGB = $this->getcolor($this->bordercolor);
			imageRectangle($this->image, 0, 0, $this->width-1, $this->height-1, $this->bordercolorRGB);
		}
		
		return $text;
	}

	protected function getcolor($color) {
		$color = eregi_replace ("^#","",$color);
		$r = $color[0].$color[1];
		$r = hexdec ($r);
		$b = $color[2].$color[3];
		$b = hexdec ($b);
		$g = $color[4].$color[5];
		$g = hexdec ($g);
		$color = imagecolorallocate ($this->image, $r, $b, $g);
		return $color;
	}

	protected function setnoise() {
		for ($i=0; $i<$this->noisenum; $i++) {
			$randColor = imageColorAllocate($this->image, rand(0, 255), rand(0, 255), rand(0, 255));
			imageSetPixel($this->image, rand(0, $this->width), rand(0, $this->height), $randColor);
		}
	}
}
/* End of this file */
