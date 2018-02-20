<?php

/**
 * This is the model class for table "T_LOGIN_LOG".
 *
 * The followings are the available columns in table 'T_LOGIN_LOG':
 * @property string $user_id
 * @property string $log_dt
 * @property string $log_type
 * @property string $module_name
 * @property string $description
 */
class Loginlog extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $log_dt_date;
	public $log_dt_month;
	public $log_dt_year;
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
		return 'T_LOGIN_LOG';
	}

	public function rules()
	{
		return array(
		
			//array('log_dt', 'application.components.validator.ADatePickerSwitcherValidator'),
			
			array('user_id, log_dt', 'required'),
			array('user_id, log_type', 'length', 'max'=>10),
			array('ip_address', 'length', 'max'=>15),
			array('module_name, description', 'length', 'max'=>50),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('user_id, log_dt, log_type, module_name, description,log_dt_date,log_dt_month,log_dt_year, ip_address', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'log_dt' => 'Log Date',
			'log_type' => 'Log Type',
			'module_name' => 'Module Name',
			'description' => 'Description',
			'ip_address' => 'IP Address'
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('user_id',$this->user_id,true);

		if(!empty($this->log_dt_date))
			$criteria->addCondition("TO_CHAR(t.log_dt,'DD') LIKE '%".($this->log_dt_date)."%'");
		if(!empty($this->log_dt_month))
			$criteria->addCondition("TO_CHAR(t.log_dt,'MM') LIKE '%".($this->log_dt_month)."%'");
		if(!empty($this->log_dt_year))
			$criteria->addCondition("TO_CHAR(t.log_dt,'YYYY') LIKE '%".($this->log_dt_year)."%'");		$criteria->compare('log_type',$this->log_type,true);
		$criteria->compare('module_name',$this->module_name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}