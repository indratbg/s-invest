<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_EXPIRED = 4;
    const ERROR_USER_SUSPEND = 5;
	const ERROR_USER_NOUSERGROUP = 6;
	const ERROR_USER_ALREADYLOGIN = 7;
	const ERROR_USER_NOT_APPROVED = 8;
	
    const ERROR_USER_SUSPEND_WRONG_3X = 3;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$tempusername = $this->username;
		$tempusername = strtoupper($tempusername);
		
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		
	    $model    = User::model()->find('user_id = TRIM(:username)', array(':username'=>$tempusername));
        $password = $this->password;
        
        if(!isset($model))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
		else{
			/*$criteriaLoginlog    	     = new CDbCriteria();
			$criteriaLoginlog->condition = "LOWER(user_id)=:user_id AND TO_CHAR(log_dt,'YYYY-MM-DD') = :log_dt";
			$criteriaLoginlog->params    = array(':user_id'=>$tempusername,':log_dt'=>Yii::app()->datetime->getDateNow()); 
			$criteriaLoginlog->order     = 'log_dt DESC';
				
	        $modelLoginlog = Loginlog::model()->find($criteriaLoginlog);*/
			
			$result = DAO::queryRowSql("
						SELECT TO_CHAR(log_dt,'YYYYMMDDHH24MISS') log_dt 
						FROM T_LOGIN_LOG 
						WHERE user_id = '$model->user_id' AND log_type = 'OUT'
			            and log_dt >= trunc(sysdate)
			            order by log_dt desc
					");
			$logOutDate = $result['log_dt'];
			
			$result = DAO::queryRowSql("
						SELECT TO_CHAR(log_dt,'YYYYMMDDHH24MISS') log_dt, ip_address
						FROM T_LOGIN_LOG 
						WHERE user_id = '$model->user_id' AND log_type = 'IN' AND description = 'SUCCESS'
			            and log_dt >= trunc(sysdate)
			            order by log_dt desc
					");
			$logInDate = $result['log_dt'];
			$logInIP = $result['ip_address'];
			//var_dump($logInDate > $logOutDate);
			//die();
			//if($modelLoginlog !== NULL && $modelLoginlog->log_type == 'IN' && ($modelLoginlog->description == 'SUCCESS' || $modelLoginlog->description == 'USER ALREADY LOGIN')){
			if($logInDate > $logOutDate && $ip != $logInIP){	
				$this->errorCode = self::ERROR_USER_ALREADYLOGIN;
				$modelLoginlog = new Loginlog();
				$modelLoginlog->user_id 	= $model->user_id;
				$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
				$modelLoginlog->log_type 	= 'IN';
				$modelLoginlog->description = 'USER SUDAH LOGIN DI TEMPAT LAIN';
				$modelLoginlog->ip_address = $ip;
				$modelLoginlog->save(FALSE);
			}else{
			
				$row  	   = DAO::queryRowSql("SELECT F_ENCRYPTION('".$password."') AS password FROM DUAL");
				$password  = $row['password'];
				
				if($model->approved_stat != 'A'){
					$this->errorCode=self::ERROR_USER_NOT_APPROVED;
					$modelLoginlog = new Loginlog();
					$modelLoginlog->user_id 	= $model->user_id;
					$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
					$modelLoginlog->log_type 	= 'IN';
					$modelLoginlog->description = 'USER BELUM DIAPPROVE';
					$modelLoginlog->ip_address = $ip;
					$modelLoginlog->save(FALSE);
				}else if($model->sts_suspended == AConstant::IS_FLAG_Y){
		        	$this->errorCode=self::ERROR_USER_SUSPEND;
					$modelLoginlog = new Loginlog();
					$modelLoginlog->user_id 	= $model->user_id;
					$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
					$modelLoginlog->log_type 	= 'IN';
					$modelLoginlog->description = 'USER DISUSPEND';
					$modelLoginlog->ip_address = $ip;
					$modelLoginlog->save(FALSE);
				}else if($model->password !== $password){
		            $modelLoginlog = new Loginlog();
					$modelLoginlog->user_id 	= $model->user_id;
					$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
					$modelLoginlog->log_type 	= 'IN';
					$modelLoginlog->ip_address = $ip;
					$model->ctr_fail_login++;
					if($model->ctr_fail_login == 3){
						$modelLoginlog->description = '3X SALAH PASSWORD, USER DISUSPEND';
						$model->sts_suspended = 'Y';
						$this->errorCode=self::ERROR_USER_SUSPEND_WRONG_3X;
					}else{
						$modelLoginlog->description = 'SALAH PASSWORD';
						$this->errorCode = self::ERROR_PASSWORD_INVALID;
					}
					
					$modelLoginlog->save(FALSE);
					
		        }else if($model->expiry_date < date('Y-m-d')){
		            $this->errorCode=self::ERROR_USER_EXPIRED;
					$modelLoginlog = new Loginlog();
					$modelLoginlog->user_id 	= $model->user_id;
					$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
					$modelLoginlog->log_type 	= 'IN';
					$modelLoginlog->description = 'PASSWORD EXPIRED';
					$modelLoginlog->ip_address = $ip;
					$modelLoginlog->save(FALSE);
		        }else{
		            $oUserGroup  = Usergroupdetail::model()->findAll('user_id=:user_id',array(':user_id'=>$model->user_id));
		            $oUserGroup2 = NULL;
					
					if($oUserGroup == NULL){
						$this->errorCode=self::ERROR_USER_NOUSERGROUP;
						$modelLoginlog = new Loginlog();
						$modelLoginlog->user_id 	= $model->user_id;
						$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
						$modelLoginlog->log_type 	= 'IN';
						$modelLoginlog->description = 'USER BELUM DIBERIKAN AKSES';
						$modelLoginlog->ip_address = $ip;
						$modelLoginlog->save(FALSE);
						return !$this->errorCode;
					}else{
			            foreach ($oUserGroup as $value)
			                $oUserGroup2[] = $value->usergroup_id;
			        
						$this->username = $model->user_name;    
			            $this->setState('id',$model->user_id);
			            $this->setState('name',$model->user_name);
			            $this->setState('usergroup_id',$oUserGroup2);
						$model->ctr_fail_login = 0;
			            $this->errorCode	   = self::ERROR_NONE;
						
						// AH: log user IN status to table 
						$modelLoginlog = new Loginlog();
						$modelLoginlog->user_id 	= $model->user_id;
						$modelLoginlog->log_dt  	= new CDbExpression("TO_DATE( '".Yii::app()->datetime->getDateTimeNow()."', 'YYYY-MM-DD HH24:MI:SS')");
						$modelLoginlog->log_type 	= 'IN';
						$modelLoginlog->description = 'SUCCESS';
						$modelLoginlog->ip_address = $ip;
						$modelLoginlog->save(FALSE); 		   
					}
		        }
				
				$query = "UPDATE MST_USER SET ctr_fail_login = $model->ctr_fail_login, 
				 			sts_suspended = '$model->sts_suspended' 
							WHERE user_id = '$model->user_id' ";
							
				DAO::executeSql($query);
		    }
			return !$this->errorCode;
        }
        return !$this->errorCode;
	}
}