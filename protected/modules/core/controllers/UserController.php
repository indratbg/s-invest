<?php

class UserController extends AAdminController
{	 
	public function actionRestartLogin()
	{
		if(isset($_POST['registereduser']) || isset($_POST['flag']))
		{
			$registered = @$_POST['registereduser'];
			
			if(!is_array($registered))
            	$registered = array($registered);
        
			foreach($registered as $user_id) 
        	{	
				$modelLoginlog = new Loginlog();
				$modelLoginlog->user_id 	= $user_id;
				$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
				$modelLoginlog->log_type 	= 'OUT';
				$modelLoginlog->save(FALSE);
        	}	
			
			Yii::app()->user->setFlash('success', 'Restart Login For '.CJSON::encode($registered));
		}
		
		
		$listStuckLoginUser = array();
		$listUser =  User::model()->findAll();
		
		foreach ($listUser as $modelUser) 
		{
			$criteriaLoginlog    	     = new CDbCriteria();
			$currentuserid				 = Yii::app()->user->id;
			$criteriaLoginlog->condition = "user_id=:user_id AND user_id <> '$currentuserid'";
			$criteriaLoginlog->params    = array(':user_id'=>$modelUser->user_id); 
			$criteriaLoginlog->order     = 'log_dt DESC';
				
	        $modelLoginlog = Loginlog::model()->find($criteriaLoginlog);
			
			if($modelLoginlog !== NULL && $modelLoginlog->log_type == 'IN' )
				$listStuckLoginUser[$modelUser->user_id] = $modelUser->user_id.' - '.$modelUser->user_name;	
		}
		
		$this->render('restartlogin',array(
            'listStuckLoginUser'=>$listStuckLoginUser,
        ));
	}
	
	public function actionAjxSearchUser() 
	{
		$resp['status']  = 'error';
		$resp['content'] = 'user not found';
		
		if(isset($_POST['search'])):
			
	        $search_key   		= $_REQUEST['search'];
			$search_key         = strtolower($search_key);
			$listStuckLoginUser = NULL;
			$listUser =  User::model()->findAll("user_id LIKE '%".$search_key."%'");
			
			foreach ($listUser as $modelUser) 
			{
				$criteriaLoginlog    	     = new CDbCriteria();
				$criteriaLoginlog->condition = "user_id=:user_id";
				$criteriaLoginlog->params    = array(':user_id'=>$modelUser->user_id); 
				$criteriaLoginlog->order     = 'log_dt DESC';
					
		        $modelLoginlog = Loginlog::model()->find($criteriaLoginlog);
				
				if($modelLoginlog !== NULL && $modelLoginlog->log_type == 'IN' ){
					$listStuckLoginUser['users'][] = array('user_id'=>$modelUser->user_id,'user_id_n_name'=>$modelUser->user_id.' - '.$modelUser->user_name);
				}	
			}
			
			if($listStuckLoginUser === NULL)
				$resp['content'] = 'user not found';
			else {
				$resp['status']  = 'success';
				$resp['content'] = $listStuckLoginUser;
			}
		endif;
		
        echo CJSON::encode($resp);
    }
	
	public function actionChangePassword()
    {
        $model = User::model()->findByPk(Yii::app()->user->id);
		$model->scenario = 'changepassword';
		$recyclecounter = 0;
		
        if(isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            if($model->validate())
            {
            	$row  	   = DAO::queryRowSql("SELECT F_ENCRYPTION('".$model->old_pass."') AS password FROM DUAL");
				$old_pass  = $row['password'];
				
                if($old_pass !== $model->password)
                    $model->addError('old_pass','Wrong Password!');
				else 
                {
                	unset(Yii::app()->request->cookies['expdate']);
					
					$qhowlong = DAO::queryRowSql("SELECT prm_cd_2 FROM MST_PARAMETER WHERE prm_cd_1 = 'PSWCYL'");
					$howlong = $qhowlong['prm_cd_2'];
					
					$curruser = Yii::app()->user->id;
					if($howlong)
						$oldpass = DAO::queryAllSql("select * from (select * from t_password_log where user_id = '$curruser' order by eff_date desc) where rownum <= $howlong");
					
					else
						$oldpass = null;
					
                	$row  	 = DAO::queryRowSql("SELECT F_ENCRYPTION('".$model->new_pass."') AS password FROM DUAL");
					$newpass = $row['password'];

					if($oldpass){
						foreach ($oldpass as $oldpassword){
							if($newpass == $oldpassword['password']){
								$recyclecounter = 1;
							}
						}
					}
								
					if($recyclecounter == 0){
						if($model->executeValidatePassword($curruser, $old_pass) > 0){
							$model->password  	= $newpass;					
							$model->expiry_date = date("Y-m-d",strtotime("+3 months"));
	                    	$model->save(FALSE);
							
							$modelPasswordLog = new Tpasswordlog;
							$modelPasswordLog->user_id = $curruser;
							$modelPasswordLog->password = $newpass;
							$modelPasswordLog->eff_date = new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
							$modelPasswordLog->save(FALSE);
							
							$model = User::model()->findByPk(Yii::app()->user->id);
		                    Yii::app()->user->setFlash('success', 'Successfully update password, password will be expired on '.Yii::app()->format->formatDate($model->expiry_date));
						}						
					}else{
						$model->addError('new_pass','Password was recently used!');
					}
				}
            }
        }
    
        $this->render('changepass',array(
            'model'=>$model,
        ));
    }	

	public function actionView($id)
	{
		$model 			 = $this->loadModel($id);
		$model->regn_cd  = trim($model->regn_cd);
		
		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model=new User;
		$model->scenario = 'insert';
		$model->unsetAttributes();
		$model->sts_suspended = 'N';
		
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			
			if($model->validate())
			{
				$model->user_id  = $_POST['User']['user_id'];
		        $row  			  			= DAO::queryRowSql("SELECT F_ENCRYPTION('".trim($model->new_pass)."') AS password FROM DUAL");
				$model->encrypted_password  = $row['password'];
				$model->password  			= trim($model->new_pass);
				
				if($model->executeSp(AConstant::INBOX_STAT_INS,$model->user_id) > 0 ){
					Yii::app()->user->setFlash('success', 'Successfully create '.$model->user_id);
					$this->redirect(array('/core/user/index'));
				}
            }
		}else{
			$model->expiry_date = date('d/m/Y',strtotime("+3 months"));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model			 = $this->loadModel($id);
		$model->regn_cd  = trim($model->regn_cd);
		
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			if(!empty($_POST['User']['new_pass'])){
				$model->password			= $model->new_pass;
				$row  			  			= DAO::queryRowSql("SELECT F_ENCRYPTION('".$model->new_pass."') AS password FROM DUAL");
				$model->encrypted_password  = $row['password'];
			}else{
				$model->encrypted_password  = $model->password;
				$model->password			= null;
			}
			
			if($model->validate() && ($model->executeSp(AConstant::INBOX_STAT_UPD,$id) > 0) ){	
                Yii::app()->user->setFlash('success', 'Successfully update '.$model->user_id);
				$this->redirect(array('/core/user/index'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->layout 	= '//layouts/main_popup';
		$is_successsave = false;
		
		$model  = new Ttempheader();
		$model->scenario = 'cancel';
		$model1 = NULL;
		
		if(isset($_POST['Ttempheader']))
		{
			$model->attributes = $_POST['Ttempheader'];	
					
			if($model->validate()){
				$model1    				= $this->loadModel($id);
				$model1->cancel_reason  = $model->cancel_reason;
				if($model1->executeSp(AConstant::INBOX_STAT_CAN,$id) > 0){
					Yii::app()->user->setFlash('success', 'Successfully cancel '.$model1->user_id);
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
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		$model->approved_stat = 'A';

		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
