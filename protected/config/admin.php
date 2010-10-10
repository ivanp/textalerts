<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'name'=>'Admin System',

		// preloading 'log' component
		'preload'=>array('log'),

		// autoloading model and component classes
		'import'=>array(
			'application.models.*',
			'application.components.*',
		),

		'modules'=>array(
			'admin'=>array(),
			// uncomment the following to enable the Gii tool
			'gii'=>array(
				'class'=>'system.gii.GiiModule',
				'password'=>'kucingmiaw',
			),
		),

		// application components
		'components'=>array(
			'user'=>array(
				// enable cookie-based authentication
				'allowAutoLogin'=>true,
				'loginUrl' => array('user/login')
			),
			// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
				'showScriptName' => false, // remove index.php in URL
				'urlFormat'=>'path',
				'rules'=>array(
					'site/subscribe/<type:\w+>/<group_id:\d+>'=>'site/subscribe',
					'site/unsubscribe/<type:\w+>/<group_id:\d+>'=>'site/unsubscribe',
					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
			'errorHandler'=>array(
				// use 'site/error' action to display errors
							'errorAction'=>'site/error',
					),
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
					// uncomment the following to show log messages on web pages
					/*
					array(
						'class'=>'CWebLogRoute',
					),
					*/
				),
			),
		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
			'adminEmail'=>'ivan@primaguna.com',
			'mainHost'=>'admin.textalert.com'
		),

	)
);