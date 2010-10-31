<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(

		// application components
		'components'=>array(
//			'authManager'=>array(
//	//			'class'=>'application.modules.srbac.components.SDbAuthManager',
//				'class'=>'CDbAuthManager',
//				'connectionID'=>'db',
//				'itemTable'=>'auth_item',
//				'itemChildTable'=>'auth_itemchild',
//				'assignmentTable'=>'auth_assignment',
//				'defaultRoles'=>array('Authenticated', 'Guest')
//			),
			
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

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
		),

		

	)
);