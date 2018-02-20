<?php

/**
 * This is the model class for table "T_PASSWORD_LOG".
 *
 * The followings are the available columns in table 'T_PASSWORD_LOG':
 * @property string $user_id
 * @property string $password
 * @property string $eff_date
 */
class Tpasswordlog extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $eff_date_date;
	public $eff_date_month;
	public $eff_date_year;
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
		return 'T_PASSWORD_LOG';
	}

	public function rules()
	{
		return array(
		
			array('eff_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
			
			array('user_id, password, eff_date', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('password', 'length', 'max'=>50),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('user_id, password, eff_date,eff_date_date,eff_date_month,eff_date_year', 'safe', 'on'=>'search'),
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
			'password' => 'Password',
			'eff_date' => 'Eff Date',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('password',$this->password,true);

		if(!empty($this->eff_date_date))
			$criteria->addCondition("TO_CHAR(t.eff_date,'DD') LIKE '%".$this->eff_date_date."%'");
		if(!empty($this->eff_date_month))
			$criteria->addCondition("TO_CHAR(t.eff_date,'MM') LIKE '%".$this->eff_date_month."%'");
		if(!empty($this->eff_date_year))
			$criteria->addCondition("TO_CHAR(t.eff_date,'YYYY') LIKE '%".$this->eff_date_year."%'");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}