<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CFrontController extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';

	public function init()
	{
		$this->menu = array(
				array('label'=>'Home', 'url'=>array('site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Demo', 'url'=>array('/site/page', 'view'=>'demo')),
				array('label'=>'Sign Up', 'url'=>array('/site/page', 'view'=>'paypal')),
//				array('label'=>'Dashboard', 'url'=>array('site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
//				array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
//				array('label'=>'Register', 'url'=>array('/user/register'), 'visible'=>Yii::app()->user->isGuest),
//				array('label'=>'Account ('.Yii::app()->user->name.')', 'url'=>array('/user/account'), 'visible'=>!Yii::app()->user->isGuest, 'items' => array(
//						array('label' => 'Profile', 'url'=>array('/user/profile')),
//						array('label' => 'Password', 'url'=>array('/user/password')),
//						array('label' => 'Logout', 'url'=>array('/user/logout'))
//				)),
			);
	}
}