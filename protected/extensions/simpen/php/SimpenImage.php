<?php
class SimpenImage
{
	/**
	 * 控件的name值
	 * 
	 * @var string
	 */
	private $_inputName = 'simpenImage';
	
	/**
	 * 允许上传图片的最大大小，单位bit
	 * 
	 * @var number
	 */
	private $_maxSize = 2000000;
	
	/**
	 * 允许上传图片的格式
	 * 
	 * @var array
	 */
	private $_imageTypes = array('png', 'jpg', 'jpeg', 'gif', 'bmp');
	
	/**
	 * 图片成功接收之后的文件名
	 * 
	 * @var string
	 */
	private $_fileName;
	
	/**
	 * 图片的保存位置
	 * 
	 * @var string
	 */
	private $_savePath;
	
	/**
	 * 图片在页面中可访问的路径
	 * 
	 * @var string
	 */
	private $_baseUrl;
	
	/**
	 * 上传错误码
	 * 
	 * @var number
	 */
	private $_error;
	
	/**
	 * 上传错误消息
	 * 
	 * @var string
	 */
	private $_errorMsg;
	
	public function __construct()
	{}
	
	/**
	 * 接收图片并保存到相应位置
	 * 
	 * 成功返回true，或者在失败时返回false
	 * 
	 * @return boolean
	 */
	public function receive()
	{
		$img_upload = $_FILES[$this->_inputName];
		switch ($img_upload['error']) {
			case UPLOAD_ERR_OK :
				if ($img_upload['size'] > $this->_maxSize) {
					$this->_error = 8;
					$this->_errorMsg = '图片大小超过被允许范围';
					return false;
				}
				
				$name_arr = explode('.', $img_upload['name']);
				$type = strtolower($name_arr[count($name_arr) - 1]);
				if (!in_array($type, $this->_imageTypes)) {
					$this->_error = 9;
					$this->_errorMsg = '图片类型错误';
					return false;
				}
				
				$this->_fileName = $this->_randomFileName() . '.' . $type;
				move_uploaded_file($img_upload['tmp_name'], $this->getFullFileName());
				
				$this->_error = UPLOAD_ERR_OK;
				$this->_errorMsg = '上传成功';
				return true;
				break;
			case UPLOAD_ERR_INI_SIZE :
			case UPLOAD_ERR_FORM_SIZE :
			case UPLOAD_ERR_PARTIAL :
			case UPLOAD_ERR_NO_FILE :
			case UPLOAD_ERR_NO_TMP_DIR :
			case UPLOAD_ERR_CANT_WRITE :
			default :
				$this->_error = $img_upload['error'];
				$this->_errorMsg = '上传过程中错误';
				return false;
				break;
		}
	}
	
	/**
	 * 向页面输出参数
	 * 
	 */
	public function output()
	{
		$data = array(
			'src' => $this->getImageUrl(),
			'error' => $this->_error,
			'errorMsg' => $this->_errorMsg
		);
		
		$json = json_encode($data);
		$output = '<script type="text/javascript">parent.jQuery.simpenImage(\'' . $json . '\')</script>';
		
		echo $output;
	}
	
	/**
	 * 生成唯一文件名并返回
	 * 
	 * @return string
	 */
	private function _randomFileName()
	{
		return uniqid('simpen_');
	}
	
	/**
	 * 设置控件的name值
	 * 
	 * @param string $input_name
	 * @throws Exception
	 * @return SimpenImage
	 */
	public function setInputName($input_name)
	{
		if (empty($input_name)) {
			throw new Exception('不能设置成空值');
		}
		$this->_inputName = (string) $input_name;
		
		return $this;
	}
	
	/**
	 * 设置图片所允许的最大大小，单位bit
	 * 
	 * @param number $max_size
	 * @throws Exception
	 * @return SimpenImage
	 */
	public function setMaxSize($max_size)
	{
		$max_size = (int) $max_size;
		if ($max_size < 1) {
			throw new Exception('图片大小设置有误');
		}
		$this->_maxSize = $max_size;
		
		return $this;
	}
	
	/**
	 * 设置图片类型
	 * 
	 * @param array $image_types
	 * @return SimpenImage
	 */
	public function setImageTypes(array $image_types)
	{
		$this->_imageTypes = $image_types;
		
		return $this;
	}
	
	/**
	 * 设置图片的保存路径
	 * 
	 * @param string $save_path
	 * @throws Exception
	 * @return SimpenImage
	 */
	public function setSavePath($save_path)
	{
		if (is_dir($save_path)) {
			$this->_savePath = $save_path;
		} else {
			throw new Exception('图片保存路径设置有误');
		}
		
		return $this;
	}
	
	/**
	 * 设置图片在页面中可访问的路径
	 * 
	 * @param string $base_url
	 * @return SimpenImage
	 */
	public function setBaseUrl($base_url)
	{
		$base_url = (string) $base_url;
		if (mb_substr($base_url, -1, 1, 'utf8') !== '/') {
			$base_url .= '/';
		}
		$this->_baseUrl = $base_url;
		
		return $this;
	}
	
	/**
	 * 返回文件名，只在图片成功接收后有意义
	 * 
	 * @return string
	 */
	public function getFileName()
	{
		return $this->_fileName;
	}
	
	/**
	 * 返回文件在硬盘上的绝对路径名
	 * 
	 * @return string
	 */
	public function getFullFileName()
	{
		return $this->_savePath . '/' . $this->_fileName;
	}
	
	/**
	 * 返回图片在页面中可访问的路径
	 * 
	 * @return string
	 */
	public function getImageUrl()
	{
		return $this->_baseUrl . $this->_fileName;
	}
	
	/**
	 * 返回错误码
	 * 
	 * @return number
	 */
	public function getError()
	{
		return $this->_error;
	}
	
	/**
	 * 返回错误消息
	 * 
	 * @return string
	 */
	public function getErrorMsg()
	{
		return $this->_errorMsg;
	}
}