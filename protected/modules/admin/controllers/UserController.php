<?php
class UserController extends AdminController
{
	public function actionIndex()
	{
		$users = User::model()->findAll();
		
		$this->_menu = 'user';
		$this->pageTitle = Yii::t('AdminModule.user', 'Users');
		$this->render('index', array('users' => $users));
	}
	
	public function actionNew()
	{
		$this->_menu = 'new';
		$this->pageTitle = Yii::t('AdminModule.user', 'Add User');
		
		if (!Yii::app()->user->checkAccess('openCreateUser'))
			$this->_permissionDenied();
		
		$model = new User();
		$roles = User::getRoles();
		
		if (isset($_POST['User'])) {
			unset($_POST['User']['avatar']);
			$model->attributes = $_POST['User'];
			
			if (!Yii::app()->user->checkAccess('createUser', array('role' => $model->role)))
				$this->_permissionDenied();
			
			$avatar = CUploadedFile::getInstance($model, 'avatar');
			
			$hasAvatar = false;
			if ($avatar) {
				$model->avatar = $avatar->name;
				
				if ($model->validate()) {
					$model->avatar = User::processAvatar($avatar, $model);
					$hasAvatar = true;
				}
			}
			
			if (!isset($roles[$model->role]))
				$model->addError('role', 'Role was wrong!');
			
			if ($model->save()) {
				if ($hasAvatar) {
					$tempPath = Yii::getPathOfAlias('webroot.media.temp') . DIRECTORY_SEPARATOR;
					$avatarPath = Yii::getPathOfAlias('webroot.media.avatar') . DIRECTORY_SEPARATOR;
					
					rename($tempPath . $model->avatar, $avatarPath . $model->avatar);
				}
				
				Yii::app()->authManager->assign($model->role, $model->id);
				$this->redirect($this->createUrl('index'));
			}
		}
		
		$this->render('user', array('model' => $model, 'roles' => $roles));
	}
	
	public function actionEdit($id)
	{
		if ($id === Yii::app()->user->id) {
			$this->_menu = 'profile';
			$this->pageTitle = Yii::t('AdminModule.user', 'Profile');
		} else {
			$this->_menu = 'user';
			$this->pageTitle = Yii::t('AdminModule.user', 'Edit User');
		}
		
		$model = User::model()->findByPk($id);
		
		if (!$model)
			throw new CHttpException('404', 'This id was not found.');
		
		$oldRole = $model->role = key(Yii::app()->authManager->getAuthAssignments($model->id));
		if (!Yii::app()->user->checkAccess('editUser', array('userId' => $model->id, 'role' => $oldRole)))
			$this->_permissionDenied();
		
		$oldAvatar = $model->avatar;
		$model->password = null;
		
		if (isset($_POST['User'])) {
			unset($_POST['User']['avatar']);
			unset($_POST['User']['name']);
			unset($_POST['User']['role']);
			
			$model->attributes = $_POST['User'];
			$avatar = CUploadedFile::getInstance($model, 'avatar');
			
			$hasAvatar = false;
			if ($avatar) {
				$model->avatar = $avatar->name;
				
				if ($model->validate()) {
					$model->avatar = User::processAvatar($avatar, $model);
					$hasAvatar = true;
				}
			}
			
			if ($model->save()) {
				if ($hasAvatar) {
					$tempPath = Yii::getPathOfAlias('webroot.media.temp') . DIRECTORY_SEPARATOR;
					$avatarPath = Yii::getPathOfAlias('webroot.media.avatar') . DIRECTORY_SEPARATOR;
					
					rename($tempPath . $model->avatar, $avatarPath . $model->avatar);
					if (substr($model->avatar, 0, 8) !== 'default/' && is_file($avatarPath . $oldAvatar))
						unlink($avatarPath . $oldAvatar);
				}
				$this->redirect($this->createUrl('index'));
			} else
				$model->avatar = $oldAvatar;
		}

		$roles = User::getRoles(false);
		$this->render('user', array('model' => $model, 'roles' => $roles));
	}
	
	public function actionRemove($id)
	{
		$model = User::model()->findByPk($id);
		
		if ($model) {
			$role = key(Yii::app()->authManager->getAuthAssignments($model->id));
			
			if (!Yii::app()->user->checkAccess('removeUser', array('userId' => $model->id, 'role' => $role))) {
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array('error' => '401'));
					Yii::app()->end();
				} else {
					$this->_menu = 'user';
					$this->pageTitle = Yii::t('AdminModule.user', 'Delete User');
					$this->_permissionDenied();
				}
			}
			
			if (Post::model()->exists('author=:author', array(':author' => $id))) {
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array('error' => 'redirect', 'url' => $this->createUrl('removeChild', array('id' => $id))));
					Yii::app()->end();
				} else
					$this->redirect($this->createUrl('removeChild', array('id' => $id)));
			} else {
				$avatarPath = Yii::getPathOfAlias('webroot.media.avatar') . DIRECTORY_SEPARATOR;
				Yii::app()->authManager->revoke($role, $model->id);
				if (!empty($model->avatar) && substr($model->avatar, 0, 8) !== 'default/' && is_file($avatarPath . $model->avatar))
					unlink($avatarPath . $model->avatar);
				$model->delete();
			}
		}
		
		if (Yii::app()->request->isAjaxRequest) {
			echo json_encode(array('error' => '200'));
			Yii::app()->end();
		} else
			$this->redirect($this->createUrl('index'));
	}
	
	public function actionRemoveChild($id)
	{
		$this->_menu = 'user';
		$this->pageTitle = Yii::t('AdminModule.global', 'Delete Warning');
		
		$model = User::model()->findByPk($id);
		
		if ($model) {
			$role = key(Yii::app()->authManager->getAuthAssignments($model->id));
				
			if (!Yii::app()->user->checkAccess('removeUser', array('userId' => $model->id, 'role' => $role)))
				$this->_permissionDenied();
				
			if (Post::model()->exists('author=:author', array(':author' => $id))) {
				$userRemoveForm = new UserRemoveForm();
				$userRemoveForm->id = $id;
				
				if (isset($_POST['UserRemoveForm'])) {
						
					$userRemoveForm->attributes = $_POST['UserRemoveForm'];
					if ($userRemoveForm->validate()) {
						$avatarPath = Yii::getPathOfAlias('webroot.media.avatar') . DIRECTORY_SEPARATOR;
						Yii::app()->authManager->revoke($role, $model->id);
						if (!empty($model->avatar) && substr($model->avatar, 0, 8) !== 'default/' && is_file($avatarPath . $model->avatar))
							unlink($avatarPath . $model->avatar);
						$model->delete();
						$this->redirect($this->createUrl('index'));
					}
					if (in_array('401', $userRemoveForm->getErrors('type')))
						$this->_permissionDenied();
				}
				
				$this->render('user-remove', array(
						'user' => $model,
						'model' => $userRemoveForm
				));
				Yii::app()->end();
			}
		}
		
		$this->redirect($this->createUrl('index'));
	}
}