<?php

/**
 * This is the model class for table "TC_UPLOAD".
 *
 * The followings are the available columns in table 'TC_UPLOAD':
 * @property string $transaction_status
 * @property string $ta_reference_no
 * @property string $trade_date
 * @property string $settlement_date
 * @property string $im_code
 * @property string $im_name
 * @property string $br_code
 * @property string $br_name
 * @property string $fund_code
 * @property string $fund_name
 * @property string $security_code
 * @property string $security_name
 * @property string $buy_sell
 * @property string $ccy
 * @property double $price
 * @property integer $quantity
 * @property double $trade_amount
 * @property double $commission
 * @property double $sales_tax
 * @property double $levy
 * @property double $vat
 * @property double $other_charges
 * @property double $gross_settlement_amount
 * @property double $wht_on_commission
 * @property double $net_settlement_amount
 * @property string $tc_reference_no
 * @property string $tc_reference_id
 * @property string $match_status
 * @property string $cre_dt
 * @property string $user_id
 * @property integer $update_seq
 */
class Tcupload extends AActiveRecordSP
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
		return 'TC_UPLOAD';
	}

	public function rules()
	{
		return array(
		
			array('trade_date, settlement_date', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('price, quantity, trade_amount, commission, sales_tax, levy, vat, other_charges, gross_settlement_amount, wht_on_commission, net_settlement_amount, update_seq', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('quantity, update_seq', 'numerical', 'integerOnly'=>true),
			array('price, trade_amount, commission, sales_tax, levy, vat, other_charges, gross_settlement_amount, wht_on_commission, net_settlement_amount', 'numerical'),
			array('transaction_status', 'length', 'max'=>4),
			array('file_upload','file','types'=>'csv','wrongType'=>'File type must be *.csv','on'=>'upload'),
			array('ta_reference_no, tc_reference_no, tc_reference_id', 'length', 'max'=>20),
			array('im_code, br_code', 'length', 'max'=>5),
			array('im_name, br_name, fund_name, security_name', 'length', 'max'=>100),
			array('fund_code', 'length', 'max'=>25),
			array('security_code', 'length', 'max'=>35),
			array('buy_sell, match_status', 'length', 'max'=>1),
			array('ccy', 'length', 'max'=>3),
			array('user_id', 'length', 'max'=>10),
			array('trade_date, settlement_date, cre_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('transaction_status, ta_reference_no, trade_date, settlement_date, im_code, im_name, br_code, br_name, fund_code, fund_name, security_code, security_name, buy_sell, ccy, price, quantity, trade_amount, commission, sales_tax, levy, vat, other_charges, gross_settlement_amount, wht_on_commission, net_settlement_amount, tc_reference_no, tc_reference_id, match_status, cre_dt, user_id, update_seq,trade_date_date,trade_date_month,trade_date_year,settlement_date_date,settlement_date_month,settlement_date_year,cre_dt_date,cre_dt_month,cre_dt_year', 'safe', 'on'=>'search'),
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
			'ta_reference_no' => 'Ta Reference No',
			'trade_date' => 'Trade Date',
			'settlement_date' => 'Settlement Date',
			'im_code' => 'Im Code',
			'im_name' => 'Im Name',
			'br_code' => 'Br Code',
			'br_name' => 'Br Name',
			'fund_code' => 'Fund Code',
			'fund_name' => 'Fund Name',
			'security_code' => 'Security Code',
			'security_name' => 'Security Name',
			'buy_sell' => 'Buy Sell',
			'ccy' => 'Ccy',
			'price' => 'Price',
			'quantity' => 'Quantity',
			'trade_amount' => 'Trade Amount',
			'commission' => 'Commission',
			'sales_tax' => 'Sales Tax',
			'levy' => 'Levy',
			'vat' => 'Vat',
			'other_charges' => 'Other Charges',
			'gross_settlement_amount' => 'Gross Settlement Amount',
			'wht_on_commission' => 'Wht On Commission',
			'net_settlement_amount' => 'Net Settlement Amount',
			'tc_reference_no' => 'Tc Reference No',
			'tc_reference_id' => 'Tc Reference',
			'match_status' => 'Match Status',
			'cre_dt' => 'Cre Date',
			'user_id' => 'User',
			'update_seq' => 'Update Seq',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('transaction_status',$this->transaction_status,true);
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
		$criteria->compare('fund_code',$this->fund_code,true);
		$criteria->compare('fund_name',$this->fund_name,true);
		$criteria->compare('security_code',$this->security_code,true);
		$criteria->compare('security_name',$this->security_name,true);
		$criteria->compare('buy_sell',$this->buy_sell,true);
		$criteria->compare('ccy',$this->ccy,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('trade_amount',$this->trade_amount);
		$criteria->compare('commission',$this->commission);
		$criteria->compare('sales_tax',$this->sales_tax);
		$criteria->compare('levy',$this->levy);
		$criteria->compare('vat',$this->vat);
		$criteria->compare('other_charges',$this->other_charges);
		$criteria->compare('gross_settlement_amount',$this->gross_settlement_amount);
		$criteria->compare('wht_on_commission',$this->wht_on_commission);
		$criteria->compare('net_settlement_amount',$this->net_settlement_amount);
		$criteria->compare('tc_reference_no',$this->tc_reference_no,true);
		$criteria->compare('tc_reference_id',$this->tc_reference_id,true);
		$criteria->compare('match_status',$this->match_status,true);

		if(!empty($this->cre_dt_date))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'DD') LIKE '%".$this->cre_dt_date."%'");
		if(!empty($this->cre_dt_month))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'MM') LIKE '%".$this->cre_dt_month."%'");
		if(!empty($this->cre_dt_year))
			$criteria->addCondition("TO_CHAR(t.cre_dt,'YYYY') LIKE '%".$this->cre_dt_year."%'");		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('update_seq',$this->update_seq);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}