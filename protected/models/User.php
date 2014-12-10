<?php
class User extends CActiveRecord
{
	const AVATAR_DEFAULT_PATH = 'default/<role>.png';
	
	public $passwordRepeat;
	public $role;
	
	public function tableName()
	{
		return '{{user}}';
	}
	
	public function relations()
	{
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author')
		);
	}
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('nickname', 'required'),
			array('name, password, passwordRepeat, role', 'required', 'on' => 'insert'),
			array('name', 'length', 'min' => 3, 'max' => 16),
			array('name', 'match', 'pattern' => '/^[0-9a-z_-]+$/i'),
			array('name', 'authenticateName'),
			array('role', 'authenticateRole'),
			array('nickname', 'length', 'min' => 3, 'max' => 40),
			array('password', 'length', 'min' => 6, 'max' => 20),
			array('password', 'match', 'pattern' => '/^\S+$/i'),
			array('passwordRepeat', 'compare', 'compareAttribute' => 'password'),
			array('mail', 'length', 'max' => 40),
			array('mail', 'email'),
			array('avatar', 'file', 'types' => 'png,jpg,jpeg,gif', 'allowEmpty' => true),
			array('description', 'length', 'max' => 255),
		);
	}
	
	public function authenticateName($attribute, $params)
	{
		if ($this->isNewRecord)
			$isExists = $this->exists('name=:name', array(':name' => $this->name));
		else
			$isExists = $this->exists('name=:name AND id<>:id', array(':name' => $this->name, ':id' => $this->id));
		if ($isExists)
			$this->addError($attribute, 'The name has already been token.');
	}
	
	public function authenticateRole($attribute, $params)
	{
		$roles = self::getRoles();
		if (!isset($roles[$this->role]))
			$this->addError($attribute, 'Role was wrong!');
	}
	
	public function attributeLabels()
	{
		return array(
				'role' => Yii::t('AdminModule.user', 'Role'),
				'name' => Yii::t('AdminModule.user', 'Username'),
				'nickname' => Yii::t('AdminModule.user', 'Nickname'),
				'password' => Yii::t('AdminModule.user', 'Password'),
				'passwordRepeat' => Yii::t('AdminModule.user', 'Confirm Password'),
				'mail' => Yii::t('AdminModule.user', 'E-mail'),
				'avatar' => Yii::t('AdminModule.user', 'Avatar'),
				'description' => Yii::t('AdminModule.user', 'Self Description'),
		);
	}
	
	protected function beforeSave()
	{
		if ($this->isNewRecord && empty($this->avatar))
			$this->avatar = str_replace('<role>', strtolower($this->role), self::AVATAR_DEFAULT_PATH);
		
		// if no encrypt
		if (strlen($this->password) !== 40) {
			if (empty($this->password))
				unset($this->password);
			else
				$this->password = self::encrypt($this->name, $this->password);
		}
		
		return parent::beforeSave();
	}
	
	public static function encrypt($name, $password)
	{
		return sha1($name . md5($password));
	}
	
	public static function getList()
	{
		$results = self::model()->findAll(array('select' => 'id, nickname'));
		$return = array();
		
		foreach ($results as $row)
			$return[$row['id']] = $row['nickname'];
		
		return $return;
	}
	
	public static function processAvatar(CUploadedFile $avatar, self $model)
	{
		Yii::import('application.vendor.*');
		require_once('Microwall/Image.php');
		
		$tempPath = Yii::getPathOfAlias('webroot.media.temp');
		$tempImg = $tempPath . DIRECTORY_SEPARATOR . uniqid() . '.' . $avatar->getExtensionName();
		
		$avatar->saveAs($tempImg);
		
		$image = new Microwall_Image($tempImg);
		
		// Resize image
		if ($image->resize(100, 100))
			$avatarName = $image->save($tempPath);
		else
			throw new Exception('Can not resize the image.');
		
		return $avatarName;
	}
	
	public static function getRoles($checkAccess = true)
	{
		$roles = Yii::app()->authManager->getAuthItems(CAuthItem::TYPE_ROLE);
		$return = array();
		foreach ($roles as $key => $role) {
			if ($checkAccess) {
				if (Yii::app()->user->checkAccess('createUser', array('role' => $key)))
					$return[$key] = Yii::t('AdminModule.user', $role->description);
			} else
				$return[$key] = Yii::t('AdminModule.user', $role->description);
		}
		
		return $return;
	}
}