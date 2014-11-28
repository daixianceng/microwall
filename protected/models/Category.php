<?php
class Category extends CActiveRecord
{
	public function tableName()
	{
		return '{{category}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public function relations()
	{
		return array(
			'post' => array(self::HAS_MANY, 'Post', 'category')
		);
	}
	
	public function rules()
	{
		return array(
			array('name, slug', 'required'),
			array('name, slug', 'length', 'max' => 20),
			array('slug', 'match', 'pattern' => '/^[\w-]+$/iu'),
			array('name, slug', 'authenticate'),
			array('comment', 'length', 'max' => 255),
		);
	}
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function authenticate($attribute, $params)
	{
		if ($this->isNewRecord)
			$isExists = $this->exists("{$attribute}=:attribute", array(':attribute' => $this->{$attribute}));
		else
			$isExists = $this->exists("{$attribute}=:attribute AND id<>:id", array(':attribute' => $this->{$attribute}, ':id' => $this->id));
		
		if ($isExists)
			$this->addError($attribute, "{$attribute} has already taken.");
	}
	
	/**
	 * Get categories list
	 * 
	 * @return array
	 */
	public static function getList()
	{
		$results = self::model()->findAll(array('select' => 'id, name', 'order' => 'sort, id'));
		$return = array();
		
		foreach ($results as $row)
			$return[$row['id']] = $row['name'];
		
		return $return;
	}
	
	protected function beforeSave()
	{
		if ($this->isNewRecord)
			$this->sort = Yii::app()->db->createCommand('SELECT MAX(sort)+1 FROM ' . $this->tableName())->queryScalar();
		
		return parent::beforeSave();
	}
}