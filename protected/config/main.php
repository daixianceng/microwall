<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Microwall - The open source high-performance blog management system',
	'theme' => 'googleplus',
	'timeZone' => 'Asia/Hong_Kong',
	'language' => 'en_us',

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
	),

	'aliases' => array(
	//	'uploads' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads'
	),

	'modules' => array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
		
		'admin'
	),
	'controllerMap' => array(
		'ueditor' => array(
			'class' => 'ext.ueditor.UeditorController',
			'config' => array(
				'imagePathFormat'=>'/uploads/image/{yyyy}/{mm}/{time}{rand:6}',
				'scrawlPathFormat'=>'/uploads/image/{yyyy}/{mm}/{time}{rand:6}',
				'snapscreenPathFormat'=>'/uploads/image/{yyyy}/{mm}/{time}{rand:6}',
				'catcherPathFormat'=>'/uploads/image/{yyyy}/{mm}/{time}{rand:6}',
				'videoPathFormat'=>'/uploads/video/{yyyy}/{mm}/{time}{rand:6}',
				'filePathFormat'=>'/uploads/file/{yyyy}/{mm}/{rand:4}_{filename}',
				'imageManagerListPath'=>'/uploads/image/',
				'fileManagerListPath'=>'/uploads/file/',
				'retainOnlyLabelPasted' => true,
				'allowDivTransToP' => false,
			)
		),
	),

	// application components
	'components' => array(
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules'=>array(
				'category/<slug:[\w-]+>' => 'site/category',
				'post/<slug:[\w-]+>'=>'site/post',
			//	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		// uncomment the following to use a MySQL database
		
		'db' => array(
			'connectionString' => 'mysql:host=localhost;dbname=blog',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'mw_'
		),
		
		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		
		'messages' => array(
			'class' => 'CPhpMessageSource'
		),
		
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'itemTable' => '{{authitem}}',
			'itemChildTable' => '{{authitemchild}}',
			'assignmentTable' => '{{authassignment}}'
		),
		
		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		// this is used in contact page
		'keywords' => '',
		'description' => 'The open source high-performance blog management system. Developed and released by Microwall Studio',
		'cacheDuration' => 0
	),
);