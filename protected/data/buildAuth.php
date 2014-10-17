<?php
function buildAuth()
{
	$auth = Yii::app()->authManager;
	$auth->clearAll();
	
	$auth->createOperation('createPost', '写文章');
	$auth->createOperation('editPost','编辑文章');
	$auth->createOperation('recyclePost','把文章放入回收站');
	$auth->createOperation('removePost','彻底删除文章');
	$auth->createOperation('createCategory','添加分类');
	$auth->createOperation('editCategory','编辑分类');
	$auth->createOperation('removeCategory','移除分类');
	$auth->createOperation('openCreateUser', '打开添加用户页面');
	$auth->createOperation('createUser','添加用户');
	$auth->createOperation('editUser','编辑用户');
	$auth->createOperation('removeUser','删除用户', 'return Yii::app()->user->id !== $params[\'userId\'];');
	$auth->createOperation('setWebsite','设置网站');
	$auth->createOperation('removeComment', '移除评论');
	$auth->createOperation('createPage', '添加页面');
	$auth->createOperation('editPage', '编辑页面');
	$auth->createOperation('recyclePage', '把页面放入回收站');
	$auth->createOperation('removePage', '移除页面');
	
	$bizRule = 'return $params[\'role\'] === \'Author\';';
	$task = $auth->createTask('createAuthorUser', '添加作者角色用户', $bizRule);
	$task->addChild('createUser');
	
	$task = $auth->createTask('editAuthorUser', '编辑作者角色用户', $bizRule);
	$task->addChild('editUser');
	
	$task = $auth->createTask('removeAuthorUser', '删除作者角色用户', $bizRule);
	$task->addChild('removeUser');
	
	$bizRule = 'return Yii::app()->user->id === $params[\'userId\'];';
	$task = $auth->createTask('editOwnUser', '编辑自己的资料', $bizRule);
	$task->addChild('editUser');
	
	$task = $auth->createTask('editOwnPost', '编辑自己的文章', $bizRule);
	$task->addChild('editPost');
	
	$task = $auth->createTask('recycleOwnPost', '把自己的文章放入回收站', $bizRule);
	$task->addChild('recyclePost');
	
	$task = $auth->createTask('removeOwnPost', '彻底删除自己的文章', $bizRule);
	$task->addChild('removePost');
	
	
	$role = $auth->createRole('Administrator', 'Administrator');
	$role->addChild('createPost');		// 创建文章
	$role->addChild('editPost');		// 编辑文章
	$role->addChild('recyclePost');		// 将文章放入回收站
	$role->addChild('removePost');		// 彻底删除文章
	$role->addChild('createCategory');	// 创建分类
	$role->addChild('editCategory');	// 编辑分类
	$role->addChild('removeCategory');	// 移除分类
	$role->addChild('createUser');		// 创建用户
	$role->addChild('editUser');		// 编辑用户
	$role->addChild('removeUser');		// 移除用户
	$role->addChild('setWebsite');		// 设置网站
	$role->addChild('openCreateUser');	// 打开创建用户页面
	$role->addChild('removeComment');	// 移除评论
	$role->addChild('createPage');
	$role->addChild('editPage');
	$role->addChild('recyclePage');
	$role->addChild('removePage');
	
	$role = $auth->createRole('Editor', 'Editor');
	$role->addChild('createPost');
	$role->addChild('editPost');
	$role->addChild('recyclePost');
	$role->addChild('removePost');
	$role->addChild('createCategory');
	$role->addChild('editCategory');
	$role->addChild('removeCategory');
	$role->addChild('createAuthorUser');// 创建作者角色用户
	$role->addChild('editAuthorUser');	// 编辑作者角色用户
	$role->addChild('removeAuthorUser');// 移除作者角色用户
	$role->addChild('editOwnUser');		// 编辑自己的资料
	$role->addChild('openCreateUser');
	$role->addChild('removeComment');
	$role->addChild('createPage');
	$role->addChild('editPage');
	$role->addChild('recyclePage');
	$role->addChild('removePage');
	
	$role = $auth->createRole('Author', 'Author');
	$role->addChild('createPost');
	$role->addChild('editOwnPost');		// 编辑自己的文章
	$role->addChild('recycleOwnPost');	// 将自己的文章放入回收站
	$role->addChild('removeOwnPost');	// 彻底删除自己的文章
	$role->addChild('editOwnUser');		// 编辑自己的资料
	
	$auth->assign('Administrator', 1);
}