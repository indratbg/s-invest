<?php

class Gensecujur004 extends CFormModel
{
	public $due_date;
	public $user_id;
	public $ip_address;
	public $error_code=-999;
	public $error_msg='Initial value';
	public $tempDateCol   = array();  
	
	
	public function rules()
	{
		return array(
			array('due_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			array('due_date','required'),
			array('','safe')
		);
	}
	public function onAfterValidate()
	{
			$this->user_id =   Yii::app()->user->id;
	}
	
	public function attributeLabels()
	{
		return array(
			'due_date'=>'Due Date',
		);
	}

	
public function executeSp()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		try{
			$query  = "CALL   SP_GEN_SECJUR_004(TO_DATE(:p_curr_date,'YYYY-MM-DD'),
												   :P_USER_ID,
												   :P_ERROR_CODE,
												   :P_ERROR_MSG)";
		
			$command = $connection->createCommand($query);
			$command->bindValue(":p_curr_date",$this->due_date,PDO::PARAM_STR);
			$command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,100);

			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	}
	
	
}
