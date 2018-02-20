<?php
class Fitddownload extends ARptForm
{

    public $trx_date;
    public $trx_status;
    public $im_code;
    public $tempDateCol = array();
    public $col_name_buy = array(
        'transaction_status'=>'Transaction Status',
        'data_type'=>'Data Type',
        'td_reference_id'=>'TD Reference ID',
        'trade_date'=>'Trade Date',
        'settlement_date'=>'Settlement Date',
        'br_code'=>'BR Code',
        'im_code'=>'IM Code',
        'security_code'=>'Security Code',
        'buy_sell'=>'BUY/SELL',
        'price'=>'Price',
        'face_value'=>'Face Value',
        'td_reference_no'=>'TD Reference No.'
    );

    public $col_name_sell = array(
      'transaction_status'=>'Transaction Status',
        'data_type'=>'Data Type',
        'td_reference_id'=>'TD Reference ID',
        'trade_date'=>'Trade Date',
        'settlement_date'=>'Settlement Date',
        'br_code'=>'BR Code',
        'im_code'=>'IM Code',
        'security_code'=>'Security Code',
        'buy_sell'=>'BUY/SELL',
        'price'=>'Price',
        'face_value'=>'Face Value',
        'td_reference_no'=>'TD Reference No.',
        'data_type_sell'=>'Data Type',
        'td_reference_no_sell'=>'TD Reference No.',
        'face_value_sell'=>'Face Value',
        'acquisition_date'=>'Acquisition Date',
        'acquisition_price_perc'=>'Acquisition Price(%)',
        'acquisition_amount'=>'Acquisition Amount'
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
            $query = "CALL  SP_FI_TD_DOWNLOAD(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
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