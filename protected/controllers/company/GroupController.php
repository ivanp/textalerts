<?php

class GroupController extends CCompanyController
{
	public function actionIndex()
	{
		$groups = Group::modelByCompany($this->company)->findAll();
		$this->render('index', array(
			'groups'=>$groups
		));
	}

	public function actionView($id)
	{
		$group = Group::modelByCompany($this->company)->findByPk($id);
		if ($group instanceof Group)
		{
			$this->render('view', array(
				'company'=>$this->company,
				'group'=>$group
			));
		}
		else
		{
			throw new CHttpException(404, 'Group not found', 404);
		}
	}

	public function actionAdd()
	{
		echo 'add new group here';
	}

	public function actionEdit($id)
	{

	}

	public function actionDelete($id)
	{
		
	}
}