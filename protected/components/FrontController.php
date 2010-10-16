<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function init()
	{
		$this->menu = array(
				array('label'=>'Home', 'url'=>array('site/index')),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Dashboard', 'url'=>array('/site/dashboard'), 'visible'=>!Yii::app()->user->isGuest),
//				array('label' => 'Control Center', 'url'=>array('/admin'), 'visible' => (!Yii::app()->user->isGuest && Yii::app()->user->record->admin == 'Y')),
				array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>array('/user/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Account ('.Yii::app()->user->name.')', 'url'=>array('/user/account'), 'visible'=>!Yii::app()->user->isGuest, 'items' => array(
						array('label' => 'Profile', 'url'=>array('/user/profile')),
						array('label' => 'Password', 'url'=>array('/user/password')),
						array('label' => 'Logout', 'url'=>array('/user/logout'))
				)),
			);
	}
}