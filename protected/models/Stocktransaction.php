<?php

/**
 * This is the model class for table "STOCK_TRANSACTION".
 *
 * The followings are the available columns in table 'STOCK_TRANSACTION':
 * @property string $trx_date
 * @property string $trx_due_date
 * @property string $trx_scrip_due_date
 * @property string $trx_mrkt_type
 * @property integer $trx_seq_no
 * @property string $trx_status
 * @property string $trx_desc
 * @property string $trx_type
 * @property string $broker_cd
 * @property string $stk_cd
 * @property double $price
 * @property double $qty
 * @property double $lot
 * @property double $vat
 * @property double $levy
 * @property double $pph
 * @property double $withholding_pph
 * @property double $commision
 * @property double $commision_value
 * @property double $net_commision_value
 * @property double $curr_value
 * @property double $amt_curr_value
 * @property double $net_value
 * @property string $curr_cd
 * @property double $debit
 * @property double $credit
 * @property string $gl_code
 * @property string $sl_code
 * @property string $create_user_id
 * @property string $create_dt
 * @property string $approve_user_id
 * @property string $approve_dt
 * @property string $reject_user_id
 * @property string $reject_dt
 */
class Stocktransaction extends AActiveRecordSP
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $trx_date_date;
	public $trx_date_month;
	public $trx_date_year;

	public $trx_due_date_date;
	public $trx_due_date_month;
	public $trx_due_date_year;

	public $trx_scrip_due_date_date;
	public $trx_scrip_due_date_month;
	public $trx_scrip_due_date_year;

	public $create_dt_date;
	public $create_dt_month;
	public $create_dt_year;

	public $approve_dt_date;
	public $approve_dt_month;
	public $approve_dt_year;

	public $reject_dt_date;
	public $reject_dt_month;
	public $reject_dt_year;
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
		return 'STOCK_TRANSACTION';
	}

	public function rules()
	{
		return array(
		
			array('trx_date, trx_due_date, trx_scrip_due_date, create_dt, approve_dt, reject_dt', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
		
			array('trx_seq_no, price, qty, lot, vat, levy, pph, withholding_pph, commision, commision_value, net_commision_value, curr_value, amt_curr_value, net_value, debit, credit', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('trx_due_date, trx_scrip_due_date, trx_desc, trx_type, broker_cd, stk_cd, gl_code, sl_code, create_user_id', 'required','except'=>'retrieve'),
			array('price, qty, lot, vat, levy, pph, withholding_pph, commision, commision_value, net_commision_value, curr_value, amt_curr_value, net_value, debit, credit', 'numerical'),
			array('trx_mrkt_type, broker_cd', 'length', 'max'=>2),
			array('trx_status', 'length', 'max'=>1),
			array('trx_desc', 'length', 'max'=>100),
			array('trx_type', 'length', 'max'=>20),
			array('stk_cd', 'length', 'max'=>15),
			array('curr_cd, gl_code', 'length', 'max'=>3),
			array('sl_code', 'length', 'max'=>9),
			array('create_user_id, approve_user_id, reject_user_id', 'length', 'max'=>50),
			array('create_dt, approve_dt, reject_dt', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('trx_date, trx_due_date, trx_scrip_due_date, trx_mrkt_type, trx_seq_no, trx_status, trx_desc, trx_type, broker_cd, stk_cd, price, qty, lot, vat, levy, pph, withholding_pph, commision, commision_value, net_commision_value, curr_value, amt_curr_value, net_value, curr_cd, debit, credit, gl_code, sl_code, create_user_id, create_dt, approve_user_id, approve_dt, reject_user_id, reject_dt,trx_date_date,trx_date_month,trx_date_year,trx_due_date_date,trx_due_date_month,trx_due_date_year,trx_scrip_due_date_date,trx_scrip_due_date_month,trx_scrip_due_date_year,create_dt_date,create_dt_month,create_dt_year,approve_dt_date,approve_dt_month,approve_dt_year,reject_dt_date,reject_dt_month,reject_dt_year', 'safe', 'on'=>'search'),
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
			'trx_date' => 'Trx Date',
			'trx_due_date' => 'Trx Due Date',
			'trx_scrip_due_date' => 'Trx Scrip Due Date',
			'trx_mrkt_type' => 'Trx Mrkt Type',
			'trx_seq_no' => 'Trx Seq No',
			'trx_status' => 'Trx Status',
			'trx_desc' => 'Trx Desc',
			'trx_type' => 'Trx Type',
			'broker_cd' => 'Broker Code',
			'stk_cd' => 'Stk Code',
			'price' => 'Price',
			'qty' => 'Qty',
			'lot' => 'Lot',
			'vat' => 'Vat',
			'levy' => 'Levy',
			'pph' => 'Pph',
			'withholding_pph' => 'Withholding Pph',
			'commision' => 'Commision',
			'commision_value' => 'Commision Value',
			'net_commision_value' => 'Net Commision Value',
			'curr_value' => 'Curr Value',
			'amt_curr_value' => 'Amt Curr Value',
			'net_value' => 'Net Value',
			'curr_cd' => 'Curr Code',
			'debit' => 'Debit',
			'credit' => 'Credit',
			'gl_code' => 'Gl Code',
			'sl_code' => 'Sl Code',
			'create_user_id' => 'Create User',
			'create_dt' => 'Create Date',
			'approve_user_id' => 'Approve User',
			'approve_dt' => 'Approve Date',
			'reject_user_id' => 'Reject User',
			'reject_dt' => 'Reject Date',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;

		if(!empty($this->trx_date_date))
			$criteria->addCondition("TO_CHAR(t.trx_date,'DD') LIKE '%".$this->trx_date_date."%'");
		if(!empty($this->trx_date_month))
			$criteria->addCondition("TO_CHAR(t.trx_date,'MM') LIKE '%".$this->trx_date_month."%'");
		if(!empty($this->trx_date_year))
			$criteria->addCondition("TO_CHAR(t.trx_date,'YYYY') LIKE '%".$this->trx_date_year."%'");
		if(!empty($this->trx_due_date_date))
			$criteria->addCondition("TO_CHAR(t.trx_due_date,'DD') LIKE '%".$this->trx_due_date_date."%'");
		if(!empty($this->trx_due_date_month))
			$criteria->addCondition("TO_CHAR(t.trx_due_date,'MM') LIKE '%".$this->trx_due_date_month."%'");
		if(!empty($this->trx_due_date_year))
			$criteria->addCondition("TO_CHAR(t.trx_due_date,'YYYY') LIKE '%".$this->trx_due_date_year."%'");
		if(!empty($this->trx_scrip_due_date_date))
			$criteria->addCondition("TO_CHAR(t.trx_scrip_due_date,'DD') LIKE '%".$this->trx_scrip_due_date_date."%'");
		if(!empty($this->trx_scrip_due_date_month))
			$criteria->addCondition("TO_CHAR(t.trx_scrip_due_date,'MM') LIKE '%".$this->trx_scrip_due_date_month."%'");
		if(!empty($this->trx_scrip_due_date_year))
			$criteria->addCondition("TO_CHAR(t.trx_scrip_due_date,'YYYY') LIKE '%".$this->trx_scrip_due_date_year."%'");		$criteria->compare('trx_mrkt_type',$this->trx_mrkt_type,true);
		$criteria->compare('trx_seq_no',$this->trx_seq_no);
		$criteria->compare('trx_status',$this->trx_status,true);
		$criteria->compare('trx_desc',$this->trx_desc,true);
		$criteria->compare('trx_type',$this->trx_type,true);
		$criteria->compare('broker_cd',$this->broker_cd,true);
		$criteria->compare('stk_cd',$this->stk_cd,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('lot',$this->lot);
		$criteria->compare('vat',$this->vat);
		$criteria->compare('levy',$this->levy);
		$criteria->compare('pph',$this->pph);
		$criteria->compare('withholding_pph',$this->withholding_pph);
		$criteria->compare('commision',$this->commision);
		$criteria->compare('commision_value',$this->commision_value);
		$criteria->compare('net_commision_value',$this->net_commision_value);
		$criteria->compare('curr_value',$this->curr_value);
		$criteria->compare('amt_curr_value',$this->amt_curr_value);
		$criteria->compare('net_value',$this->net_value);
		$criteria->compare('curr_cd',$this->curr_cd,true);
		$criteria->compare('debit',$this->debit);
		$criteria->compare('credit',$this->credit);
		$criteria->compare('gl_code',$this->gl_code,true);
		$criteria->compare('sl_code',$this->sl_code,true);
		$criteria->compare('create_user_id',$this->create_user_id,true);

		if(!empty($this->create_dt_date))
			$criteria->addCondition("TO_CHAR(t.create_dt,'DD') LIKE '%".$this->create_dt_date."%'");
		if(!empty($this->create_dt_month))
			$criteria->addCondition("TO_CHAR(t.create_dt,'MM') LIKE '%".$this->create_dt_month."%'");
		if(!empty($this->create_dt_year))
			$criteria->addCondition("TO_CHAR(t.create_dt,'YYYY') LIKE '%".$this->create_dt_year."%'");		$criteria->compare('approve_user_id',$this->approve_user_id,true);

		if(!empty($this->approve_dt_date))
			$criteria->addCondition("TO_CHAR(t.approve_dt,'DD') LIKE '%".$this->approve_dt_date."%'");
		if(!empty($this->approve_dt_month))
			$criteria->addCondition("TO_CHAR(t.approve_dt,'MM') LIKE '%".$this->approve_dt_month."%'");
		if(!empty($this->approve_dt_year))
			$criteria->addCondition("TO_CHAR(t.approve_dt,'YYYY') LIKE '%".$this->approve_dt_year."%'");		$criteria->compare('reject_user_id',$this->reject_user_id,true);

		if(!empty($this->reject_dt_date))
			$criteria->addCondition("TO_CHAR(t.reject_dt,'DD') LIKE '%".$this->reject_dt_date."%'");
		if(!empty($this->reject_dt_month))
			$criteria->addCondition("TO_CHAR(t.reject_dt,'MM') LIKE '%".$this->reject_dt_month."%'");
		if(!empty($this->reject_dt_year))
			$criteria->addCondition("TO_CHAR(t.reject_dt,'YYYY') LIKE '%".$this->reject_dt_year."%'");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}