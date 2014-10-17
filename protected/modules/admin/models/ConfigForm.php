<?php
class ConfigForm extends CFormModel
{
	public $name;
	public $theme;
	public $language;
	public $keywords;
	public $description;
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function init()
	{
		$this->name = Yii::app()->name;
		$this->theme = Yii::app()->theme->name;
		$this->language = Yii::app()->language;
		$this->keywords = Yii::app()->params['keywords'];
		$this->description = Yii::app()->params['description'];
	}
	
	public function rules()
	{
		return array(
			array('name, theme, language', 'required'),
			array('name, theme, language, keywords, description', 'length', 'max' => 255)
		);
	}
	
	protected function afterValidate()
	{
		if (!$this->hasErrors())
			$this->assignValues();
		
		parent::afterValidate();
	}
	
	public static function getThemeNames()
	{
		$themes = Yii::app()->themeManager->getThemeNames();
		foreach ($themes as $theme)
			$return[$theme] = $theme;
		
		return $return;
	}
	
	public static function getLanguages()
	{
		return include Yii::getPathOfAlias('admin.messages') . '/config.php';
	}
	
	public function assignValues()
	{
		$configPath = Yii::getPathOfAlias('application.config') . DIRECTORY_SEPARATOR;
		$content = file_get_contents($configPath . 'main.tpl');
		
		foreach ($this->attributes as $name => $value)
			// 只保证传递来的数据不破坏配置文件的结构，但不保证不破坏前台页面。
			$content = str_replace("<{$name}>", str_replace('\'', '\\\'', str_replace('\\', '\\\\', $value)), $content);
		
		file_put_contents($configPath . 'main.php', $content);
	}
}