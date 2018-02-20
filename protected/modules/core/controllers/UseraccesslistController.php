<?php

class UseraccesslistController extends AAdminController
{	 
	public function actionIndex()
	{
		$model=new Vuseraccesslist('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['Vuseraccesslist']))
			$model->attributes=$_GET['Vuseraccesslist'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Vuseraccesslist::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
