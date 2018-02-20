<?php

/**
 * This is the model class for table "MST_IM".
 *
 * The followings are the available columns in table 'MST_IM':
 * @property string $im_code
 * @property string $im_name
 * @property string $cre_by
 * @property string $cre_dt
 * @property string $upd_by
 * @property string $upd_dt
 */
class Im extends AActiveRecordSP
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
		return 'MST_IM';
	}

	public function rules()
	{
		return array(
			
			array('im_name', 'length', 'max'=>100),
			array('cre_by, upd_by', 'length', 'max'=>8),
			array('cre_dt, upd_dt', 'safe'),
			array('im_code','length','max'=>'6'),
			array('im_code,im_name','required'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('im_code, im_name, cre_by, cre_dt, upd_by, upd_dt,cre_dt_date,cre_dt_month,cre_dt_year,upd_dt_date,upd_dt_month,upd_dt_year', 'safe', 'on'=>'search'),
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
			'im_code' => 'Im Code',
			'im_name' => 'Im Name',
			'cre_dt'=>'Created date',
            'cre_by'=>'Created by',
            'upd_dt'=>'Updated date',
            'upd_by'=>'Updated by'
		);
	}

  public function executeSp($exec_status,$old_im_code)
    { 
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction(); 
                
        try{
            $query  = "CALL  SP_MST_IM_UPD (:P_SEARCH_IM_CODE,
                                            :P_IM_CODE,
                                            :P_IM_NAME,
                                            :P_CRE_BY,
                                            TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),
                                            :P_UPD_BY,
                                            TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),
                                            :P_UPD_STATUS,
                                            :p_ip_address,
                                            :p_cancel_reason,
                                            :p_error_code,
                                            :p_error_msg)";
            
            $command = $connection->createCommand($query);
            $command->bindValue(":P_SEARCH_IM_CODE",$old_im_code,PDO::PARAM_STR);
            $command->bindValue(":P_IM_CODE",$this->im_code,PDO::PARAM_STR);
            $command->bindValue(":P_IM_NAME",$this->im_name,PDO::PARAM_STR);
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
		$criteria->compare('im_code',$this->im_code,true);
		$criteria->compare('im_name',$this->im_name,true);
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
        $sort->defaultOrder = 'im_code';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}