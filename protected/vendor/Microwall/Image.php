<?php
class Microwall_Image
{
    const REGEX_IMG_PATH = '/^.+\.(jpg|jpeg|gif|png)$/ui';
    private $_filename;
    private $_resource = null;
    private $_suffix;
    private $_width;
    private $_height;
	public function __construct($filename = null)
	{
	    if ($filename === null) {
	    	return ;
	    }
	    
	    $this->setFilename($filename);
	    $this->create();
	}
	public function setFilename($filename)
	{
	    if (!preg_match(self::REGEX_IMG_PATH, $filename, $matches)) {
	    	throw new Exception('图片格式不允许');
	    }
	    if (!is_file($filename)) {
	    	throw new Exception('图片不存在或已损坏');
	    }
	    
	    $this->_suffix = strtolower($matches[1]);
		$this->_filename = $filename;
		return $this;
	}
	
	public function setResource($resource)
	{
	    if (get_resource_type($resource) !== 'gd') {
	        throw new Exception('不是一个GD资源');
	    }
	    
	    if ($this->_resource !== null) {
	        throw new Exception('图片资源已存在');
	    }
	    
		$this->_resource = $resource;
	}
	public function getResource()
	{
		return $this->_resource;
	}
	public function getWidth()
	{
		return $this->_width;
	}
	public function getHeight()
	{
		return $this->_height;
	}
	public function getSuffix()
	{
		return $this->_suffix;
	}
	public function create()
	{
	    switch ($this->_suffix) {
	    	case 'jpg':
	    	case 'jpeg':
	    	    @$resource = imagecreatefromjpeg($this->_filename);
	    	    break;
	    	case 'gif':
	    	    @$resource = imagecreatefromgif($this->_filename);
	    	    break;
	    	case 'png':
	    	    @$resource = imagecreatefrompng($this->_filename);
	    	    break;
	    	default:
	    	    throw new Exception('图片格式不允许');
	    	    break;
	    }
	    
	    if ($resource === false) {
	        throw new Exception('创建文件时错误');
	    }
	    
	    $this->_width = imagesx($resource);
	    $this->_height = imagesy($resource);
	    $this->_resource = $resource;
	    return $this;
	}
	public function reset()
	{
		$this->_filename = null;
		$this->_resource = null;
		$this->_suffix = null;
		$this->_height = null;
		$this->_width = null;
		return $this;
	}
	public function resize($width, $height, $isMaintain = true)
	{
	    if ($this->_resource === null) {
	    	throw new Exception('图片未被创建');
	    }
	    
	    if ($isMaintain) {
    		if ($width / $height > $this->_width / $this->_height) {
    			$w = $this->_width;
    			$h = floor($height / $width * $w);
    			$x = 0;
    			$y = floor(($this->_height - $h) / 2);
    		} else {
    		    $h = $this->_height;
    		    $w = floor($width / $height * $h);
    		    $y = 0;
    		    $x = floor(($this->_width - $w) / 2);
    		}
	    } else {
	    	$x = 0;
	    	$y = 0;
	    	$w = $this->_width;
	    	$h = $this->_height;
	    }
		
		$newImage = imagecreatetruecolor($width, $height);
		$isResize = imagecopyresampled($newImage, $this->_resource, 0, 0, $x, $y, $width, $height, $w, $h);
		
		if (!$isResize) {
		    return false;
		}
		
		$suffix = $this->_suffix;
		$this->reset();
		$this->_suffix = $suffix;
		$this->_width = $width;
		$this->_height = $height;
		$this->_resource = $newImage;
		
		return true;
	}
	
	/**
	 * Save image
	 * 
	 * @param string $savePath
	 * @param string $name
	 * @param string $suffix
	 * @param number $quality
	 * @throws Exception
	 * @return boolean|string
	 */
	public function save($savePath, $name = null, $suffix = null, $quality = 100)
	{
	    if ($this->_resource === null) {
	    	throw new Exception('图片未被创建');
	    }
	    
	    if (!is_dir($savePath)) {
	        throw new Exception('该目录"' . $savePath . '"未被找到');
	    }
	    
	    $hasSuffix = false;
	    if ($suffix === null) {
	    	if ($name === null) {
	    		$name = self::randomFileName();
	    		$suffix = $this->_suffix;
	    	} else {
	    		preg_match(self::REGEX_IMG_PATH, $name, $matches);
	    		if (!isset($matches[1])) {
	    			$suffix = $this->_suffix;
	    		} else {
	    			$suffix = $matches[1];
	    			$hasSuffix = true;
	    		}
	    	}
	    }
	    
	    $isSave = false;
	    switch ($suffix) {
	    	case 'jpg':
	    	case 'jpeg':
	    	    $isSave = imagejpeg($this->_resource, $hasSuffix ? "$savePath/$name" : "$savePath/$name.$suffix", $quality);
	    	case 'gif':
	    	    $isSave = imagegif($this->_resource, $hasSuffix ? "$savePath/$name" : "$savePath/$name.$suffix");
	    	    break;
	    	case 'png':
	    	    $isSave = imagepng($this->_resource, $hasSuffix ? "$savePath/$name" : "$savePath/$name.$suffix");
	    	    break;
	    	default:
	    	    throw new Exception('图片格式不允许');
	    	    break;
	    }
	    
	    if (!$isSave) {
	    	return false;
	    }
	    
	    return $hasSuffix ? $name : "$name.$suffix";
	}
	public static function randomFileName()
	{
		return uniqid();
	}
}