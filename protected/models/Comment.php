<?php
class Comment extends CActiveRecord
{
	public function tableName()
	{
		return '{{comment}}';
	}
	
	public function relations()
	{
		return array(
			'post' => array(self::BELONGS_TO, 'Post', 'post_id')
		);
	}
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function rules()
	{
		return array(
			array('name, mail, content, post_id', 'required'),
			array('name', 'length', 'max' => 20),
			array('mail', 'length', 'max' => 40),
			array('mail', 'email'),
			array('website', 'url'),
			array('content', 'length', 'max' => '600'),
			array('post_id', 'authenticatePost'),
			array('reply_id', 'authenticateReply')
		);
	}
	
	public function authenticatePost($attribute, $params)
	{
		if (!Post::model()->exists('id=:id', array(':id' => $this->post_id)))
			$this->addError($attribute, 'Post id not found.');
	}
	
	public function authenticateReply($attribute, $params)
	{
		$this->reply_id = (int) $this->reply_id;
		if (empty($this->reply_id))
			return;
		
		$isExists = self::model()->exists('id=:id AND post_id=:post_id', array(':id' => $this->reply_id, ':post_id' => $this->post_id));
		if (!$isExists)
			$this->addError($attribute, 'Reply id not found.');
	}
	
	protected function beforeSave()
	{
		if (empty($this->reply_id))
			$this->reply_id = new CDbExpression('NULL');
		if ($this->isNewRecord)
			$this->date = new CDbExpression('NOW()');
	
		return parent::beforeSave();
	}
	
	protected function afterSave()
	{
		$this->refresh();
	}
}