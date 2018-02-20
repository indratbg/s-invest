<?php
class Clientwofund extends Stocktransaction
{

    public $trx_date;
    public $client_cd;
    public $client_name;
    public $tempDateCol = array();
  

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array(
                'trx_date',
                'application.components.validator.ADatePickerSwitcherValidatorSP'
            ),
            array(
                'trx_date',
                'required'
            ),
            array('client_cd,client_name','safe')
        );
    }

    public function attributeLabels()
    {
        return array(
            'trx_date'=>'Transaction Date',
        );
    }
    public static function getListData($trx_date)
    {
        $sql="select a.trx_date,a.sl_code client_cd ,c.client_name from stock_transaction a, mst_fund_client b,
            mst_client c
            where a.sl_code=b.client_cd(+)
            and a.sl_code = c.client_cd
            and a.trx_status='A'
            AND B.CLIENT_CD IS NULL
            AND TRX_DATE = to_date('$trx_date','dd/mm/yyyy') ";   
      return $sql;
    }
}
?>