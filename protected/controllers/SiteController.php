<?php

class SiteController extends Controller
{
	public $currCategory;
	public $posts;
	public $popular;
	public $model;
	
	protected function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			$this->menu = Category::model()->findAll(array('select' => 'id, name, slug', 'order' => 'sort, id'));
			$this->model = new Comment();
			return true;
		}
		
		return false;
	}
	
	public function filters()
	{
		return array(
			'AjaxOnly + postAjax, ajax, comments, comment',
			array(
				'COutputCache + index, category, post, ajax, postAjax',
				'duration' => 7200,
				'varyByParam' => array('slug', 'category', 'start', 'id'),
			),
		);
	}
	
	protected function _getPopular($category = null)
	{
		$criteria = new CDbCriteria(array(
			'select' => 'id, title, slug, pic',
			'order' => 'views DESC, date_publish DESC',
			'limit' => 8
		));
		$condition = array('status' => Post::STATUS_PUBLISHED);
		if ($category !== null)
			$condition['category'] = $category;
		
		$criteria->addColumnCondition($condition);
		
		return Post::model()->findAll($criteria);
	}
	
	protected function _getPosts($category = null, $start = 0, $limit = 10)
	{
		$criteria = new CDbCriteria(array(
			'select' => 'id, title, slug, description, date_publish, pic',
			'order' => 'date_publish DESC',
			'limit' => $limit,
			'offset' => $start
		));
		$condition = array('t.status' => Post::STATUS_PUBLISHED);
		if ($category !== null)
			$condition['t.category'] = $category;
		
		$criteria->addColumnCondition($condition);
		
		return Post::model()->with('author', 'category')->findAll($criteria);
	}
	
	public function actionAjax($category, $start)
	{
		$category = (int) $category;
		$start = (int) $start;
		
		if (!$category)
			$category = null;
		
		$this->posts = $this->_getPosts($category, $start);
		$html = $this->renderPartial('index', null, true);
		
		echo json_encode(array('count' => count($this->posts), 'html' => $html));
	}
	
	public function actionIndex()
	{
		$this->pageTitle = '';
		$this->currCategory = 'all';
		$this->posts = $this->_getPosts();
		$this->popular = $this->_getPopular();
		
		$this->render('index');
	}
	
	public function actionCategory($slug)
	{
		$category = Category::model()->find('slug=:slug', array(':slug' => $slug));
		
		if (!$category) {
			throw new CHttpException('404', 'Category not found.');
		}
		
		$this->pageTitle = $category->name;
		$this->currCategory = $category->id;
		$this->posts = $this->_getPosts($category->id);
		$this->popular = $this->_getPopular($category->id);
		
		$this->render('index');
	}
	
	public function actionPost($slug)
	{
		$post = Post::model()->find('slug=:slug AND status=:status', array(':slug' => $slug, ':status' => Post::STATUS_PUBLISHED));
		
		if (!$post) {
			throw new CHttpException('404', 'Article not found.');
		}
		
		$category = Category::model()->findByPk($post->category);
		
		$this->pageTitle = $post->title;
		$this->currCategory = $category->id;
		$this->posts = $this->_getPosts($category->id);
		$this->popular = $this->_getPopular($category->id);
		
		$this->render('post', array('post' => $post));
	}
	
	public function actionPostAjax($id)
	{
		$post = Post::model()->with('category', 'author')->find('t.id=:id AND t.status=:status', array(':id' => $id, ':status' => Post::STATUS_PUBLISHED));
		
		$data = array('error' => '200');
		if (!$post) {
			$data['error'] = '403';
			echo json_encode($data);
			Yii::app()->end();
		}
		
		$data['title'] = htmlspecialchars($post->title);
		$data['pic'] = $post->pic;
		$data['category'] = htmlspecialchars($post->getRelated('category')->name);
		$data['content'] = $post->content;
		$data['date_publish'] = date('M d, Y', strtotime($post->date_publish));
		$data['views'] = $post->views;
		$data['author'] = htmlspecialchars($post->getRelated('author')->nickname);
		
		Yii::app()->db->createCommand()->update($post->tableName(), array('views' => new CDbExpression('views + 1')), 'id=:id', array(':id' => $id));
		echo json_encode($data);
	}
	
	public function actionComment()
	{
		$model = new Comment();
		$level = (int) Yii::app()->request->getPost('level');
		if (isset($_POST['Comment'])) {
			$model->attributes = $_POST['Comment'];
			if ($model->save()) {
				
				$visitor = array(
					'n' => $model->name,
					'm' => $model->mail,
					'w' => $model->website
				);
				Yii::app()->request->cookies['visitor'] = new CHttpCookie('visitor', serialize($visitor), array('expire' => time() + 604800));
				
				$html = $this->renderPartial('_comments', array('comments' => array($model), 'level' => $level), true);
				echo json_encode(array('error' => '200', 'html' => $html));
				Yii::app()->end();
			}
		}
		echo json_encode(array('error' => '412'));
	}
	
	public function actionComments()
	{
		$postId = (int) Yii::app()->request->getPost('postId');
		
		$comments = Comment::model()->findAll('post_id=:postId AND reply_id IS NULL', array(':postId' => $postId));
		$this->renderPartial('_comments', array('comments' => $comments, 'level' => 0));
	}
	
	public function actionSearch($w)
	{
		/*
		 * Sphinx Search Engine
		 * 
		Yii::import('application.vendor.*');
		require_once 'Sphinx/sphinxapi.php';
		
		$cl = new SphinxClient();
		$cl->SetServer('127.0.0.1', 9312);
		$cl->SetConnectTimeout(3);
		$cl->SetArrayResult(true);
		$cl->SetMatchMode(SPH_MATCH_ANY);
		$res = $cl->Query($w, "*");
		print_r($cl);
		print_r($res);
		*/
		$url = Yii::app()->request->serverName . ' ' . $w;
		$url = urlencode($url);
		$url = 'http://cn.bing.com/search?q=site:' . $url;
		header('Location: ' . $url);
	}
	
	public function actionPage($slug)
	{
		
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->pageTitle = '404 页面不存在';
		if($error = Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error', $error);
		}
	}
}