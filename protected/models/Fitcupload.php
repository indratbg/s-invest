<?php

/**
 * This is the model class for table "FI_TC_UPLOAD".
 *
 * The followings are the available columns in table 'FI_TC_UPLOAD':
 * @property string $transaction_status
 * @property string $data_type
 * @property string $tc_reference_no
 * @property string $ta_reference_no
 * @property string $trade_date
 * @property string $settlement_date
 * @property string $im_code
 * @property string $im_name
 * @property string $br_code
 * @property string $br_name
 * @property string $counterparty_code
 * @property string $counterparty_name
 * @property string $fund_code
 * @property string $fund_name
 * @property string $security_code
 * @property string $security_name
 * @property string $buy_sell
 * @property string $ccy
 * @property double $price
 * @property double $face_value
 * @property double $proceeds
 * @property double $interest_rate
 * @property string $maturity_date
 * @property string $last_coupon_date
 * @property string $next_coupon_date
 * @property integer $accrued_days
 * @property double $accrued_interest_amount
 * @property double $other_fee
 * @property double $capital_gain_tax
 * @property double $interest_income_tax
 * @property double $withholding_tax
 * @property double $net_proceeds
 * @property string $seller_tax_id
 * @property string $purpose_of_transaction
 * @property string $tc_reference_id
 * @property string $match_status
 * @property string $cre_dt
 * @property string $user_id
 */
class Fitcupload extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $trade_date_date;
	public $trade_date_month;
	public $trade_date_year;

	public $settlement_date_date;
	public $settlement_date_month;
	public $settlement_date_year;

	public $maturity_date_date;
	public $maturity_date_month;
	public $maturity_date_year;

	public $last_coupon_date_date;
	public $last_coupon_date_month;
	public $last_coupon_date_year;

	public $next_coupon_date_date;
	public $next_coupon_date_month;
	public $next_coupon_date_year;

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
		return 'FI_TC_UPLOAD';
	}

	public function rules()
	{
		return array(
		
			array('trade_date, settlement_date, maturity_date, last_coupon_date, next_coupon_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('price, face_value, proceeds, interest_rate, accrued_days, accrued_interest_amount, other_fee, capital_gain_tax, interest_income_tax, withholding_tax, net_proceeds', 'application.components.validator.ANumberSwitcherValidator'),
			array('file_upload','file','types'=>'csv','wrongType'=>'File type must be *.csv','on'=>'upload'),
			array('accrued_days', 'numerical', 'integerOnly'=>true),
			array('price, face_value, proceeds, interest_rate, accrued_interest_amount, other_fee, capital_gain_tax, interest_income_tax, withholding_tax, net_proceeds', 'numerical'),
			array('transaction_status', 'length', 'max'=>4),
			array('data_type, buy_sell, purpose_of_transaction, match_status', 'length', 'max'=>1),
			array('tc_reference_no, ta_reference_no, seller_tax_id, tc_reference_id', 'length', 'max'=>20),
			array('im_code, br_code, counterparty_code', 'length', 'max'=>5),
			array('im_name, br_name, counterparty_name, fund_name, security_name', 'length', 'max'=>100),
			array('fund_code', 'length', 'max'=>25),
			array('security_code', 'length', 'max'=>35),
			array('ccy', 'length', 'max'=>3),
			array('user_id', 'length', 'max'=>10),
			array('trade_date, settlement_date, maturity_date, last_coupon_date, next_coupon_date, cre_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('transaction_status, data_type, tc_reference_no, ta_reference_no, trade_date, settlement_date, im_code, im_name, br_code, br_name, counterparty_code, counterparty_name, fund_code, fund_name, security_code, security_name, buy_sell, ccy, price, face_value, proceeds, interest_rate, maturity_date, last_coupon_date, next_coupon_date, accrued_days, accrued_interest_amount, other_fee, capital_gain_tax, interest_income_tax, withholding_tax, net_proceeds, seller_tax_id, purpose_of_transaction, tc_reference_id, match_status, cre_dt, user_id,trade_date_date,trade_date_month,trade_date_year,settlement_date_date,settlement_date_month,settlement_date_year,maturity_date_date,maturity_date_month,maturity_date_year,last_coupon_date_date,last_coupon_date_month,last_coupon_date_year,next_coupon_date_date,next_coupon_date_month,next_coupon_date_year,cre_dt_date,cre_dt_month,cre_dt_year', 'safe', 'on'=>'search'),
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
			'data_type' => 'Data Type',
			'tc_reference_no' => 'Tc Reference No',
			'ta_reference_no' => 'Ta Reference No',
			'trade_date' => 'Trade Date',
			'settlement_date' => 'Settlement Date',
			'im_code' => 'Im Code',
			'im_name' => 'Im Name',
			'br_code' => 'Br Code',
			'br_name' => 'Br Name',
			'counterparty_code' => 'Counterparty Code',
			'counterparty_name' => 'Counterparty Name',
			'fund_code' => 'Fund Code',
			'fund_name' => 'Fund Name',
			'security_code' => 'Security Code',
			'security_name' => 'Security Name',
			'buy_sell' => 'Buy Sell',
			'ccy' => 'Ccy',
			'price' => 'Price',
			'face_value' => 'Face Value',
			'proceeds' => 'Proceeds',
			'interest_rate' => 'Interest Rate',
			'maturity_date' => 'Maturity Date',
			'last_coupon_date' => 'Last Coupon Date',
			'next_coupon_date' => 'Next Coupon Date',
			'accrued_days' => 'Accrued Days',
			'accrued_interest_amount' => 'Accrued Interest Amount',
			'other_fee' => 'Other Fee',
			'capital_gain_tax' => 'Capital Gain Tax',
			'interest_income_tax' => 'Interest Income Tax',
			'withholding_tax' => 'Withholding Tax',
			'net_proceeds' => 'Net Proceeds',
			'seller_tax_id' => 'Seller Tax',
			'purpose_of_transaction' => 'Purpose Of Transaction',
			'tc_reference_id' => 'Tc Reference',
			'match_status' => 'Match Status',
			'cre_dt' => 'Cre Date',
			'user_id' => 'User',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('transaction_status',$this->transaction_status,true);
		$criteria->compare('data_type',$this->data_type,true);
		$criteria->compare('tc_reference_no',$this->tc_reference_no,true);
		$criteria->compare('ta_reference_no',$this->ta_reference_no,true);

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
		$criteria->compare('counterparty_code',$this->counterparty_code,true);
		$criteria->compare('counterparty_name',$this->counterparty_name,true);
		$criteria->compare('fund_code',$this->fund_code,true);
		$criteria->compare('fund_name',$this->fund_name,true);
		$criteria->compare('security_code',$this->security_code,true);
		$criteria->compare('security_name',$this->security_name,true);
		$criteria->compare('buy_sell',$this->buy_sell,true);
		$criteria->compare('ccy',$this->ccy,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('face_value',$this->face_value);
		$criteria->compare('proceeds',$this->proceeds);
		$criteria->compare('interest_rate',$this->interest_rate);

		if(!empty($this->maturity_date_date))
			$criteria->addCondition("TO_CHAR(t.maturity_date,'DD') LIKE '%".$this->maturity_date_date."%'");
		if(!empty($this->maturity_date_month))
			$criteria->addCondition("TO_CHAR(t.maturity_date,'MM') LIKE '%".$this->maturity_date_month."%'");
		if(!empty($this->maturity_date_year))
			$criteria->addCondition("TO_CHAR(t.maturity_date,'YYYY') LIKE '%".$this->maturity_date_year."%'");
		if(!empty($this->last_coupon_date_date))
			$criteria->addCondition("TO_CHAR(t.last_coupon_date,'DD') LIKE '%".$this->last_coupon_date_date."%'");
		if(!empty($this->last_coupon_date_month))
			$criteria->addCondition("TO_CHAR(t.last_coupon_date,'MM') LIKE '%".$this->last_coupon_date_month."%'");
		if(!empty($this->last_coupon_date_year))
			$criteria->addCondition("TO_CHAR(t.last_coupon_date,'YYYY') LIKE '%".$this->last_coupon_date_year."%'");
		if(!empty($this->next_coupon_date_date))
			$criteria->addCondition("TO_CHAR(t.next_coupon_date,'DD') LIKE '%".$this->next_coupon_date_date."%'");
		if(!empty($this->next_coupon_date_month))
			$criteria->addCondition("TO_CHAR(t.next_coupon_date,'MM') LIKE '%".$this->next_coupon_date_month."%'");
		if(!empty($this->next_coupon_date_year))
			$criteria->addCondition("TO_CHAR(t.next_coupon_date,'YYYY') LIKE '%".$this->next_coupon_date_year."%'");		$criteria->compare('accrued_days',$this->accrued_days);
		$criteria->compare('accrued_interest_amount',$this->accrued_interest_amount);
		$criteria->compare('other_fee',$this->other_fee);
		$criteria->compare('capital_gain_tax',$this->capital_gain_tax);
		$criteria->compare('interest_income_tax',$this->interest_income_tax);
		$criteria->compare('withholding_tax',$this->withholding_tax);
		$criteria->compare('net_proceeds',$this->net_proceeds);
		$criteria->compare('seller_tax_id',$this->seller_tax_id,true);
		$criteria->compare('purpose_of_transaction',$this->purpose_of_transaction,true);
		$criteria->compare('tc_reference_id',$this->tc_reference_id,true);
		$criteria->compare('match_status',$this->match_status,true);

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