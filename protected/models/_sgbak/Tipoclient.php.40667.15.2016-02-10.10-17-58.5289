<?php

/**
 * This is the model class for table "T_IPO_CLIENT".
 *
 * The followings are the available columns in table 'T_IPO_CLIENT':
 * @property string $client_cd
 * @property string $stk_cd
 * @property integer $fixed_qty
 * @property integer $pool_qty
 * @property integer $alloc_qty
 * @property string $cre_dt
 * @property string $user_id
 * @property string $upd_dt
 * @property string $upd_by
 * @property string $approved_dt
 * @property string $approved_by
 * @property string $approved_stat
 */
class Tipoclient extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;
	//AH: #END search (datetime || date)  additional comparison
	
	public $client_name;
	public $branch_code;
	public $refund;	
	public $fund_ipo;
	public $paid_ipo;
	public $amount;
	public $setor;
	public $bal_rdi;
	public $save_flg='N';
	
	public $no;
	public $nama_peap;
	public $jenis_id;
	public $no_id;
	public $nama_1;
	public $nama_2;
	public $alamat;
	public $kecamatan;
	public $kota;
	public $tanggal_lahir;
	public $warganegara;
	public $status;
	public $tanggal_id_expired;
	public $partisipan;
	public $rekening_efek;
	public $jum_pesan;
	public $subrek;
	public $status_pesan;
	
	public $fpps_cre_dt;
	
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
		return 'T_IPO_CLIENT';
	}
	
	public function getPrimaryKey()
	{
		return array('client_cd' => $this->client_cd,'stk_cd' => $this->stk_cd);
	}
	
	public function executeSp($exec_status,$old_client_cd,$old_stk_cd)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$query  = "CALL SP_T_IPO_CLIENT_UPD(
						:P_SEARCH_CLIENT_CD,
						:P_SEARCH_STK_CD,
						1,
						:P_CLIENT_CD,
						:P_STK_CD,
						:P_BRCH_CD,
						:P_FIXED_QTY,
						:P_POOL_QTY,
						:P_ALLOC_QTY,
						:P_BATCH,
						:P_IPO_PERC,
						TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),
						:P_USER_ID,
						TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),
						:P_UPD_BY,
						:P_UPD_STATUS,
						:P_IP_ADDRESS,
						:P_CANCEL_REASON,
						:P_ERROR_CODE,
						:P_ERROR_MSG)";			
						
			$command = $connection->createCommand($query);
			$command->bindValue(":P_SEARCH_CLIENT_CD",$old_client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_SEARCH_STK_CD",$old_stk_cd,PDO::PARAM_STR);
			$command->bindValue(":P_CLIENT_CD",$this->client_cd,PDO::PARAM_STR);
			$command->bindValue(":P_STK_CD",$this->stk_cd,PDO::PARAM_STR);
			$command->bindValue(":P_BRCH_CD",trim($this->brch_cd),PDO::PARAM_STR);
			$command->bindValue(":P_FIXED_QTY",$this->fixed_qty,PDO::PARAM_STR);
			$command->bindValue(":P_POOL_QTY",$this->pool_qty,PDO::PARAM_STR);
			$command->bindValue(":P_ALLOC_QTY",$this->alloc_qty,PDO::PARAM_STR);
			$command->bindValue(":P_BATCH",$this->batch,PDO::PARAM_STR);
			$command->bindValue(":P_IPO_PERC",$this->ipo_perc,PDO::PARAM_STR);
			$command->bindValue(":P_CRE_DT",$this->cre_dt,PDO::PARAM_STR);
			$command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_DT",$this->upd_dt,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_BY",$this->upd_by,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_STATUS",$exec_status,PDO::PARAM_STR);
			$command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
			$command->bindValue(":P_CANCEL_REASON",$this->cancel_reason,PDO::PARAM_STR);

			$command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,1000);

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

	public function rules()
	{
		return array(
		
			array('fpps_cre_dt', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			array('bal_rdi,fund_ipo,fund_ipo,setor,amount,fixed_qty, pool_qty, alloc_qty, fund_bal', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('stk_cd,client_cd,brch_cd,fixed_qty,pool_qty,alloc_qty,ipo_perc','required','except'=>'ipoentry'),
			
			array('fixed_qty','checkQuantity'),
			
			array('fixed_qty, pool_qty, alloc_qty', 'numerical', 'integerOnly'=>true),
			array('fpps_num', 'length', 'max'=>8),
			array('user_id, batch', 'length', 'max'=>10),
			array('client_name,save_flg,paid_ipo,refund,cre_dt,upd_dt,upd_by,approved_dt,approved_by,approved_stat, fpps_flg, subrek', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('client_cd, stk_cd, brch_cd, ipo_perc, fpps_num, fpps_flg, fund_bal, fpps_cre_dt, batch, subrek', 'safe', 'on'=>'search'),
		);
	}
	public function checkQuantity()
	{
		if($this->fixed_qty == 0 && $this->pool_qty == 0)
		{
			$this->addError('fixed_qty', 'Salah satu dari fixed/pool qty harus lebih dari nol');
			/*$this->addErrors
			(
				array
				(
					'fixed_qty'=>'Salah satu dari fixed/pool qty harus lebih dari nol',
					'pool_qty'=>'Salah satu dari fixed/pool qty harus lebih dari nol',
				)
			);*/
		}
	}

	public function relations()
	{
		return array(
			'client' => array(self::BELONGS_TO,'Client','client_cd'),
			'tpee' => array(self::BELONGS_TO,'Tpee','stk_cd'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'client_cd' => 'Client Code',
			'stk_cd' => 'Stock Code',
			'fixed_qty' => 'Fixed Qty',
			'pool_qty' => 'Pooling Qty',
			'alloc_qty' => 'Pooling yang Dipenuhi',
			'cre_dt' => 'Cre Date',
			'user_id' => 'User',
			'brch_cd' => 'Branch Code',
			'batch' => 'Batch',
			'subrek' => 'Subrek',
			
			'client_name' => 'Client Name',
			'branch_code' => 'Branch Code',
			'fpps_cre_dt' => 'FPPS Date',
			'client.client_name' => 'Name',
			'ipo_perc' => 'IPO Fee (%)'
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		
		$criteria->join = 'JOIN T_PEE p ON p.stk_cd = t.stk_cd ';
		$criteria->join .= 'JOIN MST_CLIENT c ON c.client_cd = t.client_cd';
		
		$criteria->compare('t.client_cd',$this->client_cd,true);
		$criteria->compare('t.stk_cd',$this->stk_cd,true);
		$criteria->compare('fixed_qty',$this->fixed_qty);
		$criteria->compare('pool_qty',$this->pool_qty);
		$criteria->compare('alloc_qty',$this->alloc_qty);
		$criteria->compare('brch_cd',$this->brch_cd,true);
		$criteria->compare('t.approved_stat',$this->approved_stat);

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".$this->cre_dt_date."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".$this->cre_dt_month."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".$this->cre_dt_year."%'");		$criteria->compare('user_id',$this->user_id,true);
		$criteria->addCondition("p.DISTRIB_DT_TO >= TRUNC(SYSDATE)");
				
		$sort = new CSort;
		
		$sort->defaultOrder = 'STK_CD, BRCH_CD, CLIENT_CD';
		/*$sort->attributes = array(
			'stk_cd',
			'client_cd',
		);*/

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => $sort,
		));
	}
}