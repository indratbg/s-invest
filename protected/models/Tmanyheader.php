<?php

/**
 * This is the model class for table "T_MANY_HEADER".
 *
 * The followings are the available columns in table 'T_MANY_HEADER':
 * @property string $update_date
 * @property string $menu_name
 * @property integer $update_seq
 * @property string $status
 * @property string $user_id
 * @property string $ip_address
 * @property string $approved_status
 * @property string $approved_user_id
 * @property string $approved_date
 * @property string $approved_ip_address
 * @property string $reject_reason
 * @property string $cancel_reason
 */
class Tmanyheader extends AActiveRecord
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $update_date_date;
	public $update_date_month;
	public $update_date_year;

	public $approved_date_date;
	public $approved_date_month;
	public $approved_date_year;
	//AH: #END search (datetime || date)  additional comparison
	
	public $approved_user_id;
	public $approved_ip_address;
	public $error_code = -999;
	public $error_msg  = '';
	
	/*
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	*/
	
	public function primaryKey()
	{
		return 'update_seq';
	}
	
	public function getPrimaryKey()
	{
		return $this->update_seq;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'T_MANY_HEADER';
	}

	public function rules()
	{
		return array(
		
			array('update_date, approved_date', 'application.components.validator.ADatePickerSwitcherValidator'),
			array('update_seq', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('update_date, menu_name, update_seq', 'required','on'=>'insert,update,reject'),
			array('reject_reason','required','on'=>'reject, rejectchecked'),
			array('cancel_reason','required','on'=>'cancel'),
			
			array('update_seq', 'numerical', 'integerOnly'=>true),
			array('menu_name', 'length', 'max'=>50),
			array('user_id, approved_user_id', 'length', 'max'=>10),
			array('approved_status', 'length', 'max'=>1),
			array('ip_address, approved_ip_address', 'length', 'max'=>15),
			array('reject_reason, cancel_reason', 'length', 'max'=>200),
			array('approved_date', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('status, update_date, menu_name, update_seq, user_id, ip_address, approved_status, approved_user_id, approved_date, approved_ip_address, reject_reason, cancel_reason,update_date_date,update_date_month,update_date_year,approved_date_date,approved_date_month,approved_date_year', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'update_date' => 'Update Date',
			'menu_name' => 'Menu Name',
			'update_seq' => 'Update Seq',
			'user_id' => 'Request By',
			'ip_address' => 'Ip Address',
			'approved_status' => 'Approved Status',
			'approved_user_id' => 'Approved By',
			'approved_date' => 'Approved Date',
			'approved_ip_address' => 'Approved Ip Address',
			'reject_reason' => 'Reject Reason',
			'cancel_reason' => 'Cancel Reason',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;

		if(!empty($this->update_date_date))
			$criteria->addCondition("TO_CHAR(t.update_date,'DD') LIKE '%".$this->update_date_date."%'");
		if(!empty($this->update_date_month))
			$criteria->addCondition("TO_CHAR(t.update_date,'MM') LIKE '%".$this->update_date_month."%'");
		if(!empty($this->update_date_year))
			$criteria->addCondition("TO_CHAR(t.update_date,'YYYY') LIKE '%".$this->update_date_year."%'");		
			
		if(is_array($this->menu_name))
		{
			$criteria->addCondition("menu_name IN (".implode(',',$this->menu_name).")");
		}
		else 
		{
			$this->menu_name?$criteria->addCondition("menu_name = '$this->menu_name'"):'';
		}
		/*
		$criteria->compare('update_seq',$this->update_seq);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('approved_status',$this->approved_status,true);
		$criteria->compare('approved_user_id',$this->approved_user_id,true);
		$criteria->compare('status',$this->status,true);
		*/
		if($this->update_seq)$criteria->addCondition("update_seq = $this->update_seq");
		if($this->user_id)$criteria->addCondition("user_id = '$this->user_id'");
		if($this->ip_address)$criteria->addCondition("ip_address = '$this->ip_address'");
		$criteria->compare('approved_status',$this->approved_status,true);
		if($this->approved_user_id)$criteria->addCondition("approved_user_id = '$this->approved_user_id'");
		if($this->status)$criteria->addCondition("status = '$this->status'");

		if(!empty($this->approved_date_date))
			$criteria->addCondition("TO_CHAR(t.approved_date,'DD') LIKE '%".$this->approved_date_date."%'");
		if(!empty($this->approved_date_month))
			$criteria->addCondition("TO_CHAR(t.approved_date,'MM') LIKE '%".$this->approved_date_month."%'");
		if(!empty($this->approved_date_year))
			$criteria->addCondition("TO_CHAR(t.approved_date,'YYYY') LIKE '%".$this->approved_date_year."%'");
		//$criteria->compare('reject_reason',$this->reject_reason,true);
		//$criteria->compare('cancel_reason',$this->cancel_reason,true);

		$sort = new CSort;
		$sort->defaultOrder='approved_date desc, update_date desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}

	private function logRecord()
	{
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		
		$this->approved_ip_address = $ip;
		$this->approved_user_id    = Yii::app()->user->id;
	}
	
	public function approve($sp_name='SP_T_MANY_APPROVE')
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,4000);
			
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveRpt($sp_name='SP_T_MANY_APPROVE_RPT')
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveLaptrxharian($sp_name='Sp_Lap_trx_Harian_approve')
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function approveLaporan($table_name,$sp_name='SP_LAP_APPROVE')
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						:P_TABLE_NAME,
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$table_name,PDO::PARAM_STR);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function rejectLaporan($reject_reason,$table_name,$sp_name='SP_LAP_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL ".$sp_name."(
						:P_TABLE_NAME,	
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->dbrpt->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$table_name,PDO::PARAM_STR);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	} 
	public function approveRincianporto($sp_name='Sp_rincian_porto_approve')
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
		public function rejectRincianPorto($reject_reason,$sp_name='SP_RINCIAN_PORTO_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->dbrpt->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	} 
	public function approveLapmkbdreport($sp_name='SP_LAP_MKBD_APPROVE')
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL ".$sp_name."(
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function approveGlJournal()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_Jvchh_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,2000);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveTcCepatAvgPrice()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_TC_CEPAT_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveBondTrx()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_BOND_TRX_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	
	public function approveContractCorrection()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_CONTRACT_CORRECTION_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveContractavgprice()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_CONTRACT_AVGPRICE_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	
	public function approveCancelcontravgprice()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_CANCEL_AVGPRICE_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
		
	public function approveClientClosing()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_CLIENT_CLOSING_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function approveTrekstrx()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query  = "CALL SP_T_REKS_TRX_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
						:P_APPROVED_IP_ADDRESS,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	}
	
	public function approveTfundmovement()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query  = "CALL SP_T_FUND_MOVEMENT_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
						:P_APPROVED_IP_ADDRESS,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	}
	public function approveTbankmutation()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query  = "CALL SP_MUTASI_RDI_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
						:P_APPROVED_IP_ADDRESS,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	}
	
	public function approveGentradingref($tc_date, $client_cd, $mode, $tc_id)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_GEN_TRADING_REF_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    TO_DATE(:P_TRX_DATE,'YYYY/MM/DD HH24:MI:SS'),
					    :P_CLIENT_CD,
					    :P_MODE,
					    :P_TC_ID,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindValue(":P_TRX_DATE",$tc_date,PDO::PARAM_STR);
			$command->bindValue(":P_CLIENT_CD",$client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_MODE",$mode,PDO::PARAM_STR);
			$command->bindValue(":P_TC_ID",$tc_id,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}

	public function rejectGentradingref($reject_reason)
	{
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL SP_GEN_TRADING_REF_REJECT(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->db->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			$this->error_code  = -999;
			$this->error_msg   = 'Procedure Exception '.$ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function approveTradeConf($tc_date, $client_cd, $mode, $tc_id)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_TRADE_CONF_APPROVE(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
					    :P_APPROVED_IP_ADDRESS,
					    TO_DATE(:P_TRX_DATE,'YYYY/MM/DD HH24:MI:SS'),
					    :P_CLIENT_CD,
					    :P_MODE,
					    :P_TC_ID,
					    :P_ERROR_CODE,
					    :P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			
			$command->bindValue(":P_TRX_DATE",$tc_date,PDO::PARAM_STR);
			$command->bindValue(":P_CLIENT_CD",$client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_MODE",$mode,PDO::PARAM_STR);
			$command->bindValue(":P_TC_ID",$tc_id,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			
			$command->execute();
			$transaction->commit();
			
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function rejectTradeConf($reject_reason)
	{
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL SP_TRADE_CONF_REJECT(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->db->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			$this->error_code  = -999;
			$this->error_msg   = 'Procedure Exception '.$ex->getMessage();
		}
		
		return $this->error_code;
	}
	public function rejectBondTrx($reject_reason)
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL Sp_T_Bond_Trx_Reject(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->db->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			$this->error_code  = -999;
			$this->error_msg   = 'Procedure Exception '.$ex->getMessage();
		}
		
		return $this->error_code;
	}

	public function rejectLapmkbd($reject_reason,$sp_name='SP_LAP_MKBD_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->dbrpt->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	} 
	

	public function reject($reject_reason,$sp_name='SP_T_MANY_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->db->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	} 
	
	public function rejectRpt($reject_reason,$sp_name='SP_T_MANY_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL ".$sp_name."(
						:P_MENU_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_REJECT_USER_ID,
						:P_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			
			$command = Yii::app()->dbrpt->createCommand($query);
			$command->bindValue(":P_MENU_NAME",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	} 
public function rejectUpdEntry($reject_reason,$sp_name='SP_T_MANY_REJECT')
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		$connection  = Yii::app()->db;
		
			$query   = "UPDATE T_many_HEADER
						SET approved_status = 'R',
						approved_user_id = :p_reject_user_id,
						approved_date = SYSDATE,
						approved_ip_address = :p_reject_ip_address,
						reject_reason = :p_reject_reason
						WHERE menu_name = :p_menu_name
						AND update_date = :p_update_date
						AND update_seq = :p_update_seq
						AND approved_status = 'E'";
			
			$command = $connection->createCommand($query);
			$command->bindValue(":p_menu_name",$this->menu_name,PDO::PARAM_STR);
			$command->bindValue(":p_update_date",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":p_update_seq",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":p_reject_user_id",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":p_reject_reason",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":p_reject_ip_address",$this->ip_address,PDO::PARAM_STR);
			$command->execute();
	} 

/* [IN] Approve voucher sekaligus
*/
	public function approveVoucherAll($update_seq_array)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		$num_bind = 'NUM_ARRAY(';
		$x = 0;
		foreach($update_seq_array as $value)
		{
			if($x > 0)$num_bind .= ',';
			$num_bind .= "'".$value."'";
			$x++;
		}
		
		$num_bind .= ')';
		
		try{
			$this->logRecord();
			$query   = "CALL SP_APPROVE_VOUCHER_ALL( $num_bind,
													:P_APPROVED_USER_ID,
												    :P_APPROVED_IP_ADDRESS,
												    :P_ERROR_CODE,
												    :P_ERROR_MSG)";		
			$command = $connection->createCommand($query);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,4000);
			
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}

	public function approveVoucherAllRDI()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_APPROVE_VOUCHER_RDI_ALL( :P_APPROVED_USER_ID,
												    :P_APPROVED_IP_ADDRESS,
												    :P_ERROR_CODE,
												    :P_ERROR_MSG)";		
			$command = $connection->createCommand($query);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,4000);
			
			$command->execute();
			$transaction->commit();
		}catch(Exception $ex){
			$transaction->rollback();
			if($this->error_code == -999)
				$this->error_msg = $ex->getMessage();
		}
		
		return $this->error_code;
	}


	/*
	public function approveVoucherAll_new($update_seq_array)
	{
		
		$num_bind = 'NUM_ARRAY(';
		$x = 0;
		foreach($update_seq_array as $value)
		{
			if($x > 0)$num_bind .= ',';
			$num_bind .= "'".$value."'";
			$x++;
		}
		
		$num_bind .= ')';
		
		
		$database = '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.8.70)(PORT = 1521)))(CONNECT_DATA=(SID=orcllim)))';	
		$connection = oci_connect('ipnextg', '123', $database);
		
		if (!$connection) {
		   $m = oci_error();
		   echo $m['message'], "\n";
		   exit;
		}
		else 
		{
			$sql = "BEGIN SP_APPROVE_VOUCHER_ALL( $num_bind,
													:P_APPROVED_USER_ID,
												    :P_APPROVED_IP_ADDRESS,
												    :P_ERROR_CODE,
												    :P_ERROR_MSG); END;";
			
			$query = oci_parse($connection, $sql);
			oci_bind_by_name($query,':P_APPROVED_USER_ID',$this->approved_user_id);
			oci_bind_by_name($query,':P_APPROVED_IP_ADDRESS',$this->approved_ip_address);
			oci_bind_by_name($query,':P_ERROR_CODE',$this->error_code,10);
			oci_bind_by_name($query,':P_ERROR_MSG',$this->error_msg,200);
		   	$res = oci_execute($query, OCI_DEFAULT);
		  // oci_commit($connection);
		   
		  // $hasil = oci_fetch_object($query);
		
		 return $this->error_code;
		   
		}
		
	}*/

}