<?php
class Fitcdownload extends ARptForm
{

    public $trx_date;
    public $trx_status;
    public $im_code;
    public $tempDateCol = array();
    public $col_name_buy = array(
        'transaction_status'=>'Transaction Status',
        'tc_reference_id'=>'TC Reference ID',
        'data_type'=>'Data Type',
        'tc_reference_no'=>'TC Reference No.',
        'trade_date'=>'Trade Date',
        'settlement_date'=>'Settlement Date',
        'br_code'=>'BR Code',
        'im_code'=>'IM Code',
        'counterparty_code'=>'Counterparty Code',
        'fund_code'=>'Fund Code',
        'security_code'=>'Security Code',
        'buy_sell'=>'Buy/Sell',
        'price'=>'Price',
        'face_value'=>'Face Value',
        'proceeds'=>'Proceeds',
        'last_coupon_date'=>'Last Coupon Date',
        'next_coupon_date'=>'Next Coupon Date',
        'accrued_days'=>'Accrued Days',
        'accrued_interest_amount'=>'Accrued Interest Amount',
        'other_fee'=>'Other Fee',
        'capital_gain_tax'=>'Capital Gain Tax',
        'interest_income_tax'=>'Interest Income Tax',
        'withholding_tax'=>'Withholding Tax',
        'net_proceeds'=>'Net Proceeds',
        'seller_tax_id'=>'Seller\'s Tax ID',
        'purpose_of_transaction'=>'Purpose of Transaction',
    );

    public $col_name_sell = array(
        'transaction_status'=>'Transaction Status',
        'tc_reference_id'=>'TC Reference ID',
        'data_type'=>'Data Type',
        'tc_reference_no'=>'TC Reference No.',
        'trade_date'=>'Trade Date',
        'settlement_date'=>'Settlement Date',
        'br_code'=>'BR Code',
        'im_code'=>'IM Code',
        'counterparty_code'=>'Counterparty Code',
        'fund_code'=>'Fund Code',
        'security_code'=>'Security Code',
        'buy_sell'=>'Buy/Sell',
        'price'=>'Price',
        'face_value'=>'Face Value',
        'proceeds'=>'Proceeds',
        'last_coupon_date'=>'Last Coupon Date',
        'next_coupon_date'=>'Next Coupon Date',
        'accrued_days'=>'Accrued Days',
        'accrued_interest_amount'=>'Accrued Interest Amount',
        'other_fee'=>'Other Fee',
        'capital_gain_tax'=>'Capital Gain Tax',
        'interest_income_tax'=>'Interest Income Tax',
        'withholding_tax'=>'Withholding Tax',
        'net_proceeds'=>'Net Proceeds',
        'seller_tax_id'=>'Seller\'s Tax ID',
        'purpose_of_transaction'=>'Purpose of Transaction',
        'data_type_sell'=>'Data Type',
        'tc_reference_no_sell'=>'TC Reference No.',
        'face_value_sell'=>'Face Value',
        'acquisition_date'=>'Acquisition Date',
        'acquisition_price'=>'Acquisition Price(%)',
        'acquisition_amount'=>'Acquisition Amount',
        'capital_gain'=>'Capital Gain',
        'days_of_holding_interest'=>'Days of Holding Interest',
        'holding_interest_amount'=>'Holding Interest Amount',
        'total_taxable_income'=>'Total Taxable Income',
        'tax_rate_in_perc'=>'Tax Rate in %',
        'tax_amount'=>'Tax Amount'
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
            array(
                'im_code',
                'safe'
            )
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
            $query = "CALL  SP_FI_TC_DOWNLOAD(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
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