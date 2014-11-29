<?php
class Post extends CActiveRecord
{
	const STATUS_PUBLISHED = '1001';
	const STATUS_ARCHIVED = '1010';
	const STATUS_RECYCLED = '1011';
	
	public function tableName()
	{
		return '{{post}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public function relations()
	{
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
			'author' => array(self::BELONGS_TO, 'User', 'author'),
			'category' => array(self::BELONGS_TO, 'Category', 'category'),
		);
	}
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 
	 * 
	 * @return integer
	 */
	public static function getCount($status)
	{
		return self::model()->count('status=:status', array(':status' => $status));
	}
	
	public function rules()
	{
		return array(
			array('title, slug, category, pic, description, content, status', 'required', 'on' => 'insert'),
			array('title, slug, category, description, content, status', 'required', 'on' => 'update'),
			array('title, category', 'required', 'on' => 'archive'),
			array('title', 'length', 'max' => 255),
			array('slug', 'length', 'max' => 80),
			array('slug', 'match', 'pattern' => '/^[\w-]+$/iu'),
			array('slug', 'authenticateSlug'),
			array('category', 'authenticateCategory'),
			array('pic', 'file', 'types' => 'png,jpg,jpeg,gif', 'allowEmpty' => true),
			array('description', 'length', 'min' => 10, 'max' => 255, 'on' => array('insert', 'update')),
			array('description', 'length', 'max' => 255, 'on' => 'archive'),
			array('content', 'length', 'max' => 10000),
			array('status', 'compare', 'compareValue' => self::STATUS_ARCHIVED, 'on' => 'archive'),
			array('status', 'compare', 'compareValue' => self::STATUS_PUBLISHED, 'on' => array('insert', 'update'))
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('AdminModule.post', 'Title'),
			'slug' => Yii::t('AdminModule.post', 'URL Index'),
			'category' => Yii::t('AdminModule.post', 'Category'),
			'pic' => Yii::t('AdminModule.post', 'Picture'),
			'description' => Yii::t('AdminModule.post', 'Description'),
			'content' => Yii::t('AdminModule.post', 'Content'),
		);
	}
	
	public function authenticateCategory($attribute, $params)
	{
		if (!Category::model()->exists('id=:category', array(':category' => $this->category)))
			$this->addError($attribute, 'Can not find the category.');
	}
	
	public function authenticateSlug($attribute, $params)
	{
		if (empty($this->slug))
			return;
		
		if ($this->isNewRecord)
			$isExists = $this->exists("slug=:slug", array(':slug' => $this->slug));
		else
			$isExists = $this->exists("slug=:slug AND id<>:id", array(':slug' => $this->slug, ':id' => $this->id));
		
		if ($isExists)
			$this->addError($attribute, "{$attribute} has already been taken.");
	}
	
	protected function beforeSave() {
		if ($this->isNewRecord) {
			$this->date_publish = new CDbExpression('NOW()');
			$this->author = Yii::app()->user->id;
		}
		if (empty($this->slug))
			$this->slug = new CDbExpression('NULL');
		
		if ($this->status === self::STATUS_ARCHIVED)
			$this->date_update = new CDbExpression('NOW()');
	
		return parent::beforeSave();
	}
	
	public static function processPic(CUploadedFile $pic, self $model)
	{
		Yii::import('application.vendor.*');
		require_once('Microwall/Image.php');
		
		$tempPath = Yii::getPathOfAlias('webroot.media.temp');
		$tempImg = $tempPath . DIRECTORY_SEPARATOR . uniqid() . '.' . $pic->getExtensionName();
		
		$pic->saveAs($tempImg);
		
		$image = new Microwall_Image($tempImg);
		
		$picName = '';
		// Resize image
		if ($image->resize(750, 400)) {
			$picName = $image->save($tempPath);
		} else {
			throw new Exception('Can not resize the image.');
		}
		if ($image->resize(220, 117)) {
			$image->save($tempPath, 'min_' . $picName);
		} else {
			throw new Exception('Can not resize the image.');
		}
		
		return $picName;
	}
}