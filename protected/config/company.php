<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(

		// application components
		'components'=>array(
			'session' => array(
				'cookieParams' => array('domain' => '.'.HOSTNAME)
			),
			'user'=>array(
				'class'=>'CompanyWebUser',
				'allowAutoLogin'=>true,
				'loginUrl' => '/user/login'
			),
			// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
				'rules'=>array(
					''=>'site/index',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
			'errorHandler'=>array(
				// use 'site/error' action to display errors
							'errorAction'=>'site/error',
					),
		),
	)
);