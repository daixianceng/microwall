<?php
class PageController extends AdminController
{
	protected $_type;
	
	public function filters()
	{
		return array(
			'AjaxOnly + sort'
		);
	}
	
	public function actionIndex()
	{
		$this->layout = '/layouts/post-list';
		$this->_menu = 'pages';
		$this->pageTitle = Yii::t('AdminModule.page', 'Pages');
		
		$list = Page::model()->findAll(array(
			'condition' => 'status=:status',
			'order' => 'sort, id',
			'params' => array(':status' => Page::STATUS_PUBLISHED)
		));
		
		$this->render('index', array('list' => $list));
	}
	
	public function actionArchived()
	{
		$this->layout = '/layouts/post-list';
		$this->_menu = 'pages';
		$this->pageTitle = Yii::t('AdminModule.page', 'Pages');
		
		$list = Page::model()->findAll(array(
			'condition' => 'status=:status',
			'order' => 'date_update DESC',
			'params' => array(':status' => Page::STATUS_ARCHIVED)
		));
		
		$this->render('archived', array('list' => $list));
	}
	
	public function actionRecycled()
	{
		$this->layout = '/layouts/post-list';
		$this->_menu = 'pages';
		$this->pageTitle = Yii::t('AdminModule.page', 'Pages');
		
		$list = Page::model()->findAll(array(
			'condition' => 'status=:status',
			'order' => 'date_trash DESC',
			'params' => array(':status' => Page::STATUS_RECYCLED)
		));
		
		$this->render('recycled', array('list' => $list));
	}
	
	public function actionAdd($type = Page::TYPE_LOCAL)
	{
		if (!in_array($type, array(Page::TYPE_LOCAL, Page::TYPE_LINK)))
			$type = Page::TYPE_LOCAL;
		
		$this->layout = '/layouts/page';
		$this->_type = $type;
		$this->_menu = 'page';
		$this->pageTitle = Yii::t('AdminModule.page', 'Add Page');
		
		if (!Yii::app()->user->checkAccess('createPage'))
			$this->_permissionDenied();
		
		if ($type === Page::TYPE_LOCAL)
			// default insert
			$model = new Page();
		else
			// link
			$model = new Page($type);
		
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'Page') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if (isset($_POST['Page'])) {
			
			if ($type === Page::TYPE_LOCAL) {
				if (isset($_POST['Page']['status']) && $_POST['Page']['status'] === Page::STATUS_ARCHIVED)
					// archive
					$model->setScenario('archive');
			}
			
			$model->attributes = $_POST['Page'];
			$model->type = $type;
			
			if ($type === Page::TYPE_LINK)
				$model->status = Page::STATUS_PUBLISHED;
			
			
			if ($model->save()) {
				if ($model->status === Page::STATUS_PUBLISHED)
					$redirectUrl = $this->createUrl('index');
				else
					$redirectUrl = $this->createUrl('archived');
					
				Yii::app()->user->setFlash('success', Yii::t('AdminModule.page', 'The page published successfully!'));
				$this->redirect($redirectUrl);
			} else
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.page', 'Post failure!'));
		}
		
		$this->render('page-' . $type, array('model' => $model));
	}
	
	public function actionEdit($id)
	{
		$this->_menu = 'pages';
		$this->pageTitle = Yii::t('AdminModule.page', 'Edit Page');
		
		if (!Yii::app()->user->checkAccess('editPage'))
			$this->_permissionDenied();
		
		// default scenario to update
		$model = Page::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException('404', 'The page not found.');
		
		if ($model->type === Page::TYPE_LINK)
			// link
			$model->setScenario('link');
		
		if (isset($_POST['Page'])) {
			if ($model->type === Page::TYPE_LOCAL) {
				if (isset($_POST['Page']['status']) && $_POST['Page']['status'] === Page::STATUS_ARCHIVED)
					// archive
					$model->setScenario('archive');
			}
			
			$model->attributes = $_POST['Page'];
			
			if ($model->type === Page::TYPE_LINK)
				$model->status = Page::STATUS_PUBLISHED;
			
			if ($model->save()) {
				if ($model->status === Page::STATUS_PUBLISHED)
					$redirectUrl = $this->createUrl('index');
				else
					$redirectUrl = $this->createUrl('archived');
				
				Yii::app()->user->setFlash('success', Yii::t('AdminModule.page', 'The page updated successfully!'));
				$this->refresh();
			} else
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.page', 'The page update failed!'));
		}
		
		$this->layout = '/layouts/page';
		$this->_type = $model->type;
		$this->render('page-' . $model->type, array('model' => $model));
	}
	
	public function actionRecycle($id)
	{
		if (!Yii::app()->user->checkAccess('recyclePage')) {
			$this->_menu = 'pages';
			$this->pageTitle = Yii::t('AdminModule.page', 'Move to Trash');
			$this->_permissionDenied();
		}
		
		$model = Page::model()->findByPk($id);
		
		if ($model) {
			$model->status = Page::STATUS_RECYCLED;
			if (!$model->save(false)) {
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.page', 'The page move to the trash failed!'));
				$this->redirect(array('recycled'));
			}
		}
		
		Yii::app()->user->setFlash('success', Yii::t('AdminModule.page', 'The page successfully moved to the trash!'));
		$this->redirect(array('recycled'));
	}
	
	/**
	 * 永久删除
	 * 
	 * @param integer $id
	 */
	public function actionRemove($id)
	{
		if (!Yii::app()->user->checkAccess('removePage')) {
			if (Yii::app()->request->isAjaxRequest) {
				echo json_encode(array('error' => '401'));
				Yii::app()->end();
			} else {
				$this->_menu = 'pages';
				$this->pageTitle = Yii::t('AdminModule.page', 'Delete Page');
				$this->_permissionDenied();
			}
		}
		
		$model = Page::model()->findByPk($id);
		
		if ($model) {
			if (!$model->delete()) {
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array('error' => '417'));
					Yii::app()->end();
				} else {
					Yii::app()->user->setFlash('error', Yii::t('AdminModule.page', 'The page delete failed!'));
					$this->redirect(array('recycled'));
				}
			}
		}
		
		if (Yii::app()->request->isAjaxRequest)
			echo json_encode(array('error' => '200'));
		else {
			Yii::app()->user->setFlash('success', Yii::t('AdminModule.page', 'The page deleted successfully!'));
			$this->redirect(array('recycled'));
		}
	}
	
	public function actionSort($list)
	{
		$list = explode(',', $list);
		
		if (empty($list) || !Yii::app()->user->checkAccess('editPage'))
			Yii::app()->end();
		
		$sql = '';
		foreach($list as $key => $id) {
			$id = (int) $id;
			$sql .= "UPDATE {{page}} SET sort='{$key}' WHERE id='{$id}';";
		}
		
		Yii::app()->db->createCommand($sql)->execute();
	}
	
	public function getType()
	{
		return $this->_type;
	}
}