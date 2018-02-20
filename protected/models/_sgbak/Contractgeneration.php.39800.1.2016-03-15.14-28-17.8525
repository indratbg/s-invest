<?php 
	class Contractgeneration extends CFormModel
	{
		public $error_code 	  = -999;
		public $error_msg  	  = 'Initial Value';
		public $ip_address;
		
		public $tc_date;
		public $trx_cnt;
		//public static $options=array('All'=>'Show All','DIFF'=>'Show Difference');
		
		
		public function attributeLabels()
		{
			return array(
			);
			
		}
		
		/**
		 * Declares the validation rules.
		 * The rules state that user_id and password are required,
		 * and password needs to be authenticated. 
		 */
		public function rules()
		{
			return array(
				array('tc_date', 'required')
			);
		}
		
		public function executeSpConGen()
		{
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();	
			try{
				$query  = " CALL SP_CONTRACT_GENERATION(
							TO_DATE(:P_EXCHANGE_DATE,'dd/mm/yyyy'),
							:P_USER_ID,
							:P_IP_ADDRESS,
							:P_TRX_CNT,
							:P_ERROR_CODE,
							:P_ERROR_MSG)";
							
							
				$command = $connection->createCommand($query);
				$command->bindValue(":P_EXCHANGE_DATE",$this->tc_date,PDO::PARAM_STR);
				$command->bindValue(":P_USER_ID",Yii::app()->user->id,PDO::PARAM_STR);
				$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
				$command->bindParam(":P_TRX_CNT",$this->trx_cnt,PDO::PARAM_INT,10);
				$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
				$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,500);
	
				$command->execute();
				$transaction->commit();
			}catch(Exception $ex){
				
				if($this->error_code = -999)
					$this->error_msg = $ex->getMessage();
				$transaction->rollback();
			}
			
			if($this->error_code < 0)
				$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
			
			return $this->error_code;
		}
	}
 ?>