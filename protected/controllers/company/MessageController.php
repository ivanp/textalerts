<?php

class MessageController extends CCompanyController
{
	public function actionCreate()
	{
		$groups = Group::modelByCompany($this->company)->findAll();

		$this->render('create', array(
			'model'	=> new CreateNewMessageForm(),
			'groups' => $groups
		));
	}

	public function actionIndex($status = 'all')
	{

	}
}