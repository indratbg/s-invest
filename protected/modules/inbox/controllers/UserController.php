<?php

class UserController extends AAdminController
{
	public $table_name = 'MST_USER';
	
	public function actionView($id)
	{
		$model			  = $this->loadModel($id);
		$modelDetail	  = null;
		$modelDetailUpd   = null;
		$listttempdetail  = Ttempdetail::model()->findAll('update_seq =:update_seq',array(':update_seq'=>$model->update_seq));
		
		if($model->status == AConstant::INBOX_STAT_UPD){
			$modelDetail     = User::model()->find("rowid ='$model->table_rowid'");	// left
			$modelDetailUpd  = User::model()->find("rowid ='$model->table_rowid'");	// right [updated version]
			Ttempdetail::generateModelAttributes2($modelDetailUpd, $listttempdetail);
			
			if($model->approved_status != AConstant::INBOX_APP_STAT_ENTRY):
				$this->render('view',array(
					'model'=>$model,
					'modelDetail'=>$modelDetail,
				));	
			else:
				$this->render('view_compare',array(
					'model'=>$model,
					'modelDetail'=>$modelDetail,
					'modelDetailUpd'=>$modelDetailUpd
				));
			
			endif;	
		}else{
			if($model->status == AConstant::INBOX_STAT_CAN){
				$modelDetail     = User::model()->find("rowid ='$model->table_rowid'");	
			}else{
				$modelDetail  = new User();
				Ttempdetail::generateModelAttributes2($modelDetail, $listttempdetail);
			}
			
			$this->render('view',array(
				'model'=>$model,
				'modelDetail'=>$modelDetail,
			));	
		}
	}

	public function actionAjxPopReject($id)
	{
		$this->layout 	= '//layouts/main_popup';
		$is_successsave = false;
		
		$model = Ttempheader::model()->findByPk($id);
		$model->scenario = 'reject';
		
		if(isset($_POST['Ttempheader']))
		{
			$model->attributes = $_POST['Ttempheader'];			
			if($model->validate()):
				$this->reject($model);
				$is_successsave = true;
			endif;
		}

		$this->render('/template/_popreject',array(
			'model'=>$model,
			'is_successsave'=>$is_successsave
		));
	}

	public function actionAjxPopRejectChecked()
	{
		$this->layout 	= '//layouts/main_popup';
		
		if(!isset($_GET['arrid']))
			throw new CHttpException(404,'The requested page does not exist.');
		
		$is_successsave = false;
		$model = new Ttempheader();
		$model->scenario = 'rejectchecked';
		
		$arrid = $_GET['arrid'];
			
		if(isset($_POST['Ttempheader']))
		{
			$model->attributes = $_POST['Ttempheader'];
			if($model->validate() && $this->rejectChecked($model,$arrid))
				$is_successsave = true;
		}	
	

		$this->render('/template/_popreject',array(
			'model'=>$model,
			'is_successsave'=>$is_successsave
		));	
	} 
	
	public function actionApprove($id)
	{
		$model = $this->loadModel($id);		
		$model->approve();
		
		if($model->error_code < 0)
			Yii::app()->user->setFlash('error', 'Approve '.$model->update_seq.', Error  '.$model->error_code.':'.$model->error_msg);
		else
			Yii::app()->user->setFlash('success', 'Successfully approve '.$id);
		
		$this->redirect(array('index','id'=>$model->update_seq));
	}
	
	private function reject(&$model)
	{		
		$model->reject($model->reject_reason);
		
		if($model->error_code < 0)
			Yii::app()->user->setFlash('error', 'Approve '.$model->update_seq.', Error  '.$model->error_code.':'.$model->error_msg);
		else
			Yii::app()->user->setFlash('success', 'Successfully reject '.$model->update_seq);
	}
	
	private function rejectChecked($model,$arrid)
	{
		$reject_reason = $model->reject_reason;
		
		foreach($arrid as $id):
			$model = $this->loadModel($id);	
			$model->reject($reject_reason);
			
			if($model->error_code < 0){
				Yii::app()->user->setFlash('error', 'Error reject '.$model->update_seq.' '.$model->error_msg);
				return false;
			}
		endforeach;
		
		Yii::app()->user->setFlash('success', 'Successfully reject '.json_encode($arrid));
		return true;
	}
	

	public function actionApproveChecked()
	{
		$result  = 'error';
		
		if(isset($_POST['arrid'])):
			
			$arrid	 = $_POST['arrid'];
			$result  = 'success';
			
			foreach($arrid as $id){
				$model = $this->loadModel($id);		
				$model->approve();
				
				if($model->error_code < 0){
					$result  = 'error';
					break;
				}
			}
			
			if($result  == 'error')
				Yii::app()->user->setFlash('error', 'Error approve '.$model->update_seq.' '.$model->error_msg);
			else
				Yii::app()->user->setFlash('success', 'Successfully approve '.json_encode($arrid));
		endif;
		
		echo $result;
	}

	public function actionIndex()
	{
		$model = new Ttempheader('search');
		$model->unsetAttributes();
		$model->table_name = $this->table_name;
		$model->approved_status = AConstant::INBOX_APP_STAT_ENTRY;
		
		if(isset($_GET['Ttempheader']))
			$model->attributes=$_GET['Ttempheader'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionIndexProcessed()
	{
		$model = new Ttempheader('search');
		$model->unsetAttributes();
		$model->table_name = $this->table_name;
		$model->approved_status = '<>'.AConstant::INBOX_APP_STAT_ENTRY;
		
		if(isset($_GET['Ttempheader']))
			$model->attributes=$_GET['Ttempheader'];

		$this->render('index_processed',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model = Ttempheader::model()->find('update_seq=:update_seq AND table_name=:table_name',array(':update_seq'=>$id,':table_name'=>$this->table_name));
		
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
