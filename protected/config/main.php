<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	require(dirname(__FILE__).'/db.php'), array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TextAlerts.com', // this thing must be the same across domains (cookie related)

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.models.forms.*',
		'application.vendors.*'
//		'application.modules.srbac.controllers.SBaseController',
//		'application.modules.srbac.*'
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
		'session' => array(
			'cookieParams' => array('.'.HOSTNAME_ADMIN)
		),
		'urlManager'=>array(
			'showScriptName' => false, // remove index.php in URL
			'urlFormat'=>'path',
		),
		// uncomment the following to use a MySQL database
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
//				array(
//					'class'=>'CWebLogRoute',
//				),
			),
		),
//		'swiftMailer' => array(
//			'class' => 'ext.swiftMailer.SwiftMailer',
//		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'ivan@primaguna.com',
		'domain'=>HOSTNAME_MAIN,
		'redirectHosts'=>array('www','web','admin','root','system','adm','w','ww',
				'ftp','mail','irc','gopher','smtp','pop3','pop','imap','system','w3',
				'administrator'),
	),

	'behaviors'=>array(
		'runEnd'=>array(
			'class'=>'application.components.WebApplicationEndBehavior',
		),
	),
));