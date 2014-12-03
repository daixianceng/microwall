<?php
require_once 'Microwall/System.php';

class Microwall_Dir
{
	protected $_name;
	protected $_size;
	protected $_formatSize;
	
	public function __construct($dir)
	{
		if (is_dir($dir))
			$this->_name = $dir;
		else
			throw new Exception('dir not a real dir');
	}
	
	/*
	 * 该方法因权限与安装等问题暂被替换
	 * 
	public function getSize()
	{
		if ($this->_size !== null)
			return $this->_size;
		else {
			switch(Microwall_System::getOS()) {
				case Microwall_System::OS_LINUX :
					$io = popen('/usr/bin/du -sk ' . $this->_name, 'r');
					$size = fgets($io, 4096);
					$size = substr($size, 0, strpos ( $size, "\t" ));
					pclose ( $io );
					$this->_size = $size;
					break;
				case Microwall_System::OS_WIN :
					$obj = new COM('scripting.filesystemobject');
					if (is_object($obj)) {
						$ref = $obj->getfolder($this->_name);
						$this->_size = $ref->size;
						$obj = null;
					} else
						throw new Exception('Can not create object');
					break;
				default :
					break;
			}
			return $this->_size;
		}
	}
	*/
	
	public function getSize()
	{
		$size = 0;
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_name)) as $file){
			$size += $file->getSize();
		}
		return $size;
	}
	
	public function getFormatSize()
	{
		if ($this->_formatSize !== null)
			return $this->_formatSize;
		
		$size = $this->getSize();
		$units = explode(' ', 'B KB MB GB TB PB');
		$mod = 1024;
	
		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}
	
		$endIndex = strpos($size, ".") + 3;
	
		$this->_formatSize = substr($size, 0, $endIndex) . ' ' . $units[$i];
		return $this->_formatSize;
	}
}