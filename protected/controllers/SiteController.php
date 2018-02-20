<?php

class SiteController extends AAdminController
{
	//public $expired = null;
	public $layout = '//layouts/column1';	
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
// 		if(Yii::app()->user->isGuest)
// 			$this->redirect(array('/site/login'));
// 		else
			if(!Yii::app()->request->cookies['expdate']){
				$curruser = Yii::app()->user->id;
				$modeluser = DAO::queryRowSql("select (trunc(expiry_date) - trunc(sysdate)) as remains from mst_user where lower(user_id) = lower('$curruser')");
				$expdate = $modeluser['remains']; // AS : Range between expiry date and current system date
				Yii::app()->request->cookies['expdate'] = new CHttpCookie('expdate', $expdate);
			}
			//var_dump(Yii::app()->params['session_timeout']);
			//die();
			$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	/**
	 * This is the action to handle external exceptions in popup window
	 */
	public function actionErrorpopup()
	{
		$this->layout = '//layouts/iframe1';
		$this->actionError();
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		$modeluser = new User;
		
		$lastactivity = new DateTime();
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		$ischangepassword = 0;
		//var_dump(isset($_POST['User']));
		//die();
		//if(!empty($_POST['User']["old_pass"]) && !empty($_POST['User']["new_pass"]) && !empty($_POST['User']["confirm_pass"]))
		if(isset($_POST['User']))
        {
        	$ischangepassword = 1;
        	$model->attributes=$_POST['LoginForm'];
			if($model->validate(array('user_id'))){
				$model->user_id = strtoupper($model->user_id);
				$modeluser = User::model()->findByPk($model->user_id);
	        	$modeluser->scenario = 'changepassword';
				$recyclecounter = 0;
	            $modeluser->attributes = $_POST['User'];
	            if($modeluser->validate())
	            {
	            	$row  	   = DAO::queryRowSql("SELECT F_ENCRYPTION('".$modeluser->old_pass."') AS password FROM DUAL");
					$old_pass  = $row['password'];
					
	                if($old_pass !== $modeluser->password)
	                    $modeluser->addError('old_pass','Wrong Password!');
					else 
	                {
	                	unset(Yii::app()->request->cookies['expdate']);
						
						$qhowlong = DAO::queryRowSql("SELECT prm_cd_2 FROM MST_PARAMETER WHERE prm_cd_1 = 'PSWCYL'");
						$howlong = $qhowlong['prm_cd_2'];
						
						$curruser = $modeluser->user_id;
						if($howlong)
							$oldpass = DAO::queryAllSql("select * from (select * from t_password_log where user_id = '$curruser' order by eff_date desc) where rownum <= $howlong");
						
						else
							$oldpass = null;
						
	                	$row  	 = DAO::queryRowSql("SELECT F_ENCRYPTION('".$modeluser->new_pass."') AS password FROM DUAL");
						$newpass = $row['password'];
						
						//var_dump($oldpass);
						//die();
						
						if($oldpass){
							foreach ($oldpass as $oldpassword){
								if($newpass == $oldpassword['password']){
									$recyclecounter = 1;
								}
							}
						}
	
						if($recyclecounter == 0){
							if($modeluser->executeValidatePassword($curruser, $old_pass) > 0){
								$modeluser->password  	= $newpass;					
								$modeluser->expiry_date = date("Y-m-d",strtotime("+3 months"));
		                    	$modeluser->save(FALSE);
								
								$modelPasswordLog = new Tpasswordlog;
								$modelPasswordLog->user_id = $modeluser->user_id;
								$modelPasswordLog->password = $newpass;
								$modelPasswordLog->eff_date = new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
								$modelPasswordLog->save(FALSE);
								
								$modeluser = User::model()->findByPk($model->user_id);
			                    Yii::app()->user->setFlash('success', 'Successfully update password, password will be expired on '.Yii::app()->format->formatDate($modeluser->expiry_date));
							}						
						}else{
							$modeluser->addError('new_pass','Password was recently used!');
						}
					}
	            }
            }
        }
		
		if(isset($_POST['LoginForm']) && $ischangepassword == 0)
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				$curruser = Yii::app()->user->id;
								
				$modeluser = DAO::queryRowSql("select (trunc(expiry_date) - trunc(sysdate)) as remains from mst_user where lower(user_id) = lower('$curruser')");
				$expdate = $modeluser['remains']; // AS : Range between expiry date and current system date
				
				$arrUsergroupId  		= Yii::app()->user->usergroup_id;
			
				// AH : getting first registered user group (high usually us better)
				asort($arrUsergroupId);	
				
				$modelUserGroup 		= Usergroup::model()->findByPk($arrUsergroupId[0]);
				$modelMenuAction 		= Menuaction::model()->findByPk($modelUserGroup->menuaction_id);
				
				if(!empty($modelMenuAction->menuaction_id))
					$this->redirect(array($modelMenuAction->action_url));
				else{						// AH: default redirect if not yet saved				
					Yii::app()->request->cookies['curruser'] = new CHttpCookie('curruser', $curruser);
					Yii::app()->request->cookies['lastactivity'] = new CHttpCookie('lastactivity', $lastactivity->getTimestamp());
					Yii::app()->request->cookies['expdate'] = new CHttpCookie('expdate', $expdate);
					$this->redirect(array('index'));
				}
				
			}
		}

		// display the login form
		$this->render('login',array('model'=>$model,'modeluser'=>$modeluser));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$modelLoginlog = new Loginlog();
		
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		
		$modelLoginlog->user_id 	= Yii::app()->user->id;
		$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
		$modelLoginlog->log_type 	= 'OUT';
		$modelLoginlog->ip_address  = $ip;
		if(isset($modelLoginlog->user_id))
			$modelLoginlog->save(FALSE);
		Yii::app()->request->cookies->clear();
		Yii::app()->user->logout(); 
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionTestpush() {
		$soc = SocketToFront::getInstance();
		//$ps=new SocketToFront();
		 $connectRslt=$soc->connectFO();
		 echo 'hasil connect'.$connectRslt;
		 echo '<br> socketURL: '.$soc->socketURL();
		 
		 if($connectRslt!="OK"){
				Yii::app()->user->setFlash('error', 'Error Connect Socket:  '.$connectRslt." ,socket url: ".$soc->socketURL());
				return;
			}
		// echo '<br> pushclient result: '.$soc->pushClientCash('CHAN021R');
		
		 echo '<br> pushclient result: '.$soc->pushClientStock('CHAN021R','AAAB');
		  $closeRslt=$soc->closeConnection();
		 echo '<br> close socket result: '.$closeRslt;
				 
	}
	public function actionConnect()
	{
		$database = '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.8.70)(PORT = 1521)))(CONNECT_DATA=(SID=orcllim)))';	
		$connection = oci_connect('ipnextg', '123', $database);
		
		if (!$connection) {
		   $m = oci_error();
		   echo $m['message'], "\n";
		   exit;
		}
		else 
		{
			$sql = "BEGIN SP_C_TEST_INSERT(:P_PARAM1,:P_PARAM2); END;";
			
			$query = oci_parse($connection, $sql);
			$param1_in = 'b';
			$param2_out = '0';
			oci_bind_by_name($query,':P_PARAM1',$param1_in);
			oci_bind_by_name($query,':P_PARAM2',$param2_out,200);
		   	$res = oci_execute($query, OCI_DEFAULT);
		   oci_commit($connection);
		   //var_dump($param2_out);die();
		  // $hasil = oci_fetch_object($query);
		
		 return $param2_out;
		   
		}
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array_merge(array(
				array('deny',
					'actions'=>array('login'),
					'users'=>array('@'),
				),
				array('allow',
					'actions'=>array('index','errorpopup','testpush','connect'),
					'users'=>array('@'),
				),
				array('allow',
					'actions'=>array('error'),
					'users'=>array('*'),
				),
				array('allow',
					'actions' => array('login','logout'),
					'users' => array('*'),
				),
		),parent::accessRules());
	}
}