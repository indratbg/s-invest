<?php

/**
 * This is the model class for table "T_TEMP_HEADER".
 *
 * The followings are the available columns in table 'T_TEMP_HEADER':
 * @property string $update_date
 * @property string $table_name
 * @property integer $update_seq
 * @property string $table_rowid
 * @property string $status
 * @property string $user_id
 * @property string $ip_address
 * @property string $approved_status
 * @property string $approved_user_id
 * @property string $approved_date
 * @property string $reject_reason
 */
class Ttempheader extends AActiveRecord
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
	public $error_code = -1;
	public $error_msg  = '';
	
	public $page_size = 10;
	
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
		return 'T_TEMP_HEADER';
	}

	public function rules()
	{
		return array(
			array('update_date, approved_date', 'application.components.validator.ADatePickerSwitcherValidator'),
			array('update_seq', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('update_date, table_name, update_seq', 'required','on'=>'insert,update,reject'),
			array('reject_reason','required','on'=>'reject, rejectchecked'),
			array('cancel_reason','required','on'=>'cancel'),
			
			array('update_seq', 'numerical', 'integerOnly'=>true),
			array('table_name', 'length', 'max'=>40),
			array('user_id, approved_user_id', 'length', 'max'=>10),
			array('status, approved_status', 'length', 'max'=>1),
			array('ip_address', 'length', 'max'=>15),
			array('reject_reason', 'length', 'max'=>200),
			array('approved_date', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('update_date, table_name, update_seq, table_rowid, status, user_id, ip_address, approved_status, approved_user_id, approved_date, reject_reason,update_date_date,update_date_month,update_date_year,approved_date_date,approved_date_month,approved_date_year', 'safe', 'on'=>'search'),
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
			'table_name' => 'Table Name',
			'update_seq' => 'Update Seq',
			'table_rowid' => 'Table Rowid',
			'status' => 'Status',
			'user_id' => 'Request By',
			'ip_address' => 'Ip Address',
			'approved_status' => 'Approved Status',
			'approved_user_id' => 'Approved By',
			'approved_date' => 'Approved Date',
			'reject_reason' => 'Reject Reason',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;

		if(!empty($this->update_date_date))
			$criteria->addCondition("TO_CHAR(t.update_date,'DD') LIKE '%".($this->update_date_date)."%'");
		if(!empty($this->update_date_month))
			$criteria->addCondition("TO_CHAR(t.update_date,'MM') LIKE '%".($this->update_date_month)."%'");
		if(!empty($this->update_date_year))
			$criteria->addCondition("TO_CHAR(t.update_date,'YYYY') LIKE '%".($this->update_date_year)."%'");		
		/*
		$criteria->compare('update_seq',$this->update_seq);
		$criteria->compare('table_name',$this->table_name);
		$criteria->compare('table_rowid',$this->table_rowid,true);
		$criteria->compare('status',$this->status,true);
		*/
		$this->update_seq?$criteria->addCondition("update_seq = $this->update_seq"):'';
		$this->table_name?$criteria->addCondition("table_name = '$this->table_name'"):'';
		$this->table_rowid?$criteria->addCondition("table_rowid = '$this->table_rowid'"):'';
		//$this->status?$criteria->addCondition("status = '$this->status'"):'';
		$criteria->compare('status',$this->status,true);
		//$criteria->compare('lower(user_id)',strtolower($this->user_id),true);
		if(!empty($this->user_id))
			$criteria->addCondition("UPPER(user_id) LIKE UPPER('".$this->user_id."%')");
		$this->ip_address?$criteria->addCondition("ip_address = '$this->ip_address'"):'';
		//$this->approved_status?$criteria->addCondition("approved_status = '$this->approved_status'"):'';
		$criteria->compare('approved_status',$this->approved_status,true);
		$this->approved_user_id?$criteria->addCondition("approved_user_id = '$this->approved_user_id'"):'';

		if(!empty($this->approved_date_date))
			$criteria->addCondition("TO_CHAR(t.approved_date,'DD') LIKE '%".($this->approved_date_date)."%'");
		if(!empty($this->approved_date_month))
			$criteria->addCondition("TO_CHAR(t.approved_date,'MM') LIKE '%".($this->approved_date_month)."%'");
		if(!empty($this->approved_date_year))
			$criteria->addCondition("TO_CHAR(t.approved_date,'YYYY') LIKE '%".($this->approved_date_year)."%'");		
		
		//$criteria->compare('reject_reason',$this->reject_reason,true);

		$sort = new CSort;
		$sort->defaultOrder='approved_date desc, update_date desc';
		
		$page = new CPagination;
		$page->pageSize=$this->page_size;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>$page,
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
	
	public function approve()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_TEMP_APPROVE(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_APPROVED_USER_ID,
											   :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	}

	public function approveTcontracts()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$this->logRecord();
			$query   = "CALL SP_CONTR_INTRABROKER_APPROVE(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_APPROVED_USER_ID,
											   :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	}  
	
	
	public function cancelExistingTcontracts()
		{
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			
			try{
				$this->logRecord();
				$query   = "CALL SP_CONTR_INTRABROKER_CANCEL(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDATE_SEQ,:P_APPROVED_USER_ID,
												   :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
						
				$command = $connection->createCommand($query);
				$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
				$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
				$command->bindValue(":P_UPDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
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
		}
	

	public function approveBranch()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query   = "CALL SP_MST_BRANCH_APPROVE(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_APPROVED_USER_ID,
											       :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	}
	
	public function approveBond()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query   = "CALL SP_MST_BOND_APPROVE(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_APPROVED_USER_ID,
											       :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	}
	
	public function approveClientClosing()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query   = "CALL SP_T_CLIENT_CLOSING_APPROVE(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_APPROVED_USER_ID,
											       		 :P_APPROVED_IP_ADDRESS,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	}
	public function approveTrekstrx()
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query  = "CALL SP_T_REKS_TRX_APPROVE(
						:P_TABLE_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
						:P_APPROVED_IP_ADDRESS,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
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
	public function approveSuspend($reject_reason)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$this->logRecord();
			$query  = "CALL SP_Suspend_APPROVE(
						:P_TABLE_NAME,
						TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPDDATE_SEQ,
						:P_APPROVED_USER_ID,
						:P_APPROVED_IP_ADDRESS,
						:P_REJECT_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";
			$command = $connection->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_APPROVED_IP_ADDRESS",$this->approved_ip_address,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$reject_reason,PDO::PARAM_STR);
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
	
	public function reject($reject_reason)
	{
		// AH: setting model value 
		$this->approved_user_id = Yii::app()->user->id;
		$this->reject_reason 	= $reject_reason;
		 	
		$ip = Yii::app()->request->userHostAddress;
		if($ip=="::1")
			$ip = '127.0.0.1';
		$this->ip_address = $ip;
		
		try{
			$query   = "CALL Sp_T_Temp_Reject(:P_TABLE_NAME,TO_DATE(:P_UPDATE_DATE,'YYYY-MM-DD HH24:MI:SS'),:P_UPDDATE_SEQ,:P_REJECT_USER_ID,
											  :P_IP_ADDRESS,:P_REJECT_REASON,:P_ERROR_CODE,:P_ERROR_MSG)";
			
			$command = Yii::app()->db->createCommand($query);
			$command->bindValue(":P_TABLE_NAME",$this->table_name,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_USER_ID",$this->approved_user_id,PDO::PARAM_STR);
			$command->bindValue(":P_REJECT_REASON",$this->reject_reason,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_STR,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,200);
			$command->execute();
		}catch(Exception $ex){
			//$this->error_code  = -999;
			//$this->error_msg   = 'Procedure Exception '.$ex->getMessage();
		}
	}
}