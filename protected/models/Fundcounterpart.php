<?php

/**
 * This is the model class for table "MST_FUND_COUNTERPART".
 *
 * The followings are the available columns in table 'MST_FUND_COUNTERPART':
 * @property string $fund_code
 * @property string $fund_name
 * @property string $im_code
 * @property string $counterpart
 * @property string $fund_type
 * @property string $portfolio_id
 * @property string $seller_tax_id
 * @property string $cre_by
 * @property string $cre_dt
 * @property string $upd_by
 * @property string $upd_dt
 */
class Fundcounterpart extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;

	public $upd_dt_date;
	public $upd_dt_month;
	public $upd_dt_year;
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
		return 'MST_FUND_COUNTERPART';
	}

	public function rules()
	{
		return array(
			
			array('fund_name, im_code, counterpart, fund_code', 'required'),
			array('fund_name', 'length', 'max'=>100),
			array('im_code', 'length', 'max'=>6),
			array('counterpart', 'length', 'max'=>12),
			array('fund_type', 'length', 'max'=>4),
			array('portfolio_id', 'length', 'max'=>10),
			array('seller_tax_id', 'length', 'max'=>20),
			array('cre_by, upd_by', 'length', 'max'=>8),
			array('cre_dt, upd_dt, fund_code', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('fund_code, fund_name, im_code, counterpart, fund_type, portfolio_id, seller_tax_id, cre_by, cre_dt, upd_by, upd_dt,cre_dt_date,cre_dt_month,cre_dt_year,upd_dt_date,upd_dt_month,upd_dt_year', 'safe', 'on'=>'search'),
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
			'fund_code' => 'Fund Code',
			'fund_name' => 'Fund Name',
			'im_code' => 'Im Code',
			'counterpart' => 'Counter Party',
			'fund_type' => 'Fund Type',
			'portfolio_id' => 'Portfolio',
			'seller_tax_id' => 'Seller Tax',
			'cre_by' => 'Created By',
			'cre_dt' => 'Created Date',
			'upd_by' => 'Updated By',
			'upd_dt' => 'Updated Date',
		);
	}
    public function executeSp($exec_status,$old_fund_code)
    { 
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction(); 
                
        try{
            $query  = "CALL  SP_MST_FUND_COUNTERPART_UPD (  :P_SEARCH_FUND_CODE,
                                                            :P_FUND_CODE,
                                                            :P_FUND_NAME,
                                                            :P_IM_CODE,
                                                            :P_COUNTERPART,
                                                            :P_FUND_TYPE,
                                                            :P_PORTFOLIO_ID,
                                                            :P_SELLER_TAX_ID,
                                                            :P_CRE_BY,
                                                            TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),
                                                            :P_UPD_BY,
                                                            TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),
                                                            :P_UPD_STATUS,
                                                            :p_ip_address,
                                                            :p_cancel_reason,
                                                            :p_error_code,
                                                            :p_error_msg) ";
            
            $command = $connection->createCommand($query);
            $command->bindValue(":P_SEARCH_FUND_CODE",$old_fund_code,PDO::PARAM_STR);
            $command->bindValue(":P_FUND_CODE",$this->fund_code,PDO::PARAM_STR);
            $command->bindValue(":P_FUND_NAME",$this->fund_name,PDO::PARAM_STR);
            $command->bindValue(":P_IM_CODE",$this->im_code,PDO::PARAM_STR);
            $command->bindValue(":P_COUNTERPART",$this->counterpart,PDO::PARAM_STR);
            $command->bindValue(":P_FUND_TYPE",$this->fund_type,PDO::PARAM_STR);
            $command->bindValue(":P_PORTFOLIO_ID",$this->portfolio_id,PDO::PARAM_STR);
            $command->bindValue(":P_SELLER_TAX_ID",$this->seller_tax_id,PDO::PARAM_STR);
            $command->bindValue(":P_CRE_BY",$this->cre_by,PDO::PARAM_STR);
            $command->bindValue(":P_CRE_DT",$this->cre_dt,PDO::PARAM_STR);
            $command->bindValue(":P_UPD_BY",$this->upd_by,PDO::PARAM_STR);
            $command->bindValue(":P_UPD_DT",$this->upd_dt,PDO::PARAM_STR);
            $command->bindValue(":P_UPD_STATUS",$exec_status,PDO::PARAM_STR);                               
            $command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
            $command->bindValue(":P_CANCEL_REASON",$this->cancel_reason,PDO::PARAM_STR);
            $command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
            $command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,1000);
            $command->execute();
            $transaction->commit();
            //Commit baru akan dijalankan saat semua transaksi INSERT sukses
            
        }catch(Exception $ex){
            $transaction->rollback();
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
		$criteria->compare('fund_code',$this->fund_code,true);
		$criteria->compare('fund_name',$this->fund_name,true);
		$criteria->compare('im_code',$this->im_code,true);
		$criteria->compare('counterpart',$this->counterpart,true);
		$criteria->compare('fund_type',$this->fund_type,true);
		$criteria->compare('portfolio_id',$this->portfolio_id,true);
		$criteria->compare('seller_tax_id',$this->seller_tax_id,true);
		$criteria->compare('cre_by',$this->cre_by,true);
        $criteria->compare('approved_stat',$this->approved_stat,true);

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".$this->cre_dt_date."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".$this->cre_dt_month."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".$this->cre_dt_year."%'");		$criteria->compare('upd_by',$this->upd_by,true);

		if(!empty($this->upd_dt_date))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'DD') LIKE '%".$this->upd_dt_date."%'");
		if(!empty($this->upd_dt_month))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'MM') LIKE '%".$this->upd_dt_month."%'");
		if(!empty($this->upd_dt_year))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'YYYY') LIKE '%".$this->upd_dt_year."%'");
		$sort = new CSort;
        $sort->defaultOrder='fund_code, im_code';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}