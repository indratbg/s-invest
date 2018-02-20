<?php

/**
 * This is the model class for table "MST_PARAMETER".
 *
 * The followings are the available columns in table 'MST_PARAMETER':
 * @property string $prm_cd_1
 * @property string $prm_cd_2
 * @property string $prm_desc
 * @property string $prm_desc2
 * @property string $user_id
 * @property string $cre_dt
 * @property string $upd_dt
 * @property string $upd_by
 * @property string $approved_dt
 * @property string $approved_by
 * @property string $approved_stat
 */
class Parameter extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;

	public $upd_dt_date;
	public $upd_dt_month;
	public $upd_dt_year;
	//AH: #END search (datetime || date)  additional comparison
	
	public $prm_cd_1_new;
	public $ctr_type;
	public $error_msg;
	
	public $approved_dt_date;
	public $approved_dt_month;
	public $approved_dt_year;
	//1 = grouping, 2 = no grouping
	public $group;
	
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
		return 'MST_PARAMETER';
	}
	
	public function getParameterDesc()
	{
		return $this->prm_cd_2." - ".$this->prm_desc;
	}
	
	public function getPrimaryKey()
	{
		return array('prm_cd_1'=>$this->prm_cd_1,'prm_cd_2'=>$this->prm_cd_2);	
	}

	public function rules()
	{
		return array(
			array('prm_cd_1_new','validatePrmCd1New'),
			array('prm_cd_1, prm_cd_2, prm_desc', 'required'),
			array('prm_cd_1', 'length', 'max'=>6),
			array('prm_cd_2', 'length', 'max'=>6),
			array('prm_desc', 'length', 'max'=>255),
			array('prm_desc2', 'length', 'max'=>1000),
			array('user_id', 'length', 'max'=>8),
			array('upd_by, approved_by', 'length', 'max'=>10),
			array('approved_stat', 'length', 'max'=>1),
			array('cre_dt, upd_dt, ctr_type, group, approved_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			
			array('prm_cd_1, prm_cd_2, prm_desc, prm_desc2, user_id, cre_dt, upd_dt,cre_dt_date,cre_dt_month,cre_dt_year,upd_dt_date,upd_dt_month,upd_dt_year,upd_by, approved_dt, approved_by, approved_stat,approved_dt_date,approved_dt_month,approved_dt_year', 'safe', 'on'=>'search'),
		);
	}
	
    public function validatePrmCd1New($attribute)
    {
    	if(empty($this->prm_cd_1) ){  
			if($this->prm_cd_1_new == '')
				$this->addError($attribute,'Please input new code 1');
			else
				$this->prm_cd_1 = $this->prm_cd_1_new;
		}
    }

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'prm_cd_1' => 'Code 1',
			'prm_cd_2' => 'Code 2',
			'prm_desc' => 'Description',
			'prm_desc2' => 'Description 2',
			'user_id' => 'User',
			'cre_dt' => 'Cre Date',
			'upd_dt' => 'Upd Date',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		
		if(empty($this->prm_cd_1)){
			$criteria->addCondition("UPPER(prm_cd_1) IN ('BANKCD','FPAJAK')");	
		}else{
			$criteria->addCondition("UPPER(prm_cd_1) LIKE UPPER('%".$this->prm_cd_1."%')");
		}

		$criteria->addCondition("UPPER(prm_cd_2) LIKE UPPER('".$this->prm_cd_2."%')");
		$criteria->addCondition("UPPER(prm_desc) LIKE UPPER('%".$this->prm_desc."%')");
		if(!empty($this->prm_desc2))
			$criteria->addCondition("UPPER(prm_desc2) LIKE UPPER('%".$this->prm_desc2."%')");
		if(!empty($this->user_id))
			$criteria->addCondition("UPPER(user_id) LIKE UPPER('%".$this->user_id."%')");

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".($this->cre_dt_date)."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".($this->cre_dt_month)."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".($this->cre_dt_year)."%'");
		if(!empty($this->upd_dt_date))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'DD') LIKE '%".($this->upd_dt_date)."%'");
		if(!empty($this->upd_dt_month))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'MM') LIKE '%".($this->upd_dt_month)."%'");
		if(!empty($this->upd_dt_year))
			$criteria->addCondition("TO_CHAR(t.upd_dt,'YYYY') LIKE '%".($this->upd_dt_year)."%'");
		$criteria->compare('upd_by',$this->upd_by,true);

		if(!empty($this->approved_dt_date))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'DD') LIKE '%".($this->approved_dt_date)."%'");
		if(!empty($this->approved_dt_month))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'MM') LIKE '%".($this->approved_dt_month)."%'");
		if(!empty($this->approved_dt_year))
			$criteria->addCondition("TO_CHAR(t.approved_dt,'YYYY') LIKE '%".($this->approved_dt_year)."%'");		$criteria->compare('approved_by',$this->approved_by,true);
		$criteria->compare('approved_stat','A',true);
		$sort = new CSort();
		$sort->defaultOrder = 'prm_cd_1, prm_cd_2';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	public function executeSp($exec_status,$prm_cd_1,$prm_cd_2)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();	
		
		try{
			$query  = "CALL SP_MST_PARAMETER_UPD(
						:P_SEARCH_PRM_CD_1,:P_SEARCH_PRM_CD_2,		
						:P_PRM_CD_1,:P_PRM_CD_2,:P_PRM_DESC,:P_PRM_DESC2,	
						:P_USER_ID,TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),		
						:P_UPD_BY,:P_UPD_STATUS,:P_IP_ADDRESS,:P_CANCEL_REASON,:P_ERROR_CODE,:P_ERROR_MSG)";
			
			$command = $connection->createCommand($query);
			
			$command->bindValue(":P_SEARCH_PRM_CD_1",$prm_cd_1,PDO::PARAM_STR);
			$command->bindValue(":P_SEARCH_PRM_CD_2",$prm_cd_2,PDO::PARAM_STR);
			$command->bindValue(":P_PRM_CD_1",$this->prm_cd_1,PDO::PARAM_STR);
            $command->bindValue(":P_PRM_CD_2",$this->prm_cd_2,PDO::PARAM_STR);
            $command->bindValue(":P_PRM_DESC",$this->prm_desc,PDO::PARAM_STR);
            $command->bindValue(":P_PRM_DESC2",$this->prm_desc2,PDO::PARAM_STR);
			
			  
            $command->bindValue(":P_CRE_DT",$this->cre_dt,PDO::PARAM_STR); 
            $command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR); 
            $command->bindValue(":P_UPD_DT",$this->upd_dt,PDO::PARAM_STR); 
            $command->bindValue(":P_UPD_BY",$this->upd_by,PDO::PARAM_STR); 
            $command->bindValue(":P_UPD_STATUS",$exec_status,PDO::PARAM_STR); 
            $command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR); 
            $command->bindValue(":P_CANCEL_REASON",$this->cancel_reason,PDO::PARAM_STR); 
             
            $command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10); 
            $command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,100); 
			
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

	public static function cmbRegion()
	{
		$criteria  =  new CDbCriteria();
		$criteria->condition = 	'prm_cd_1=:prm_cd_1';
		$criteria->params    =  array(':prm_cd_1'=>'NEGARA');
		$criteria->order     =  'prm_desc ASC';
		
		$temp =  CHtml::listData(self::model()->findAll($criteria), 'prm_cd_2', 'prm_desc');
		$temp = array_merge(array('-Choose Region-'),$temp);
		return $temp;
	}
	
	public static function getParamDesc($type,$value)
	{
		if($value !== NULL):	
			$value = trim($value);	
			$temp  = self::model()->find('prm_cd_1=:prm_cd_1 AND prm_cd_2=:prm_cd_2',array(':prm_cd_1'=>$type,':prm_cd_2'=>$value));
			
			if($temp === null)
				return $type.' - '.$value;
			return $temp->prm_desc;
		endif;
		return $value;		
	}
	
	public static function getAssetType($asset_type)
	{
		if($asset_type !== NULL):	
			$asset_type = trim($asset_type);	
			$temp  = self::model()->find('prm_cd_1=:prm_cd_1 AND prm_cd_2=:prm_cd_2',array(':prm_cd_1'=>'FASSET',':prm_cd_2'=>$asset_type));
			
			if($temp === null)
				return $asset_type;
			return $temp->prm_desc;
		endif;
		return $asset_type;		
	}
	
	public static function getTypeDesc($lawan_type)
	{
		if($lawan_type !== NULL):	
			$lawan_type = trim($lawan_type);	
			$temp  = self::model()->find('prm_cd_1=:prm_cd_1 AND prm_cd_2=:prm_cd_2',array(':prm_cd_1'=>'LAWAN',':prm_cd_2'=>$lawan_type));
			
			if($temp === null)
				return $lawan_type;
			return $temp->prm_desc;
		endif;
		return $lawan_type;		
	}
	
	public static function getCombo($type,$prompt,$limit_start = null,$limit_end = null,$order = 'desc')
	{
		$criteria  			 =  new CDbCriteria();
		
		if($limit_start !== null && $limit_end !== null){
			$criteria->condition = 	'prm_cd_1=:prm_cd_1 AND prm_cd_2 >= :limit_start AND prm_cd_2 <= :limit_end';
			$criteria->params    =  array(':prm_cd_1'=>$type,':limit_start'=>$limit_start,':limit_end'=>$limit_end);		
		}else if($limit_start !== null){
			$criteria->condition = 	'prm_cd_1=:prm_cd_1 AND prm_cd_2 >= :limit_start';
			$criteria->params    =  array(':prm_cd_1'=>$type,':limit_start'=>$limit_start);
		}else{
			$criteria->condition = 	'prm_cd_1=:prm_cd_1';
			$criteria->params    =  array(':prm_cd_1'=>$type);
		}
		
		if($order == 'desc')$criteria->order     =  'prm_desc ASC';
		else
		{
			$criteria->order = 'prm_cd_2 ASC';		
		}

		$temp = array();	
		
		$listParameter = self::model()->findAll($criteria);
		if($prompt)$temp 		   = array(''=>$prompt); 
		foreach($listParameter as $modelParameter)
			$temp[$modelParameter->prm_cd_2] = $modelParameter->prm_desc;	
		
		return $temp;
	}
	
	


	public static function getRadioList($type,$limit_start,$limit_end)
	{
		$criteria  			 =  new CDbCriteria();
		$criteria->condition = 	'prm_cd_1=:prm_cd_1 AND prm_cd_2 >= :limit_start AND prm_cd_2 <= :limit_end';
		$criteria->params    =  array(':prm_cd_1'=>$type,':limit_start'=>$limit_start,':limit_end'=>$limit_end);
		$criteria->order     =  'prm_cd_2 ASC';
		
		$listParameter = self::model()->findAll($criteria);
		foreach($listParameter as $modelParameter)
			$temp[$modelParameter->prm_cd_2] = $modelParameter->prm_desc;	
		
		
		//$temp = CHtml::listData(self::model()->findAll($criteria), 'prm_cd_2', 'prm_desc');
		//$temp = array_merge(array(''=>$prompt),$temp);
		return $temp;
	}
}