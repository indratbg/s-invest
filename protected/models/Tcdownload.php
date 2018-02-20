<?php
class Tcdownload extends ARptForm
{

    public $trx_date;
    public $trx_status;
    public $im_code;
    public $tempDateCol = array();
    public $col_name = array(
        'TRANSACTION_STATUS'=>'Transaction Status',
        'TC_REFERENCE_ID'=>'TC Reference ID',
        'TRADE_DATE'=>'Trade Date',
        'SETTLEMENT_DATE'=>'Settlement Date',
        'BR_CODE'=>'BR Code',
        'IM_CODE'=>'IM Code',
        'FUND_CODE'=>'Fund Code',
        'SECURITY_CODE'=>'Security Code',
        'BUY_SELL'=>'Buy/Sell',
        'PRICE'=>'Price',
        'QUANTITY'=>'Quantity',
        'TRADE_AMOUNT'=>'Trade Amount',
        'COMMISSION'=>'Commission',
        'SALES_TAX'=>'Sales Tax',
        'LEVY'=>'Levy',
        'VAT'=>'VAT',
        'OTHER_CHARGES'=>'Other Charges',
        'GROSS_SETTLEMENT_AMOUNT'=>'Gross Settlement Amount',
        'WHT_ON_COMMISSION'=>'WHT on Commission',
        'NET_SETTLEMENT_AMOUNT'=>'Net Settlement Amount',
        'TC_REFERENCE_NO'=>'TC Reference No.'
    );

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
                'trx_status,trx_date',
                'required'
            ),
            array('im_code','safe')
        );
    }

    public function attributeLabels()
    {
        return array(
            'trx_date'=>'Transaction Date',
            'trx_status'=>'Transaction Status',
             'im_code'=>'Invest Management'
        );
    }

    public function executeSp($im_code)
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try
        {
            $query = "CALL  SP_TC_DOWNLOAD(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
                                            :P_TRX_STATUS,
                                            :P_IM_CODE,
                                            :P_USER_ID,
                                             :P_RAND_VALUE,
                                            :P_vo_errcd,
                                            :P_vo_errmsg)";

            $command = $connection->createCommand($query);
            $command->bindValue(":P_TRX_DATE", $this->trx_date, PDO::PARAM_STR);
            $command->bindValue(":P_TRX_STATUS", $this->trx_status, PDO::PARAM_STR);
             $command->bindValue(":P_IM_CODE", $im_code, PDO::PARAM_STR);
            $command->bindValue(":P_USER_ID", $this->vp_userid, PDO::PARAM_STR);
            $command->bindParam(":P_RAND_VALUE", $this->vo_random_value, PDO::PARAM_INT, 10);
            $command->bindParam(":P_vo_errcd", $this->vo_errcd, PDO::PARAM_INT, 10);
            $command->bindParam(":P_vo_errmsg", $this->vo_errmsg, PDO::PARAM_STR, 200);
            $command->execute();
            $transaction->commit();
        }
        catch(Exception $ex)
        {
            $transaction->rollback();
            if ($this->vo_errcd = -999)
                $this->vo_errmsg = $ex->getMessage();
        }

        if ($this->vo_errcd < 0)
            $this->addError('vo_errmsg', 'Error ' . $this->vo_errcd . ' ' . $this->vo_errmsg);

        return $this->vo_errcd;
    }

}
?>