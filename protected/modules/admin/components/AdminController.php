<?php
class AdminController extends CController
{
	public $layout = '/layouts/column1';
	
	protected $_menu;
	protected $_subTitle;
	
	public function init()
	{
		if (Yii::app()->user->isGuest) {
			$this->redirect($this->createUrl('access/login'));
		} else {
			
		}
	}
	
	public function getMenu()
	{
		return $this->_menu;
	}
	
	public function getSubTitle()
	{
		return $this->_subTitle;
	}
	
	protected function _permissionDenied()
	{
		$this->render('/layouts/_permission-denied');
		Yii::app()->end();
	}
}