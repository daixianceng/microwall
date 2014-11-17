<?php
class PostController extends AdminController
{
	public function filters()
	{
		return array(
			'AjaxOnly + categorySort'
		);
	}
	
	private function _buildPages(CDbCriteria $criteria, $page)
	{
		$count = Post::model()->count($criteria);
		
		$pages = new CPagination($count);
		
		if ($page < 0 || $page > $pages->pageCount)
			throw new CHttpException('404', 'This page was not found.');
		
		$pages->currentPage = $page;
		$pages->applyLimit($criteria);
		
		return $pages;
	}
	
	public function actionIndex($category = 'all', $page = 0)
	{
		$this->layout = '/layouts/post-list';
		
		if ($category !== 'all') 
			$category = (int) $category;
		$page = (int) $page;
		
		$criteria = new CDbCriteria();
		
		if (is_int($category) && Category::model()->exists('id=:id', array(':id' => $category)))
			$criteria->addColumnCondition(array('category' => $category));
		
		$criteria->addColumnCondition(array('status' => Post::STATUS_PUBLISHED));
		$criteria->select = 'id, title, slug, date_publish, author';
		$criteria->order = 't.id DESC';
		
		$pages = $this->_buildPages($criteria, $page);
		$posts = Post::model()->with(array('author' => array('select' => 'id, nickname')))->findAll($criteria);
		
		$this->_menu = 'post';
		$this->pageTitle = Yii::t('AdminModule.post', 'Articles');
		$this->render('index', array(
				'posts' => $posts,
				'pages' => $pages,
				'categories' => Category::getList(),
				'currentCategory' => $category
		));
	}
	
	public function actionArchived($category = 'all', $page = 0)
	{
		$this->layout = '/layouts/post-list';
		
		if ($category !== 'all')
			$category = (int) $category;
		$page = (int) $page;
		
		$criteria = new CDbCriteria();
		
		if (is_int($category) && Category::model()->exists('id=:id', array(':id' => $category)))
			$criteria->addColumnCondition(array('category' => $category));
		
		$criteria->addColumnCondition(array('status' => Post::STATUS_ARCHIVED));
		$criteria->select = 'id, title, date_update, author';
		$criteria->order = 't.date_update DESC';
		
		$pages = $this->_buildPages($criteria, $page);
		$posts = Post::model()->with(array('author' => array('select' => 'id, nickname')))->findAll($criteria);
		
		$this->_menu = 'post';
		$this->pageTitle = Yii::t('AdminModule.post', 'Articles');;
		$this->render('archived', array(
				'posts' => $posts, 
				'pages' => $pages, 
				'categories' => Category::getList(), 
				'currentCategory' => $category
		));
	}
	
	public function actionRecycled($category = 'all', $page = 0)
	{
		$this->layout = '/layouts/post-list';
		
		if ($category !== 'all')
			$category = (int) $category;
		$page = (int) $page;
		
		$criteria = new CDbCriteria();
		
		if (is_int($category) && Category::model()->exists('id=:id', array(':id' => $category)))
			$criteria->addColumnCondition(array('category' => $category));
		
		$criteria->addColumnCondition(array('status' => Post::STATUS_RECYCLED));
		$criteria->select = 'id, title, date_trash, author';
		$criteria->order = 't.date_trash DESC';
		
		$pages = $this->_buildPages($criteria, $page);
		$posts = Post::model()->with(array('author' => array('select' => 'id, nickname')))->findAll($criteria);
		
		$this->_menu = 'post';
		$this->pageTitle = Yii::t('AdminModule.post', 'Articles');;
		$this->render('recycled', array(
				'posts' => $posts, 
				'pages' => $pages, 
				'categories' => Category::getList(), 
				'currentCategory' => $category
		));
	}
	
	/**
	 * Write article
	 */
	public function actionWriting()
	{
		$this->_menu = 'writing';
		$this->pageTitle = Yii::t('AdminModule.post', 'Writing');
		
		if (!Yii::app()->user->checkAccess('createPost'))
			$this->_permissionDenied();
		
		// default insert
		$model = new Post();
		
		if (isset($_POST['Post'])) {
			unset($_POST['Post']['pic']);
			
			if (isset($_POST['Post']['status']) && $_POST['Post']['status'] === Post::STATUS_ARCHIVED)
				// archive
				$model->setScenario('archive');
			
			$model->attributes = $_POST['Post'];
			$pic = CUploadedFile::getInstance($model, 'pic');
			
			$hasPic = false;
			// if uploaded image
			if ($pic) {
				$model->pic = $pic->name;
				// auth the image type
				if ($model->validate()) {
					$model->pic = Post::processPic($pic, $model);
					$hasPic = true;
				}
			}
			
			if ($model->save()) {
				if ($hasPic) {
					$tempPath = Yii::getPathOfAlias('webroot.media.temp') . DIRECTORY_SEPARATOR;
					$picPath = Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR;
			
					rename($tempPath . $model->pic, $picPath . $model->pic);
					rename($tempPath . 'min_' . $model->pic, $picPath . 'min_' . $model->pic);
				}

				if ($model->status === Post::STATUS_PUBLISHED) {
					Yii::app()->user->setFlash('success', Yii::t('AdminModule.post', 'Published article successfully!'));
					$redirectUrl = $this->createUrl('index');
				} else {
					Yii::app()->user->setFlash('success', Yii::t('AdminModule.post', 'The article successfully deposited in the draft!'));
					$redirectUrl = $this->createUrl('archived');
				}
					
				$this->redirect($redirectUrl);
			} else
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.post', 'Post failure!'));
		}
		
		$this->render('writing', array('model' => $model, 'categories' => Category::getList()));
	}
	
	/**
	 * Edit article
	 * 
	 * @param integer $id
	 * @throws CHttpException
	 */
	public function actionEdit($id)
	{
		$this->_menu = 'writing';
		$this->pageTitle = Yii::t('AdminModule.post', 'Edit Article');
		
		// default scenario to update
		$model = Post::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException(404, 'The post id can not be find.');
		if (!Yii::app()->user->checkAccess('editPost', array('userId' => $model->author)))
			$this->_permissionDenied();
		
		$oldPicName = $model->pic;
		if (isset($_POST['Post'])) {
			unset($_POST['Post']['pic']);
			
			if (isset($_POST['Post']['status']) && $_POST['Post']['status'] === Post::STATUS_ARCHIVED)
				// archive
				$model->setScenario('archive');
			
			$model->attributes = $_POST['Post'];
			$pic = CUploadedFile::getInstance($model, 'pic');
			
			$hasPic = false;
			if ($pic) {
				$model->pic = $pic->name;
				if ($model->validate()) {
					$model->pic = Post::processPic($pic, $model);
					$hasPic = true;
				}
			}
				
			if ($model->save()) {
				if ($hasPic) {
					$tempPath = Yii::getPathOfAlias('webroot.media.temp') . DIRECTORY_SEPARATOR;
					$picPath = Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR;
			
					rename($tempPath . $model->pic, $picPath . $model->pic);
					rename($tempPath . 'min_' . $model->pic, $picPath . 'min_' . $model->pic);
			
					if (is_file($picPath . $oldPicName))
						unlink($picPath . $oldPicName);
					if (is_file($picPath . 'min_' . $oldPicName))
						unlink($picPath . 'min_' . $oldPicName);
				}
				
				Yii::app()->user->setFlash('success', Yii::t('AdminModule.post', 'The article updated successfully!'));
				$this->refresh();
			} else {
				$model->pic = $oldPicName;
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.post', 'The article update failed!'));
			}
		}
		
		$this->render('writing', array('model' => $model, 'categories' => Category::getList()));
	}
	
	/**
	 * 移至回收站
	 * 
	 * @param integer $id
	 */
	public function actionRecycle($id)
	{
		$model = Post::model()->findByPk($id);
		
		if ($model) {
			
			if (!Yii::app()->user->checkAccess('recyclePost', array('userId' => $model->author))) {
				$this->_menu = 'post';
				$this->pageTitle = Yii::t('AdminModule.post', 'Move to Trash');
				$this->_permissionDenied();
			}
			
			$model->status = Post::STATUS_RECYCLED;
			$model->date_trash = new CDbExpression('NOW()');
			if ($model->save(false))
				Yii::app()->user->setFlash('success', Yii::t('AdminModule.post', 'The article successfully moved to the trash!'));
			else
				Yii::app()->user->setFlash('error', Yii::t('AdminModule.post', 'The article move to the trash failed!'));
		}
		
		$this->redirect($this->createUrl('recycled'));
	}
	
	/**
	 * 永久删除
	 * 
	 * @param integer $id
	 */
	public function actionRemove($id)
	{
		$model = Post::model()->findByPk($id);
		
		if ($model) {
			
			if (!Yii::app()->user->checkAccess('removePost', array('userId' => $model->author))) {
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array('error' => '401'));
					Yii::app()->end();
				} else {
					$this->_menu = 'post';
					$this->pageTitle = Yii::t('AdminModule.post', 'Delete Article');
					$this->_permissionDenied();
				}
			}
			
			if (!empty($model->pic) && is_file(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . $model->pic)) {
				unlink(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . $model->pic);
				unlink(Yii::getPathOfAlias('webroot.media.pic') . DIRECTORY_SEPARATOR . 'min_' . $model->pic);
			}
			$model->delete();
		}
		
		if (Yii::app()->request->isAjaxRequest) {
			echo json_encode(array('error' => '200'));
			Yii::app()->end();
		} else
			$this->redirect($this->createUrl('index'));
	}
	
	public function actionCategories()
	{
		$this->_menu = 'categories';
		$this->pageTitle = Yii::t('AdminModule.post', 'Categories');
		
		$categories = Category::model()->findAll(array('order' => 'sort, id'));
		$this->render('categories', array('categories' => $categories));
	}
	
	public function actionCategoryAdd()
	{
		$this->_menu = 'categories';
		$this->pageTitle = Yii::t('AdminModule.post', 'Add Category');
		
		if (!Yii::app()->user->checkAccess('createCategory'))
			$this->_permissionDenied();
		
		$model = new Category();
		
		if (isset($_POST['Category'])) {
			$model->attributes = $_POST['Category'];
			if ($model->save()) {
				$this->redirect($this->createUrl('categories'));
			}
		}
		
		$this->render('category', array('model' => $model));
	}
	
	public function actionCategoryEdit($id)
	{
		$this->_menu = 'categories';
		$this->pageTitle = Yii::t('AdminModule.post', 'Edit Category');
		
		if (!Yii::app()->user->checkAccess('editCategory'))
			$this->_permissionDenied();
		
		$model = Category::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException(404, 'The category id can not be find.');
		
		if (isset($_POST['Category'])) {
			$model->setScenario('update');
			$model->attributes = $_POST['Category'];
			if ($model->save()) {
				$this->redirect($this->createUrl('categories'));
			}
		}
		
		$this->render('category', array('model' => $model));
	}
	
	public function actionCategoryRemove($id)
	{
		if (!Yii::app()->user->checkAccess('removeCategory')) {
			if (Yii::app()->request->isAjaxRequest) {
				echo json_encode(array('error' => '401'));
				Yii::app()->end();
			} else {
				$this->_menu = 'categories';
				$this->pageTitle = Yii::t('AdminModule.post', 'Delete Category');
				$this->_permissionDenied();
			}
		}
		
		$model = Category::model()->findByPk($id);
		
		if ($model) {
			if (Post::model()->exists('category=:category', array(':category' => $model->id))) {
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array('error' => 'redirect', 'url' => $this->createUrl('categoryRemoveChild', array('id' => $id))));
					Yii::app()->end();
				} else
					$this->redirect($this->createUrl('categoryRemoveChild', array('id' => $id)));
			} else
				$model->delete();
		}
		
		if (Yii::app()->request->isAjaxRequest) {
			echo json_encode(array('error' => '200'));
			Yii::app()->end();
		} else
			$this->redirect($this->createUrl('categories'));
	}
	
	public function actionCategoryRemoveChild($id)
	{
		$this->_menu = 'categories';
		$this->pageTitle = Yii::t('AdminModule.global', 'Delete Warning');
		
		if (!Yii::app()->user->checkAccess('removeCategory'))
			$this->_permissionDenied();
		
		$model = Category::model()->findByPk($id);
		
		if ($model) {
			if (Post::model()->exists('category=:category', array(':category' => $model->id))) {
				
				$categoryRemoveForm = new CategoryRemoveForm();
				$categoryRemoveForm->id = $id;
				
				if (isset($_POST['CategoryRemoveForm'])) {
					
					$categoryRemoveForm->attributes = $_POST['CategoryRemoveForm'];
					if ($categoryRemoveForm->validate()) {
						$model->delete();
						$this->redirect($this->createUrl('categories'));
					}
					if (in_array('401', $categoryRemoveForm->getErrors('type')))
						$this->_permissionDenied();
				}
				
				$this->render('category-remove', array(
					'category' => $model,
					'model' => $categoryRemoveForm
				));
				Yii::app()->end();
			}
		}
		
		$this->redirect($this->createUrl('categories'));
	}
	
	public function actionCategorySort($list)
	{
		$list = explode(',', $list);
		
		if (empty($list) || !Yii::app()->user->checkAccess('editCategory'))
			Yii::app()->end();
		
		$sql = '';
		foreach($list as $key => $id) {
			$id = (int) $id;
			$sql .= "UPDATE {{category}} SET sort='{$key}' WHERE id='{$id}';";
		}
		
		Yii::app()->db->createCommand($sql)->execute();
	}
}