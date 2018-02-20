<?php

/**
 * This is the model class for table "T_USER_ACCESS_LOG".
 *
 * The followings are the available columns in table 'T_USER_ACCESS_LOG':
 * @property double $access_log_id
 * @property string $user_id
 * @property string $action_url
 * @property string $access_date
 */
class Useraccesslog extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $access_date_date;
	public $access_date_month;
	public $access_date_year;
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
		return 'T_USER_ACCESS_LOG';
	}

	public function rules()
	{
		return array(
		
			array('access_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('access_log_id', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('user_id', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('ip_address', 'length', 'max'=>15),
			array('action_url', 'length', 'max'=>225),
			array('access_date', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('access_log_id, user_id, action_url, access_date, access_date_date,access_date_month,access_date_year,ip_address', 'safe', 'on'=>'search'),
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
			'access_log_id' => 'Access Log',
			'user_id' => 'User',
			'action_url' => 'Action Url',
			'access_date' => 'Access Date',
			'ip_address' => 'IP Address'
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('access_log_id',$this->access_log_id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('action_url',$this->action_url,true);

		if(!empty($this->access_date_date))
			$criteria->addCondition("TO_CHAR(t.access_date,'DD') LIKE '%".$this->access_date_date."%'");
		if(!empty($this->access_date_month))
			$criteria->addCondition("TO_CHAR(t.access_date,'MM') LIKE '%".$this->access_date_month."%'");
		if(!empty($this->access_date_year))
			$criteria->addCondition("TO_CHAR(t.access_date,'YYYY') LIKE '%".$this->access_date_year."%'");		$criteria->compare('app_param',$this->app_param,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}