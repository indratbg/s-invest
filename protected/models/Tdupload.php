<?php

/**
 * This is the model class for table "TD_UPLOAD".
 *
 * The followings are the available columns in table 'TD_UPLOAD':
 * @property string $transaction_status
 * @property string $trade_date
 * @property string $settlement_date
 * @property string $im_code
 * @property string $im_name
 * @property string $br_code
 * @property string $br_name
 * @property string $security_code
 * @property string $security_name
 * @property string $buy_sell
 * @property string $ccy
 * @property double $price
 * @property integer $quantity
 * @property string $td_reference_no
 * @property string $td_reference_id
 * @property string $check_status
 * @property string $cre_dt
 * @property string $user_id
 */
class Tdupload extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $trade_date_date;
	public $trade_date_month;
	public $trade_date_year;

	public $settlement_date_date;
	public $settlement_date_month;
	public $settlement_date_year;

	public $cre_dt_date;
	public $cre_dt_month;
	public $cre_dt_year;
    public $file_upload;
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
		return 'TD_UPLOAD';
	}

	public function rules()
	{
		return array(
		
			array('trade_date, settlement_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('price, quantity', 'application.components.validator.ANumberSwitcherValidator'),
			array('file_upload','file','types'=>'csv','wrongType'=>'File type must be *.csv','on'=>'upload'),
			array('quantity', 'numerical', 'integerOnly'=>true,'on'=>'upload'),
			array('price', 'numerical'),
			array('transaction_status', 'length', 'max'=>4),
			array('im_code, br_code', 'length', 'max'=>5),
			array('im_name, br_name, security_name', 'length', 'max'=>100),
			array('security_code', 'length', 'max'=>35),
			array('buy_sell, check_status', 'length', 'max'=>1,'on'=>'upload'),
			array('ccy', 'length', 'max'=>3),
			array('td_reference_no, td_reference_id', 'length', 'max'=>20),
			array('user_id', 'length', 'max'=>10),
			array('trade_date, settlement_date, cre_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('transaction_status, trade_date, settlement_date, im_code, im_name, br_code, br_name, security_code, security_name, buy_sell, ccy, price, quantity, td_reference_no, td_reference_id, check_status, cre_dt, user_id,trade_date_date,trade_date_month,trade_date_year,settlement_date_date,settlement_date_month,settlement_date_year,cre_dt_date,cre_dt_month,cre_dt_year', 'safe', 'on'=>'search'),
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
			'transaction_status' => 'Transaction Status',
			'trade_date' => 'Trade Date',
			'settlement_date' => 'Settlement Date',
			'im_code' => 'Im Code',
			'im_name' => 'Im Name',
			'br_code' => 'Br Code',
			'br_name' => 'Br Name',
			'security_code' => 'Security Code',
			'security_name' => 'Security Name',
			'buy_sell' => 'Buy Sell',
			'ccy' => 'Ccy',
			'price' => 'Price',
			'quantity' => 'Quantity',
			'td_reference_no' => 'Td Reference No',
			'td_reference_id' => 'Td Reference',
			'check_status' => 'Check Status',
			'cre_dt' => 'Cre Date',
			'user_id' => 'User'
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('transaction_status',$this->transaction_status,true);

		if(!empty($this->trade_date_date))
			$criteria->addCondition("TO_CHAR(t.trade_date,'DD') LIKE '%".$this->trade_date_date."%'");
		if(!empty($this->trade_date_month))
			$criteria->addCondition("TO_CHAR(t.trade_date,'MM') LIKE '%".$this->trade_date_month."%'");
		if(!empty($this->trade_date_year))
			$criteria->addCondition("TO_CHAR(t.trade_date,'YYYY') LIKE '%".$this->trade_date_year."%'");
		if(!empty($this->settlement_date_date))
			$criteria->addCondition("TO_CHAR(t.settlement_date,'DD') LIKE '%".$this->settlement_date_date."%'");
		if(!empty($this->settlement_date_month))
			$criteria->addCondition("TO_CHAR(t.settlement_date,'MM') LIKE '%".$this->settlement_date_month."%'");
		if(!empty($this->settlement_date_year))
			$criteria->addCondition("TO_CHAR(t.settlement_date,'YYYY') LIKE '%".$this->settlement_date_year."%'");		$criteria->compare('im_code',$this->im_code,true);
		$criteria->compare('im_name',$this->im_name,true);
		$criteria->compare('br_code',$this->br_code,true);
		$criteria->compare('br_name',$this->br_name,true);
		$criteria->compare('security_code',$this->security_code,true);
		$criteria->compare('security_name',$this->security_name,true);
		$criteria->compare('buy_sell',$this->buy_sell,true);
		$criteria->compare('ccy',$this->ccy,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('td_reference_no',$this->td_reference_no,true);
		$criteria->compare('td_reference_id',$this->td_reference_id,true);
		$criteria->compare('check_status',$this->check_status,true);

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".$this->cre_dt_date."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".$this->cre_dt_month."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".$this->cre_dt_year."%'");		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}