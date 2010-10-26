<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(

		// application components
		'components'=>array(
			// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
				'rules'=>array(
					''=>'site/index',
					'site/subscribe/<type:\w+>/<group_id:\d+>'=>'site/subscribe',
					'site/unsubscribe/<type:\w+>/<group_id:\d+>'=>'site/unsubscribe',
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