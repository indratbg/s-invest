<?php

/**
 * This is the model class for table "MST_USER".
 *
 * The followings are the available columns in table 'MST_USER':
 * @property string $user_id
 * @property string $user_name
 * @property string $password
 * @property string $expiry_date
 * @property string $dept
 * @property string $user_level
 * @property string $short_name
 * @property string $def_addr_1
 * @property string $def_addr_2
 * @property string $def_addr_3
 * @property string $post_cd
 * @property string $regn_cd
 * @property string $phone_num
 * @property string $ic_type
 * @property string $ic_num
 * @property string $user_cre_id
 * @property string $cre_dt
 * @property string $upd_dt
 * @property string $sts_suspended
 */
class User extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $expiry_date_date;
	public $expiry_date_month;
	public $expiry_date_year;

	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;

	public $upd_dt_date;
	public $upd_dt_month;
	public $upd_dt_year;
	//AH: #END search (datetime || date)  additional comparison
	
	public $old_pass = '';
    public $new_pass = '';
    public $confirm_pass = '';
	public $encrypted_password = '';
	
	public static $USER_LEVEL 		= array('1  '=>'Branch Manager','2  '=>'Salesman');
	public static $USER_DEPARTMENT 	= array('1'=>'Back Office','2'=>'Front Office');
	public static $IC_TYPE 	  		= array('0'=>'KTP','2'=>'SIM','3'=>'PASPOR','4'=>'KIMS');
	
	const USER_LEVEL_BO    = 1;
	const USER_LEVEL_SALES = 2;
	
	const IC_TYPE_KTP = 0;
	const IC_TYPE_SIM = 1;
	const IC_TYPE_PASPOR = 2;
	const IC_TYPE_KIMS = 3;
	
	const USER_DEPARTMENT_BO = 1;
	const USER_DEPARTMENT_FO = 2;
	
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
		return 'MST_USER';
	}
	
	/*
	 *  AH: provide all date only field in here , 
	 *    to change format from yyyy-mm-dd 00:00:00 into yyyy-mm-dd
	 */
	protected function afterFind()
	{
		$this->expiry_date = Yii::app()->format->cleanDate($this->expiry_date);
		$this->regn_cd  = trim($this->regn_cd);
	}
	
	/*
	 *  AH: this function is for executing procedure
	 *  exec_status  : I/U/C
	 */
	public function executeSp($exec_status,$user_id)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$query  = "CALL SP_MST_USER_UPD(:P_SEARCH_USER_ID,:P_USER_ID,:P_USER_NAME, 
						:P_PASSWORD,:P_ENCRYPTED_PASSWORD, 
						TO_DATE(:P_EXPIRY_DATE,'YYYY-MM-DD'),:P_DEPT,
						:P_USER_LEVEL,:P_SHORT_NAME,:P_DEF_ADDR_1,:P_DEF_ADDR_2,:P_DEF_ADDR_3,
						:P_POST_CD,:P_REGN_CD,:P_PHONE_NUM,:P_IC_TYPE,:P_IC_NUM,:P_STS_SUSPENDED,:P_USER_CRE_ID,
						TO_DATE(:P_CRE_DT,'YYYY-MM-DD HH24:MI:SS'),:P_UPD_BY,
						TO_DATE(:P_UPD_DT,'YYYY-MM-DD HH24:MI:SS'),:P_DEF_ADDR,
						:P_UPD_STATUS,:P_IP_ADDRESS,:P_CANCEL_REASON,:P_ERROR_CODE,:P_ERROR_MSG)";
					
			$command = $connection->createCommand($query);
					
			$command->bindValue(":P_SEARCH_USER_ID",$user_id,PDO::PARAM_STR);
			$command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR);
			$command->bindValue(":P_USER_NAME",$this->user_name,PDO::PARAM_STR);
			$command->bindValue(":P_PASSWORD",$this->password,PDO::PARAM_STR);
			$command->bindValue(":P_ENCRYPTED_PASSWORD",$this->encrypted_password,PDO::PARAM_STR);
			
			$command->bindValue(":P_EXPIRY_DATE",$this->expiry_date,PDO::PARAM_STR);
			$command->bindValue(":P_DEPT",$this->dept,PDO::PARAM_STR);
			$command->bindValue(":P_USER_LEVEL",$this->user_level,PDO::PARAM_STR);
			$command->bindValue(":P_SHORT_NAME",$this->short_name,PDO::PARAM_STR);
			$command->bindValue(":P_DEF_ADDR_1",$this->def_addr_1,PDO::PARAM_STR);
			$command->bindValue(":P_DEF_ADDR_2",$this->def_addr_2,PDO::PARAM_STR);
			$command->bindValue(":P_DEF_ADDR_3",$this->def_addr_3,PDO::PARAM_STR);
			$command->bindValue(":P_POST_CD",$this->post_cd,PDO::PARAM_STR);
			$command->bindValue(":P_REGN_CD",$this->regn_cd,PDO::PARAM_STR);
			$command->bindValue(":P_PHONE_NUM",$this->phone_num,PDO::PARAM_STR);
			$command->bindValue(":P_IC_TYPE",$this->ic_type,PDO::PARAM_STR);
			$command->bindValue(":P_IC_NUM",$this->ic_num,PDO::PARAM_STR);
			$command->bindValue(":P_STS_SUSPENDED",$this->sts_suspended,PDO::PARAM_STR);
			$command->bindValue(":P_USER_CRE_ID",$this->user_cre_id,PDO::PARAM_STR);
			$command->bindValue(":P_CRE_DT",$this->cre_dt,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_BY",$this->upd_by,PDO::PARAM_STR);
			$command->bindValue(":P_UPD_DT",$this->upd_dt,PDO::PARAM_STR);
			$command->bindValue(":P_DEF_ADDR",$this->def_addr_1.$this->def_addr_2.$this->def_addr_3,PDO::PARAM_STR);
			
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
	
	public function executeValidatePassword($user_id, $encrypted_old_password)
	{
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		
		try{
			$query  = "CALL VALIDATE_PASSWORD(:P_USER_ID, 
						:P_NEW_PASSWORD,:P_ENCRYPTED_OLD_PASSWORD,
						:P_ERR_CODE,:P_ERR_MSG)";
					
			$command = $connection->createCommand($query);
					
			$command->bindValue(":P_USER_ID",$user_id,PDO::PARAM_STR);
			$command->bindValue(":P_NEW_PASSWORD",$this->new_pass,PDO::PARAM_STR);
			$command->bindValue(":P_ENCRYPTED_OLD_PASSWORD",$encrypted_old_password,PDO::PARAM_STR);
			
			$command->bindParam(":P_ERR_CODE",$this->error_code,PDO::PARAM_INT,10);
			$command->bindParam(":P_ERR_MSG",$this->error_msg,PDO::PARAM_STR,100);
			
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
	

	public function rules()
	{
		return array(
			array('user_id, user_name, new_pass, confirm_pass, expiry_date','required','on'=>'insert'),
			array('confirm_pass', 'compare', 'on'=>'changepassword, insert, update', 'compareAttribute'=>'new_pass', 'skipOnError'=>true),
			array('old_pass, new_pass, confirm_pass','required','on'=>'changepassword'),
			
			array('user_id', 'length', 'max'=>8),
			array('user_name', 'length', 'max'=>40),
			array('password', 'length', 'max'=>50),
			array('new_pass, password', 'length', 'min'=>6),
			array('dept, ic_type, sts_suspended', 'length', 'max'=>1),
			array('user_level, regn_cd', 'length', 'max'=>3),
			array('short_name', 'length', 'max'=>10),
			array('def_addr_1, def_addr_2, def_addr_3', 'length', 'max'=>50),
			array('post_cd', 'length', 'max'=>6),
			array('phone_num', 'length', 'max'=>15),
			array('ic_num', 'length', 'max'=>25),
			array('user_cre_id', 'length', 'max'=>8),
			array('expiry_date, cre_dt, upd_dt, approved_stat', 'safe'),

			array('expiry_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			array('new_pass, confirm_pass, expiry_date','safe','on'=>'update'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_name, password, expiry_date, dept, user_level, short_name, approved_stat, def_addr_1, def_addr_2, def_addr_3, post_cd, regn_cd, phone_num, ic_type, ic_num, user_cre_id, cre_dt, upd_dt, sts_suspended,expiry_date_date,expiry_date_month,expiry_date_year,cre_dt_date,cre_dt_month,cre_dt_year,upd_dt_date,upd_dt_month,upd_dt_year', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'usergroupdetail' => array(self::HAS_MANY, 'Usergroupdetail', 'user_id','order'=>'t.user_name ASC'),
		);
	}

	public function scopes()
	{
		return array(
			'active'=>array(
                'condition'=>"expiry_date > trunc(sysdate) AND sts_suspended = '".AConstant::IS_FLAG_N."'",
            ),
            'stucklogin'=>array(
            
			),
		);
	}

	public function attributeLabels()
	{
		return array(
			'user_id' => 'User ID',
			'user_name' => 'Name',
			'password' => 'Password',
			'expiry_date' => 'Expiry Date',
			'dept' => 'Department',
			'user_level' => 'User Level',
			'short_name' => 'Short Name',
			'def_addr_1' => 'Def Addr 1',
			'def_addr_2' => 'Def Addr 2',
			'def_addr_3' => 'Def Addr 3',
			'post_cd' => 'Post Code',
			'regn_cd' => 'Region Code',
			'phone_num' => 'Phone Number',
			'ic_type' => 'IC Type',
			'ic_num' => 'IC Number',
			'user_cre_id' => 'User Cre',
			'cre_dt' => 'Cre Date',
			'upd_dt' => 'Upd Date',
			'sts_suspended' => 'Status',
			'old_pass'=>'Old Password',
			'new_pass'=>'New Password',
			'confirm_pass'=>'Confirm Password',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("UPPER(user_id) LIKE UPPER('%".$this->user_id."%')");
		$criteria->addCondition("UPPER(user_name) LIKE UPPER('%".$this->user_name."%')");
		$criteria->compare('password',$this->password,true);

		if(!empty($this->expiry_date_date))
			$criteria->addCondition("TO_CHAR(t.expiry_date,'DD') LIKE '%".($this->expiry_date_date)."%'");
		if(!empty($this->expiry_date_month))
			$criteria->addCondition("TO_CHAR(t.expiry_date,'MM') LIKE '%".($this->expiry_date_month)."%'");
		if(!empty($this->expiry_date_year))
			$criteria->addCondition("TO_CHAR(t.expiry_date,'YYYY') LIKE '%".($this->expiry_date_year)."%'");		
		
		$criteria->compare('dept',$this->dept,true);
		$criteria->compare('user_level',$this->user_level,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('def_addr_1',$this->def_addr_1,true);
		$criteria->compare('def_addr_2',$this->def_addr_2,true);
		$criteria->compare('def_addr_3',$this->def_addr_3,true);
		$criteria->compare('post_cd',$this->post_cd,true);
		$criteria->compare('regn_cd',$this->regn_cd,true);
		$criteria->compare('phone_num',$this->phone_num,true);
		$criteria->compare('ic_type',$this->ic_type,true);
		$criteria->compare('ic_num',$this->ic_num,true);
		$criteria->compare('user_cre_id',$this->user_cre_id,true);

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
			
		$criteria->compare('sts_suspended',$this->sts_suspended,true);
		$criteria->compare('approved_stat',$this->approved_stat,true);

		$sort = new CSort;
		
		$sort->defaultOrder='user_id';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}
}