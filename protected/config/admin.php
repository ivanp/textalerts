<?php
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(

		// application components
		'components'=>array(
			'user'=>array(
				'class'=>'AdminWebUser',
//				'allowAutoLogin'=>true,
				'loginUrl' => '/user/login'
			),
			'authManager'=>array(
	//			'class'=>'application.modules.srbac.components.SDbAuthManager',
				'class'=>'CDbAuthManager',
				'connectionID'=>'db',
				'itemTable'=>'auth_item',
				'itemChildTable'=>'auth_itemchild',
				'assignmentTable'=>'auth_assignment',
//				'defaultRoles'=>array('Authenticated', 'Guest')
			),
			'urlManager'=>array(
				'rules'=>array(
					''=>'site/index',
					'<controller:\w+>'=>'<controller>/index',
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
		),
		'modules'=>array(
			'srbac'=>array(
				'class'=>'application.modules.srbac.SrbacModule',
				'userclass'=>'SuperAdmin', //default: User
				'userid'=>'id', //default: userid
				'username'=>'username', //default:username
				'debug'=>true, //default :false
				'pageSize'=>10, // default : 15
				'superUser' =>'Authority', //default: Authorizer
				'css'=>'srbac.css', //default: srbac.css
				'layout'=>
				'application.views.admin.layouts.column1', //default: application.views.layouts.main,
				//must be an existing alias
				'notAuthorizedView'=> 'srbac.views.authitem.unauthorized', // default:
				//srbac.views.authitem.unauthorized, must be an existing alias
				'alwaysAllowed'=>array(
					//default: array()
					'SiteLogin','SiteLogout','SiteIndex','SiteAdmin',
					'SiteError', 'SiteContact'),
				'userActions'=>array('Show','View','List'), //default: array()
				'listBoxNumberOfLines' => 15, //default : 10
				'imagesPath' => 'srbac.images', // default: srbac.images
				'imagesPack'=>'noia', //default: noia
				'iconText'=>true, // default : false
				'header'=>'srbac.views.authitem.header', //default : srbac.views.authitem.header,
				//must be an existing alias
				'footer'=>'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
				//must be an existing alias
				'showHeader'=>true, // default: false
				'showFooter'=>true, // default: false
				'alwaysAllowedPath'=>'srbac.components', // default: srbac.components
				// must be an existing alias

			)
		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			// this is used in contact page
		),

	)
);