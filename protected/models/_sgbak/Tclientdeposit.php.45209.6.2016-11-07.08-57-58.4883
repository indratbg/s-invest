<?php

/**
 * This is the model class for table "T_CLIENT_DEPOSIT".
 *
 * The followings are the available columns in table 'T_CLIENT_DEPOSIT':
 * @property string $trx_date
 * @property string $client_cd
 * @property double $debit
 * @property double $credit
 * @property string $doc_num
 * @property string $cre_dt
 * @property string $user_id
 * @property string $upd_dt
 * @property string $upd_by
 * @property string $approved_dt
 * @property string $approved_by
 * @property string $approved_stat
 * @property string $reversal_jur
 * @property string $no_perjanjian
 * @property string $doc_type
 * @property integer $tal_id
 * @property string $folder_cd
 */
class Tclientdeposit extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $trx_date_date;
	public $trx_date_month;
	public $trx_date_year;

	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;

	public $upd_dt_date;
	public $upd_dt_month;
	public $upd_dt_year;

	public $approved_dt_date;
	public $approved_dt_month;
	public $approved_dt_year;
	public $amount;
	public $client_name;
	public $mvmt_type;
	public $save_flg='N';
	public $cancel_flg = 'N';
	public $doc_type;
	public $old_trx_date;
	public $old_client_cd;
	public $old_doc_num;
	public $text;
	
	//AH: #END search (datetime || date)  additional comparison
	
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'T_CLIENT_DEPOSIT';
	}

	public function rules()
	{
		return array(
		
			array('old_trx_date,trx_date, approved_dt', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('debit, credit, tal_id', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('trx_date, client_cd', 'required'),
			array('tal_id', 'numerical', 'integerOnly'=>true),
			array('debit, credit', 'numerical'),
			array('client_cd', 'length', 'max'=>12),
			array('doc_num, reversal_jur', 'length', 'max'=>17),
			array('user_id, upd_by, approved_by, folder_cd', 'length', 'max'=>10),
			array('approved_stat, doc_type', 'length', 'max'=>1),
			array('no_perjanjian', 'length', 'max'=>20),
			array('old_client_cd,old_doc_num,old_trx_date,doc_type,cancel_flg,save_flg,mvmt_type,client_name,amount,cre_dt, upd_dt, approved_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('trx_date, client_cd, debit, credit, doc_num, cre_dt, user_id, upd_dt, upd_by, approved_dt, approved_by, approved_stat, reversal_jur, no_perjanjian, doc_type, tal_id, folder_cd,trx_date_date,trx_date_month,trx_date_year,cre_dt_date,cre_dt_month,cre_dt_year,upd_dt_date,upd_dt_month,upd_dt_year,approved_dt_date,approved_dt_month,approved_dt_year', 'safe', 'on'=>'search'),
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
			'trx_date' => 'Trx Date',
			'client_cd' => 'Client Code',
			'debit' => 'Debit',
			'credit' => 'Credit',
			'doc_num' => 'Doc Num',
			'cre_dt' => 'Cre Date',
			'user_id' => 'User',
			'upd_dt' => 'Upd Date',
			'upd_by' => 'Upd By',
			'approved_dt' => 'Approved Date',
			'approved_by' => 'Approved By',
			'approved_stat' => 'Approved Stat',
			'reversal_jur' => 'Reversal Jur',
			'no_perjanjian' => 'No Perjanjian',
			'doc_type' => 'Doc Type',
			'tal_id' => 'Tal',
			'folder_cd' => 'Folder Code',
			'jml_deposit'=>'Jumlah Deposit'
		);
	}

public function executeSp($exec_status,$old_trx_date,$old_client_cd,$old_doc_num,$record_seq)
	{ 
		$connection  = Yii::app()->db;
				
		try{
			$query  = "CALL  SP_T_CLIENT_DEPOSIT_UPD(TO_DATE(:P_SEARCH_TRX_DATE,'YYYY-MM-DD'),
													:P_SEARCH_CLIENT_CD,
													:P_SEARCH_DOC_NUM,
													TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
													:P_CLIENT_CD,
													:P_DEBIT,
													:P_CREDIT,
													:P_DOC_NUM,
													TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),
													:P_USER_ID,
													TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),
													:P_UPD_BY,
													:P_REVERSAL_JUR,
													:P_NO_PERJANJIAN,
													:P_DOC_TYPE,
													:P_TAL_ID,
													:P_FOLDER_CD,
													:P_UPD_STATUS,
													:p_ip_address,
													:p_cancel_reason,
													TO_DATE(:p_update_date,'YYYY-MM-DD HH24:MI:SS'),
													:p_update_seq,
													:p_record_seq,
													:p_error_code,
													:p_error_msg)";
			
			$command = $connection->createCommand($query);
			$command->bindValue(":P_SEARCH_TRX_DATE",$old_trx_date,PDO::PARAM_STR);
			$command->bindValue(":P_SEARCH_CLIENT_CD",$old_client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_SEARCH_DOC_NUM",$old_doc_num,PDO::PARAM_STR);
			$command->bindValue(":P_TRX_DATE",$this->trx_date,PDO::PARAM_STR);
			$command->bindValue(":P_CLIENT_CD",$this->client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_DEBIT",$this->debit,PDO::PARAM_STR);
			$command->bindValue(":P_CREDIT",$this->credit,PDO::PARAM_STR);
			$command->bindValue(":P_DOC_NUM",$this->doc_num,PDO::PARAM_STR);
			$command->bindValue(":P_CRE_DT",$this->cre_dt,PDO::PARAM_STR);
			$command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_DT",$this->upd_dt,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_BY",$this->upd_by,PDO::PARAM_STR);
			$command->bindValue(":P_REVERSAL_JUR",$this->reversal_jur,PDO::PARAM_STR);
			$command->bindValue(":P_NO_PERJANJIAN",$this->no_perjanjian,PDO::PARAM_STR);
			$command->bindValue(":P_DOC_TYPE",$this->doc_type,PDO::PARAM_STR);
			$command->bindValue(":P_TAL_ID",$this->tal_id,PDO::PARAM_STR);
			$command->bindValue(":P_FOLDER_CD",$this->folder_cd,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_STATUS",$exec_status,PDO::PARAM_STR);								
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			$command->bindValue(":P_CANCEL_REASON",$this->cancel_reason,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR);
			$command->bindValue(":P_UPDATE_SEQ",$this->update_seq,PDO::PARAM_STR);
			$command->bindValue(":P_RECORD_SEQ",$record_seq,PDO::PARAM_STR);	
			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,1000);

			$command->execute();
			
			//Commit baru akan dijalankan saat semua transaksi INSERT sukses
			
		}catch(Exception $ex){
			if($this->error_code = -999)
				$this->error_msg = $ex->getMessage();
		}
		
		if($this->error_code < 0)
			$this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
		
		return $this->error_code;
	}
	
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->select = ' t.client_cd, a.client_name,sum(credit - debit) amount';
		$criteria->join = 'join mst_client a on t.client_cd = a.client_cd';
		//$criteria->condition ='t.approved_stat = \'A\'';
		$criteria->group ='t.client_cd,a.client_name';
		$criteria->having = 'sum(credit-debit) >0';
		$criteria->order ='1';
		
		if(!empty($this->trx_date_date))
			$criteria->addCondition("TO_CHAR(t.trx_date,'DD') LIKE '%".$this->trx_date_date."%'");
		if(!empty($this->trx_date_month))
			$criteria->addCondition("TO_CHAR(t.trx_date,'MM') LIKE '%".$this->trx_date_month."%'");
		if(!empty($this->trx_date_year))
			$criteria->addCondition("TO_CHAR(t.trx_date,'YYYY') LIKE '%".$this->trx_date_year."%'");	
			$criteria->compare('lower(t.client_cd)',strtolower($this->client_cd),true);
		$criteria->compare('t.debit',$this->debit);
		$criteria->compare('t.credit',$this->credit);
		$criteria->compare('t.doc_num',$this->doc_num,true);
		$criteria->compare('lower(a.client_name)',strtolower($this->client_name),true);

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".$this->cre_dt_date."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".$this->cre_dt_month."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".$this->cre_dt_year."%'");		
			

		if(!empty($this->upd_dt_date))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'DD') LIKE '%".$this->upd_dt_date."%'");
		if(!empty($this->upd_dt_month))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'MM') LIKE '%".$this->upd_dt_month."%'");
		if(!empty($this->upd_dt_year))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'YYYY') LIKE '%".$this->upd_dt_year."%'");		$criteria->compare('t.upd_by',$this->upd_by,true);

		if(!empty($this->approved_dt_date))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'DD') LIKE '%".$this->approved_dt_date."%'");
		if(!empty($this->approved_dt_month))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'MM') LIKE '%".$this->approved_dt_month."%'");
		if(!empty($this->approved_dt_year))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'YYYY') LIKE '%".$this->approved_dt_year."%'");		$criteria->compare('t.approved_by',$this->approved_by,true);
		$criteria->compare('t.approved_stat',$this->approved_stat,true);
		$criteria->compare('t.reversal_jur',$this->reversal_jur,true);
		$criteria->compare('t.no_perjanjian',$this->no_perjanjian,true);
		$criteria->compare('t.doc_type',$this->doc_type,true);
		$criteria->compare('tal_id',$this->tal_id);
		$criteria->compare('t.folder_cd',$this->folder_cd,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}