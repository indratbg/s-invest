<?php

class ParameterController extends AAdminController
{
	public function actionView($prm_cd_1,$prm_cd_2)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($prm_cd_1,$prm_cd_2),
		));
	}

	public function actionCreate()
	{
		$model=new Parameter;

		if(isset($_POST['Parameter'])){
			$model->attributes=$_POST['Parameter'];
			if($model->validate() && $model->executeSp(AConstant::INBOX_STAT_INS,$model->prm_cd_1, $model->prm_cd_2) > 0 ){
            	Yii::app()->user->setFlash('success', 'Successfully create '.$model->prm_cd_1.' '.$model->prm_cd_2);
				$this->redirect(array('/master/parameter/index'));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($prm_cd_1,$prm_cd_2)
	{
		$model=$this->loadModel($prm_cd_1,$prm_cd_2);

		if(isset($_POST['Parameter']))
		{
			$model->attributes=$_POST['Parameter'];
			if($model->validate() && $model->executeSp(AConstant::INBOX_STAT_UPD,$prm_cd_1, $prm_cd_2) > 0 ){
				Yii::app()->user->setFlash('success', 'Successfully update '.$model->prm_cd_1.' '.$model->prm_cd_2);
             	$this->redirect(array('/master/parameter/index'));
			    //$this->redirect(array('view','prm_cd_1'=>$model->prm_cd_1,'prm_cd_2'=>$model->prm_cd_2));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($prm_cd_1,$prm_cd_2)
	{
		$this->layout 	= '//layouts/main_popup';
		$is_successsave = false;
		
		$model1 = NULL;
		$model  = new Ttempheader();
		$model->scenario = 'cancel';
		
		if(isset($_POST['Ttempheader']))
		{
			$model->attributes = $_POST['Ttempheader'];	
					
			if($model->validate()){
				
				$model1    				= $this->loadModel($prm_cd_1, $prm_cd_2);
				$model1->cancel_reason  = $model->cancel_reason;
				
				if($model1->validate() && $model1->executeSp(AConstant::INBOX_STAT_CAN,$prm_cd_1, $prm_cd_2) > 0){
					Yii::app()->user->setFlash('success', 'Successfully cancel bank');
					$is_successsave = true;
				}
			}
		}

		$this->render('_popcancel',array(
			'model'=>$model,
			'model1'=>$model1,
			'is_successsave'=>$is_successsave		
		));
	}

	public function actionIndex()
	{
		$model=new Parameter('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Parameter']))
			$model->attributes=$_GET['Parameter'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($prm_cd_1,$prm_cd_2)
	{
		$model=Parameter::model()->findByPk(array('prm_cd_1'=>$prm_cd_1,'prm_cd_2'=>$prm_cd_2));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
