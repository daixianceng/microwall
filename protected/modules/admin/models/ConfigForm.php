<?php
class ConfigForm extends CFormModel
{
	public $name;
	public $theme;
	public $language;
	public $keywords;
	public $description;
	public $duration;
	public $timezone;
	
	private static $_durations;
	private static $_themeNames;
	private static $_languages;
	private static $_timezones;
	
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
		$this->duration = Yii::app()->params['cacheDuration'];
		$this->timezone = Yii::app()->timeZone;
	}
	
	public function rules()
	{
		return array(
			array('name, theme, language, duration, timezone', 'required'),
			array('name, theme, language, keywords, description', 'length', 'max' => 255),
			array('duration', 'authenticateDuration'),
			array('timezone', 'authenticateTimezone')
		);
	}
	
	public function authenticateDuration($attribute, $params)
	{
		$durations = self::getDurations();
		if (!isset($durations[$this->duration]))
			$this->addError($attribute, 'The duration invalid.');
	}
	
	public function authenticateTimezone($attribute, $params)
	{
		$timezones = self::getTimezones();
		if (!isset($timezones[$this->timezone]))
			$this->addError($attribute, 'The timezone invalid.');
	}
	
	protected function afterValidate()
	{
		if (!$this->hasErrors())
			$this->assignValues();
		
		parent::afterValidate();
	}
	
	public static function getThemeNames()
	{
		if (self::$_themeNames !== null)
			return self::$_themeNames;
		$themes = Yii::app()->themeManager->getThemeNames();
		foreach ($themes as $theme)
			self::$_themeNames[$theme] = $theme;
		
		return self::$_themeNames;
	}
	
	public static function getLanguages()
	{
		if (self::$_languages !== null)
			return self::$_languages;
		
		self::$_languages = include Yii::getPathOfAlias('admin.messages') . '/config.php';
		return self::$_languages;
	}
	
	public static function getDurations()
	{
		if (self::$_durations !== null)
			return self::$_durations;
		self::$_durations['0'] = Yii::t('AdminModule.default', 'Turn off caching');
		self::$_durations['900'] = Yii::t('AdminModule.default', '15 minutes');
		self::$_durations['3600'] = Yii::t('AdminModule.default', '1 hour');
		self::$_durations['14400'] = Yii::t('AdminModule.default', '4 hours');
		self::$_durations['86400'] = Yii::t('AdminModule.default', '1 day');
		self::$_durations['604800'] = Yii::t('AdminModule.default', '7 days');
		
		return self::$_durations;
	}
	
	public static function getTimezones()
	{
		if (self::$_timezones !== null)
			return self::$_timezones;
		self::$_timezones['Pacific/Wake'] = Yii::t('AdminModule.timezones', '(UTC-12:00) International Date Line West');
		self::$_timezones['Pacific/Midway'] = Yii::t('AdminModule.timezones', '(UTC-11:00) Midway Island, Samoa');
		self::$_timezones['Pacific/Honolulu'] = Yii::t('AdminModule.timezones', '(UTC-10:00) Hawaii');
		self::$_timezones['US/Alaska'] = Yii::t('AdminModule.timezones', '(UTC-09:00) Alaska');
		self::$_timezones['America/Los_Angeles'] = Yii::t('AdminModule.timezones', '(UTC-08:00) Pacific Time (US and Canada); Tijuana');
		self::$_timezones['US/Mountain'] = Yii::t('AdminModule.timezones', '(UTC-07:00) Mountain Time (US and Canada)');
		self::$_timezones['America/Chihuahua'] = Yii::t('AdminModule.timezones', '(UTC-07:00) Chihuahua, La Paz, Mazatlan');
		self::$_timezones['US/Arizona'] = Yii::t('AdminModule.timezones', '(UTC-07:00) Arizona');
		self::$_timezones['US/Central'] = Yii::t('AdminModule.timezones', '(UTC-06:00) Central Time (US and Canada)');
		self::$_timezones['Canada/Saskatchewan'] = Yii::t('AdminModule.timezones', '(UTC-06:00) Saskatchewan');
		self::$_timezones['America/Mexico_City'] = Yii::t('AdminModule.timezones', '(UTC-06:00) Guadalajara, Mexico City, Monterrey');
		self::$_timezones['America/Managua'] = Yii::t('AdminModule.timezones', '(UTC-06:00) Central America');
		self::$_timezones['US/Eastern'] = Yii::t('AdminModule.timezones', '(UTC-05:00) Eastern Time (US and Canada)');
		self::$_timezones['US/East-Indiana'] = Yii::t('AdminModule.timezones', '(UTC-05:00) Indiana (East)');
		self::$_timezones['America/Bogota'] = Yii::t('AdminModule.timezones', '(UTC-05:00) Bogota, Lima, Quito');
		self::$_timezones['Canada/Atlantic'] = Yii::t('AdminModule.timezones', '(UTC-04:00) Atlantic Time (Canada)');
		self::$_timezones['America/Caracas'] = Yii::t('AdminModule.timezones', '(UTC-04:00) Caracas, La Paz');
		self::$_timezones['America/Santiago'] = Yii::t('AdminModule.timezones', '(UTC-04:00) Santiago');
		self::$_timezones['Canada/Newfoundland'] = Yii::t('AdminModule.timezones', '(UTC-03:30) Newfoundland and Labrador');
		self::$_timezones['America/Sao_Paulo'] = Yii::t('AdminModule.timezones', '(UTC-03:00) Brasilia');
		self::$_timezones['America/Argentina/Buenos_Aires'] = Yii::t('AdminModule.timezones', '(UTC-03:00) Buenos Aires, Georgetown');
		self::$_timezones['America/Godthab'] = Yii::t('AdminModule.timezones', '(UTC-03:00) Greenland');
		self::$_timezones['America/Noronha'] = Yii::t('AdminModule.timezones', '(UTC-02:00) Mid-Atlantic');
		self::$_timezones['Atlantic/Azores'] = Yii::t('AdminModule.timezones', '(UTC-01:00) Azores');
		self::$_timezones['Atlantic/Cape_Verde'] = Yii::t('AdminModule.timezones', '(UTC-01:00) Cape Verde Islands');
		self::$_timezones['Etc/Greenwich'] = Yii::t('AdminModule.timezones', '(UTC) Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London');
		self::$_timezones['Africa/Casablanca'] = Yii::t('AdminModule.timezones', '(UTC) Casablanca, Monrovia');
		self::$_timezones['Europe/Belgrade'] = Yii::t('AdminModule.timezones', '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague');
		self::$_timezones['Europe/Sarajevo'] = Yii::t('AdminModule.timezones', '(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb');
		self::$_timezones['Europe/Brussels'] = Yii::t('AdminModule.timezones', '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris');
		self::$_timezones['Europe/Amsterdam'] = Yii::t('AdminModule.timezones', '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna');
		self::$_timezones['Africa/Lagos'] = Yii::t('AdminModule.timezones', '(UTC+01:00) West Central Africa');
		self::$_timezones['Europe/Bucharest'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Bucharest');
		self::$_timezones['Africa/Cairo'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Cairo');
		self::$_timezones['Europe/Helsinki'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius');
		self::$_timezones['Europe/Athens'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Athens, Istanbul, Minsk');
		self::$_timezones['Asia/Jerusalem'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Jerusalem');
		self::$_timezones['Africa/Harare'] = Yii::t('AdminModule.timezones', '(UTC+02:00) Harare, Pretoria');
		self::$_timezones['Europe/Volgograd'] = Yii::t('AdminModule.timezones', '(UTC+03:00) Moscow, St. Petersburg, Volgograd');
		self::$_timezones['Asia/Kuwait'] = Yii::t('AdminModule.timezones', '(UTC+03:00) Kuwait, Riyadh');
		self::$_timezones['Africa/Nairobi'] = Yii::t('AdminModule.timezones', '(UTC+03:00) Nairobi');
		self::$_timezones['Asia/Baghdad'] = Yii::t('AdminModule.timezones', '(UTC+03:00) Baghdad');
		self::$_timezones['Asia/Tehran'] = Yii::t('AdminModule.timezones', '(UTC+03:30) Tehran');
		self::$_timezones['Asia/Muscat'] = Yii::t('AdminModule.timezones', '(UTC+04:00) Abu Dhabi, Muscat');
		self::$_timezones['Asia/Baku'] = Yii::t('AdminModule.timezones', '(UTC+04:00) Baku, Tbilisi, Yerevan');
		self::$_timezones['Asia/Kabul'] = Yii::t('AdminModule.timezones', '(UTC+04:30) Kabul');
		self::$_timezones['Asia/Yekaterinburg'] = Yii::t('AdminModule.timezones', '(UTC+05:00) Ekaterinburg');
		self::$_timezones['Asia/Karachi'] = Yii::t('AdminModule.timezones', '(UTC+05:00) Islamabad, Karachi, Tashkent');
		self::$_timezones['Asia/Calcutta'] = Yii::t('AdminModule.timezones', '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi');
		self::$_timezones['Asia/Katmandu'] = Yii::t('AdminModule.timezones', '(UTC+05:45) Kathmandu');
		self::$_timezones['Asia/Dhaka'] = Yii::t('AdminModule.timezones', '(UTC+06:00) Astana, Dhaka');
		self::$_timezones['Asia/Calcutta'] = Yii::t('AdminModule.timezones', '(UTC+06:00) Sri Jayawardenepura');
		self::$_timezones['Asia/Almaty'] = Yii::t('AdminModule.timezones', '(UTC+06:00) Almaty, Novosibirsk');
		self::$_timezones['Asia/Rangoon'] = Yii::t('AdminModule.timezones', '(UTC+06:30) Yangon Rangoon');
		self::$_timezones['Asia/Bangkok'] = Yii::t('AdminModule.timezones', '(UTC+07:00) Bangkok, Hanoi, Jakarta');
		self::$_timezones['Asia/Krasnoyarsk'] = Yii::t('AdminModule.timezones', '(UTC+07:00) Krasnoyarsk');
		self::$_timezones['Asia/Hong_Kong'] = Yii::t('AdminModule.timezones', '(UTC+08:00) Beijing, Chongqing, Hong Kong SAR, Urumqi');
		self::$_timezones['Asia/Kuala_Lumpur'] = Yii::t('AdminModule.timezones', '(UTC+08:00) Kuala Lumpur, Singapore');
		self::$_timezones['Asia/Taipei'] = Yii::t('AdminModule.timezones', '(UTC+08:00) Taipei');
		self::$_timezones['Australia/Perth'] = Yii::t('AdminModule.timezones', '(UTC+08:00) Perth');
		self::$_timezones['Asia/Irkutsk'] = Yii::t('AdminModule.timezones', '(UTC+08:00) Irkutsk, Ulaanbaatar');
		self::$_timezones['Asia/Seoul'] = Yii::t('AdminModule.timezones', '(UTC+09:00) Seoul');
		self::$_timezones['Asia/Tokyo'] = Yii::t('AdminModule.timezones', '(UTC+09:00) Osaka, Sapporo, Tokyo');
		self::$_timezones['Asia/Irkutsk'] = Yii::t('AdminModule.timezones', '(UTC+09:00) Yakutsk');
		self::$_timezones['Australia/Darwin'] = Yii::t('AdminModule.timezones', '(UTC+09:30) Darwin');
		self::$_timezones['Australia/Adelaide'] = Yii::t('AdminModule.timezones', '(UTC+09:30) Adelaide');
		self::$_timezones['Australia/Canberra'] = Yii::t('AdminModule.timezones', '(UTC+10:00) Canberra, Melbourne, Sydney');
		self::$_timezones['Australia/Brisbane'] = Yii::t('AdminModule.timezones', '(UTC+10:00) Brisbane');
		self::$_timezones['Australia/Hobart'] = Yii::t('AdminModule.timezones', '(UTC+10:00) Hobart');
		self::$_timezones['Asia/Vladivostok'] = Yii::t('AdminModule.timezones', '(UTC+10:00) Vladivostok');
		self::$_timezones['Pacific/Guam'] = Yii::t('AdminModule.timezones', '(UTC+10:00) Guam, Port Moresby');
		self::$_timezones['Asia/Magadan'] = Yii::t('AdminModule.timezones', '(UTC+11:00) Magadan, Solomon Islands, New Caledonia');
		self::$_timezones['Asia/Kamchatka'] = Yii::t('AdminModule.timezones', '(UTC+12:00) Fiji Islands, Kamchatka, Marshall Islands');
		self::$_timezones['Pacific/Auckland'] = Yii::t('AdminModule.timezones', '(UTC+12:00) Auckland, Wellington');
		self::$_timezones['Pacific/Tongatapu'] = Yii::t('AdminModule.timezones', '(UTC+13:00) Nuku\'alofa');
		
		return self::$_timezones;
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