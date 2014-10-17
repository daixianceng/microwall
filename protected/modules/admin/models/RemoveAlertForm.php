<?php
class RemoveAlertForm extends CFormModel
{
	const TYPE_MOVE = 'move';
	const TYPE_DELETE = 'delete';
	
	public $id;
	public $type;
	public $moveTo;
	public $columnName;
	public $moveToMsg;
	
	public final function rules()
	{
		return array(
				array('type', 'required'),
				array('type', 'in', 'range' => array(self::TYPE_MOVE, self::TYPE_DELETE)),
				array('moveTo', 'authenticate')
		);
	}
	
	public final function authenticate($attribute, $params)
	{
		if ($this->type === self::TYPE_MOVE) {
				
			if (!Yii::app()->user->checkAccess('editPost')) {
				$this->addError('type', '401');
				return;
			}
				
			$list = $this->getOtherList();
			if (isset($this->moveTo) && isset($list[$this->moveTo]))
				Yii::app()->db->createCommand("UPDATE {{post}} SET {$this->columnName}={$this->moveTo} WHERE {$this->columnName}={$this->id}")->execute();
			else
				$this->addError($attribute, $this->moveToMsg);
		} else {
				
			if (!Yii::app()->user->checkAccess('removePost')) {
				$this->addError('type', '401');
				return;
			}
				
			$picArr = Yii::app()->db->createCommand()
									->select('pic')
									->from('{{post}}')
									->where("{$this->columnName}=:column", array(':column' => $this->id))
									->queryColumn();
			foreach ($picArr as $picName) {
				if (!empty($picName) && is_file(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . $picName)) {
					unlink(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . $picName);
					unlink(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . 'min_' . $picName);
				}
			}
				
			Yii::app()->db->createCommand()->delete('{{post}}', "{$this->columnName}=:column", array(':column' => $this->id));
		}
	}
	
	public function getOtherList()
	{
		return array();
	}
}