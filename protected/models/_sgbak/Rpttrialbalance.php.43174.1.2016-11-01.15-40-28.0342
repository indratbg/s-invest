<?php

class Rpttrialbalance extends ARptForm
{
	public $month;
	public $year;
	public $bgn_date;
	public $end_date;
	public $report_mode;
	public $cancel_flg;
	public $branch_cd;
	public $branch_option;
	public $from_gla;
	public $to_gla;
	public $from_sla;
	public $to_sla;
	public $dummy_date;
	public $tempDateCol   = array();  
	
	public function rules()
	{
		return array(
			array('bgn_date,end_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			array('month,year,report_mode,cancel_flg,branch_cd,branch_option,from_gla,to_gla,from_sla,to_sla','safe')
		);
	}
	
	public function attributeLabels()
	{
		return array(
		
			
		);
	}
		

	public function executeRpt($bgn_acct,$end_acct,$bgn_sub,$end_sub,$branch, $rpt_mode)
	{
	 
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$query  = "CALL SPR_TRIAL_BALANCE( TO_DATE(:P_BGN_DATE,'YYYY-MM-DD'),
											    TO_DATE(:P_END_DATE,'YYYY-MM-DD'),
											    :P_BGN_ACCT,
											    :P_END_ACCT,
											    :P_BGN_SUB,
											    :P_END_SUB,
											    :P_BRANCH,
											    :P_MODE,
											    :P_USER_ID ,
											    :P_GENERATE_DATE,
											    :P_RANDOM_VALUE,
											    :P_ERROR_CD,
											    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_BGN_DATE",$this->bgn_date,PDO::PARAM_STR);
			$command->bindValue(":P_END_DATE",$this->end_date,PDO::PARAM_STR);
			$command->bindValue(":P_BGN_ACCT",$bgn_acct,PDO::PARAM_STR);
			$command->bindValue(":P_END_ACCT",$end_acct,PDO::PARAM_STR);
			$command->bindValue(":P_BGN_SUB",$bgn_sub,PDO::PARAM_STR);
			$command->bindValue(":P_END_SUB",$end_sub,PDO::PARAM_STR);
			$command->bindValue(":P_BRANCH",$branch,PDO::PARAM_STR);
			$command->bindValue(":P_MODE",$rpt_mode,PDO::PARAM_STR);
			$command->bindValue(":P_USER_ID",$this->vp_userid,PDO::PARAM_STR);
			$command->bindValue(":P_GENERATE_DATE",$this->vp_generate_date,PDO::PARAM_STR);
			$command->bindParam(":P_RANDOM_VALUE",$this->vo_random_value,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERROR_CD",$this->vo_errcd,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERROR_MSG",$this->vo_errmsg,PDO::PARAM_STR,200);

			$command->execute();
			$transaction->commit();
			
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->vo_errcd == -999){
				$this->vo_errmsg = $ex->getMessage();
		    }
		}
		
		if($this->vo_errcd < 0)
			$this->addError('vo_errmsg', 'Error '.$this->vo_errcd.' '.$this->vo_errmsg);
		
		return $this->vo_errcd;
	}
	

}
