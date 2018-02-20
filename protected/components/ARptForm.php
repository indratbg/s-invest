<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ARptForm extends CFormModel
{
    // AH  
	public $vp_userid;
	public $vp_generate_date;
	
	public $vo_random_value;
	public $vo_errcd;
	public $vo_errmsg;
	public $trx_date;
	public $approved_stat;
	public $tablename; 
	public $module;
	public $rptname;
	
	//Special Case MKBD
	public $p_vd51;
	public $p_vd52;
	public $p_vd53;
	public $p_vd54;
	public $p_vd55;
	public $p_vd56;
	public $p_vd57;
	public $p_vd58;
	public $p_vd59;
	public $p_vd510a;
	public $p_vd510b;
	public $p_vd510c;
	public $p_vd510d;
	public $p_vd510e;
	public $p_vd510f;
	public $p_vd510g;
	public $p_vd510h;
	public $p_vd510i;
	
	
    public function __construct($module,$tablename,$rptname,$scenario = 'insert')
	{
        parent::__construct($scenario);
        $this->attachEventHandler('onAfterValidate', '');
		
		// [AH] Setting variable
		$this->tablename = $tablename;
		$this->module    = $module;	
		$this->rptname   = $rptname;
	
    }

    public function onAfterValidate($event)
	{
        $this->vo_errcd  = -999;
		$this->vo_errmsg = 'Initial Value';
		
        $this->vp_userid = Yii::app()->user->id;
		$this->vp_generate_date = Yii::app()->datetime->getDateTimeNow();         
    }
	
	public function showReport($bgn_date='', $end_date='')
	{
		$url = "";
		self::changeIP($this->rptname);
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		
		try{				
			//insert token here
			if(!is_array($this->tablename)) 
			{
				$modelToken   = new Token;
				$modelToken->user_id 	= $this->vp_userid;
				$modelToken->module 	= $this->module;
				$modelToken->tablename	= $this->tablename;
				$modelToken->token_cd 	= $this->vp_userid.$this->module.date("YmdHis");
				$modelToken->token_date = date("Y-m-d H:i:s");
				$modelToken->random_value = $this->vo_random_value;
				
				
				$modelDeleteToken = Token::model()->find("user_id =:user_id AND module =:module", array(':user_id'=>$modelToken->user_id,':module'=>$modelToken->module));
				
				if($modelDeleteToken != null)
				{
					//20dec, untuk report yang tidak menggunakan parameter tidak perlu panggil SP_RPT_REMOVE_RAND
					if($modelToken->module != 'LIST_OF_CASH_DIVIDEN'){
						
						
					// [AR] delete old report data 1 user = 1 trash
					$query  = "CALL SP_RPT_REMOVE_RAND(:VP_TABLE_NAME,:VP_RAND_VALUE,:VO_ERRCD,:VO_ERRMSG)";	
					$command = $connection->createCommand($query);
					
					$command->bindValue(":VP_TABLE_NAME",$modelDeleteToken->tablename,PDO::PARAM_STR);
					$command->bindValue(":VP_RAND_VALUE",$modelDeleteToken->random_value,PDO::PARAM_STR);
					//$command->bindValue(":VP_USERID",$modelDeleteToken->user_id,PDO::PARAM_STR);
					
					$command->bindParam(":VO_ERRCD",$this->vo_errcd,PDO::PARAM_INT,10);
					$command->bindParam(":VO_ERRMSG",$this->vo_errmsg,PDO::PARAM_STR,100);
		
					$command->execute();
					
					$modelDeleteToken->delete();
					}
				}
				if ($modelToken->module !='LAPORAN_TRX_HARIAN'){
					
				$modelToken->save();
				}
				
			}
			else 
			{	//LO : For report that uses multiple tables
				$x = 1;			
				$tokenCdDate = date("YmdHis");
			
				foreach($this->tablename as $val)
				{
					$modelToken   = new Token;
					$modelToken->user_id 	= $this->vp_userid;
					$modelToken->module 	= $this->module;
					$modelToken->token_date = date("Y-m-d H:i:s");
					$modelToken->random_value = $this->vo_random_value;
					if($x == 1)$modelToken->token_cd 	= $this->vp_userid.$this->module.$tokenCdDate;
					else {
						$modelToken->token_cd 	= $this->vp_userid.$this->module.$tokenCdDate.'_'.$x;
					}
					$modelToken->tablename	= $val;
					
					$modelDeleteToken = Token::model()->find("user_id =:user_id AND module =:module AND tablename =:tablename", array(':user_id'=>$modelToken->user_id,':module'=>$modelToken->module,':tablename'=>$modelToken->tablename));
				
					if($modelDeleteToken != null )
					{
						
						if($modelToken->module != 'LIST_OF_CASH_DIVIDEN' ){
						// [AR] delete old report data 1 user = 1 trash
						$query  = "CALL SP_RPT_REMOVE_RAND(:VP_TABLE_NAME,:VP_RAND_VALUE,:VO_ERRCD,:VO_ERRMSG)";	
						$command = $connection->createCommand($query);
						
						$command->bindValue(":VP_TABLE_NAME",$modelDeleteToken->tablename,PDO::PARAM_STR);
						$command->bindValue(":VP_RAND_VALUE",$modelDeleteToken->random_value,PDO::PARAM_STR);
						//$command->bindValue(":VP_USERID",$modelDeleteToken->user_id,PDO::PARAM_STR);
						
						$command->bindParam(":VO_ERRCD",$this->vo_errcd,PDO::PARAM_INT,10);
						$command->bindParam(":VO_ERRMSG",$this->vo_errmsg,PDO::PARAM_STR,100);
			
						$command->execute();
						
						$modelDeleteToken->delete();
						}
					}
					
					$modelToken->save();
					$x++;
				}				
			}
			
			$transaction->commit();
			
			$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
			
			if ($modelToken->module =='LAPORAN_TRX_HARIAN'){
			$param 		  = '&ACC_TOKEN='.$modelToken->token_cd.'&ACC_USER_ID='.$this->vp_userid.'&TRX_DATE='.$this->trx_date;
			}
			else{
			$param 		  = '&ACC_TOKEN='.$modelToken->token_cd.'&ACC_USER_ID='.$this->vp_userid.'&RP_RANDOM_VALUE='.$this->vo_random_value;	
			}
			//if using date parameter
			if($bgn_date && $end_date)
			{
				$param 		  = '&ACC_TOKEN='.$modelToken->token_cd.'&ACC_USER_ID='.$this->vp_userid.'&RP_RANDOM_VALUE='.$this->vo_random_value.
							    '&BGN_DATE='.$bgn_date.'&END_DATE='.$end_date;
			}
			$url 		  = Constanta::URL.$this->rptname.$locale.$param;
			
		}catch(Exception $ex){
			$transaction->rollback();
			$this->vo_errcd = -888;
			$this->vo_errmsg = $ex->getMessage();
		}
		
		if($this->vo_errcd < 0)
			$this->addError('vo_errmsg', 'Error '.$this->vo_errcd.' '.$this->vo_errmsg);
		
		return $url;
	}

	public function showReport2()
	{
		self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		$param 		  = '&ACC_USER_ID='.$this->vp_userid.'&TRX_DATE='.$this->trx_date.'&APPROVED_STAT='.$this->approved_stat;
		$url 		  = Constanta::URL.$this->rptname.$locale.$param;	
		return $url;
	}
		public function showReportMkbd($update_date,$update_seq)
	{
		self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		$param 		  = '&ACC_USER_ID='.$this->vp_userid.'&TRX_DATE='.$this->trx_date.'&APPROVED_STAT='.$this->approved_stat
						.'&VD_51='.$this->p_vd51
						.'&VD_52='.$this->p_vd52
						.'&VD_53='.$this->p_vd53
						.'&VD_54='.$this->p_vd54
						.'&VD_55='.$this->p_vd55
						.'&VD_56='.$this->p_vd56
						.'&VD_57='.$this->p_vd57
						.'&VD_58='.$this->p_vd58
						.'&VD_59='.$this->p_vd59
						.'&VD_510A='.$this->p_vd510a
						.'&VD_510B='.$this->p_vd510b
						.'&VD_510C='.$this->p_vd510c
						.'&VD_510D='.$this->p_vd510d
						.'&VD_510E='.$this->p_vd510e
						.'&VD_510F='.$this->p_vd510f
						.'&VD_510G='.$this->p_vd510g
						.'&VD_510H='.$this->p_vd510h
						.'&VD_510I='.$this->p_vd510i
						.'&UPDATE_DATE='.$update_date
						.'&UPDATE_SEQ='.$update_seq;
		$url 		  = Constanta::URL.$this->rptname.$locale.$param.'&&__dpi=96&__format=pdf&__pageoverflow=0&__overwrite=false&#zoom=100';	
		return $url;
	}
		public function showReportStat($update_date,$update_seq)
	{
		self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		$param 		  = '&ACC_USER_ID='.$this->vp_userid.'&UPDATE_DATE='.$update_date.'&UPDATE_SEQ='.$update_seq;
		$url 		  = Constanta::URL.$this->rptname.$locale.$param;	
		return $url;
	}

	public function showLapMKBD($update_date,$update_seq)
	{	self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		$param 		  = '&ACC_USER_ID='.$this->vp_userid.'&UPDATE_DATE='.$update_date.'&UPDATE_SEQ='.$update_seq.'&TRX_DATE='.$this->trx_date.'&APPROVED_STAT='.$this->approved_stat;;
		$url 		  = Constanta::URL.$this->rptname.$locale.$param;	
		return $url;
	}
	
	public function showLapTradeConf($from_date, $to_date, $from_client, $to_client, $brch_cd, $rem_cd, $cl_type,$print_flg)
	{	self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		
		$param 		  = '&ACC_USER_ID='.$this->vp_userid.'&FROM_DATE='.$from_date.'&TO_DATE='.$to_date.
						'&FROM_CLIENT='.$from_client.'&TO_CLIENT='.$to_client.'&BRCH_CD='.$brch_cd.
						'&REM_CD='.$rem_cd.'&CLIENT_TYPE='.$cl_type.'&APPROVED_STAT='.$this->approved_stat.
						'&PRINT_FLG='.$print_flg;
					
		$url 		  = Constanta::URL.$this->rptname.$locale.$param;	
		return $url;
	}

	public function showLapTradeConfInbox($update_date, $update_seq, $client_cd)
	{	self::changeIP($this->rptname);
		$format = DAO::queryRowSql("SELECT dstr1 FROM MST_SYS_PARAM WHERE param_id = 'SYSTEM' AND param_cd1 = 'DECPOINT'");
		
			if($format['dstr1'] == ',')
			{
				$locale = '&__locale=in_ID';
			}
			else 
			{
				$locale = '&__locale=en_US';
			}
		
		$param 		  = '&UPDATE_DATE='.$update_date.'&UPDATE_SEQ='.$update_seq.'&CLIENT_CD='.$client_cd;
					
		$url 		  = Constanta::URL.$this->rptname.$locale.$param;	
		return $url;
	}
	public static function changeIP($rptname)
	{
		//[IN] Setting ip report in rptdesign
		$filename = Constanta::REPORT_PATH.$rptname;
		$lines = file($filename);
		$str=implode("",file($filename));
		
		$ip=Sysparam::model()->find("PARAM_ID='SYSTEM' AND param_cd1='REPORT' AND param_cd2='IP'")->dstr1;
		
			foreach ($lines as $line_num => $line) 
			{
				if(strpos(trim($line),"<property name=\"odaURL\">") !== false)
				{
					if(strpos(trim($line), $ip) === false)
					{
						$fp=fopen($filename,'w');
						$str=str_replace(trim($line),"<property name=\"odaURL\">jdbc:oracle:thin:@".$ip."</property>",$str);
						//now, save the file
						fwrite($fp,$str,strlen($str));
						fclose($fp);
						$flg=TRUE;
					}
				break;
				}			
			}
	}
	
}
?>
