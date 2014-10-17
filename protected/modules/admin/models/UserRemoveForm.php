<?php
class UserRemoveForm extends RemoveAlertForm
{
	public $columnName = 'author';
	public $moveToMsg = 'User not found.';
	
	public function getOtherList()
	{
		$users = User::getList();
		unset($users[$this->id]);
		return $users;
	}
}