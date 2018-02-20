<?php

class Transferarapont3 extends CFormModel
{
	public $due_date;
	public $user_id;
	public $error_code=-999;
	public $error_msg='Initial value';
	public $ip_address;
	public function rules()
	{
		return array(
			//array('due_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			array('due_date','safe')
		);
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
			$query  = "CALL  SP_PINDAH_T3 (to_date(:p_due_date,'yyyy-mm-dd'),
												:P_IP_ADDRESS,
										   :p_user_id,
										   :p_error_code ,
										   :p_error_msg)";
		
			$command = $connection->createCommand($query);
			$command->bindValue(":p_due_date",$this->due_date,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			$command->bindValue(":p_user_id",$this->user_id,PDO::PARAM_STR);
			$command->bindParam(":p_error_code",$this->error_code,PDO::PARAM_INT,10);
			$command->bindParam(":p_error_msg",$this->error_msg,PDO::PARAM_STR,200);

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
