<?php
class Page extends CActiveRecord
{
	const STATUS_PUBLISHED = '1001';
	const STATUS_ARCHIVED = '1010';
	const STATUS_RECYCLED = '1011';
	const TYPE_LOCAL = 'local';
	const TYPE_LINK = 'link';
	
	public function tableName()
	{
		return '{{page}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('title', 'required'),
			array('slug', 'required', 'on' => array('insert', 'update')),
			array('slug', 'authenticate', 'on' => array('insert', 'archive', 'update')),
			array('content', 'length', 'max' => 10000, 'on' => array('insert', 'archive', 'update')),
			array('content', 'url', 'allowEmpty' => false, 'on' => 'link'),
			array('status', 'compare', 'compareValue' => self::STATUS_ARCHIVED, 'on' => 'archive'),
			array('status', 'compare', 'compareValue' => self::STATUS_PUBLISHED, 'on' => array('insert', 'update'))
		);
	}
	
	protected function beforeSave()
	{
		if ($this->type === self::TYPE_LINK || empty($this->slug))
			$this->slug = new CDbExpression('NULL');
		
		if ($this->isNewRecord)
			$this->sort = Yii::app()->db->createCommand('SELECT MAX(sort)+1 FROM ' . $this->tableName())->queryScalar();
		
		if ($this->status === self::STATUS_RECYCLED)
			$this->date_trash = new CDbExpression('NOW()');
		
		if ($this->status === self::STATUS_ARCHIVED)
			$this->date_update = new CDbExpression('NOW()');
		
		return parent::beforeSave();
	}
	
	public function authenticate($attribute, $params)
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
}