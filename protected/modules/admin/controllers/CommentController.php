<?php
class CommentController extends AdminController
{
	private function _buildPages(CDbCriteria $criteria, $page)
	{
		$count = Comment::model()->count($criteria);
	
		$pages = new CPagination($count);
	
		if ($page < 0 || $page > $pages->pageCount)
			throw new CHttpException('404', 'This page was not found.');
	
		$pages->currentPage = $page;
		$pages->applyLimit($criteria);
	
		return $pages;
	}
	
	public function actionIndex($page = 0)
	{
		$page = (int) $page;
		
		$criteria = new CDbCriteria();
		$criteria->select = 'id, name, mail, website, content, date';
		$criteria->order = 't.id DESC';
		
		$pages = $this->_buildPages($criteria, $page);
		$comments = Comment::model()->with(array('post' => array('select' => 'title, slug')))->findAll($criteria);
		
		$this->_menu = 'comment';
		$this->pageTitle = Yii::t('AdminModule.comment', 'Comments');
		$this->render('index', array('pages' => $pages, 'comments' => $comments));
	}
	
	public function actionRemove($id)
	{
		if (!Yii::app()->user->checkAccess('removeComment')) {
			if (Yii::app()->request->isAjaxRequest) {
				echo json_encode(array('error' => '401'));
				Yii::app()->end();
			} else {
				$this->_menu = 'comment';
				$this->pageTitle = Yii::t('AdminModule.comment', 'Delete Comment');
				$this->_permissionDenied();
			}
		}
		$model = Comment::model()->findByPk($id);
		
		if ($model)
			$model->delete();
		
		if (Yii::app()->request->isAjaxRequest) {
			echo json_encode(array('error' => '200'));
			Yii::app()->end();
		} else
			$this->redirect($this->createUrl('index'));
	}
}