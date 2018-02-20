<?php
class Generateimfile extends ARptForm
{

    public $trx_date;
    public $im_code;
    public $tempDateCol = array();
    //SINARMAS
    public $col_name = array(
        'COL1'=>'fundID',
        'COL2'=>'TransRefNo',
        'COL3'=>'TransactionDate',
        'COL4'=>'SettlementDate',
        'COL5'=>'BrokerCode',
        'COL6'=>'StockCode',
        'COL7'=>'TransactionType',
        'COL8'=>'FlagNetting',
        'COL9'=>'Volume',
        'COL10'=>'Price',
        'COL11'=>'Proceed',
        'COL12'=>'Levy',
        'COL13'=>'KPEI',
        'COL14'=>'VAT',
        'COL15'=>'WHT',
        'COL16'=>'CapitalGainTax',
        'COL17'=>'BrokerFee',
        'COL18'=>'NetAmount'
    );
//LIM
    public $col_name_lim = array(
        'COL1'=>'Transaction Status',
        'COL2'=>'TA Reference ID',
        'COL3'=>'TA Reference No.',
        'COL4'=>'Trade Date',
        'COL5'=>'Settlement Date',
        'COL6'=>'IM Code',
        'COL7'=>'BR Code',
        'COL8'=>'Fund Code',
        'COL9'=>'Security Code',
        'COL10'=>'Buy/Sell',
        'COL11'=>'Price',
        'COL12'=>'Quantity',
        'COL13'=>'Trade Amount',
        'COL14'=>'Commission',
        'COL15'=>'Sales Tax',
        'COL16'=>'Levy',
        'COL17'=>'VAT',
        'COL18'=>'Other Charges',
        'COL19'=>'Gross Settlement Amount',
        'COL20'=>'WHT on Commission',
        'COL21'=>'Net Settlement Amount',
        'COL22'=>'Settlement Type',
        'COL23'=>'Remarks',
        'COL24'=>'Cancellation Reason'
    );
//SCHRODER
    public $col_name_sch = array(
        'col1'=>'Portfolio ID',
        'col2'=>'Trans no.',
        'col3'=>'B/S',
        'col4'=>'Trade Date',
        'col5'=>'Settle Date',
        'col6'=>'Security',
        'col7'=>'ISIN',
        'col7'=>'QC',
        'col9'=>'Quantity',
        'col10'=>'Price',
        'col11'=>'Gross Amount',
        'col12'=>'Commission',
        'col13'=>'Trans Levy',
        'col14'=>'VAT',
        'col15'=>'Sales Tax',
        'col16'=>'Other Charges',
        'col17'=>'Total',
        'col18'=>'WHT on',
        'col19'=>'VAT2',
        'col20'=>'Net Settlement Amo',
        'col21'=>'Broker ID',
        'col22'=>'Broker'
    );
    //SYAILENDRA
    public $col_name_sya = array(
        'COL1'=>'BRANCH',
        'COL2'=>'TRANSACTION_DATE',
        'COL3'=>'DUE_DATE',
        'COL4'=>'CLIENT_CODE',
        'COL5'=>'CLIENT_NAME',
        'COL6'=>'ADDRESS_1',
        'COL7'=>'ADDRESS_2',
        'COL8'=>'ADDRESS_3',
        'COL9'=>'ZIP_CODE',
        'COL10'=>'PHONE',
        'COL11'=>'FAX',
        'COL12'=>'STOCK',
        'COL13'=>'L/F',
        'COL14'=>'LOT',
        'COL15'=>'QTY',
        'COL16'=>'PRICE',
        'COL17'=>'BUY/SELL',
        'COL18'=>'MARKET_TYPE',
        'COL19'=>'TOTAL_VALUE',
        'COL20'=>'COMMISSION',
        'COL21'=>'VAT',
        'COL22'=>'LEVY',
        'COL23'=>'SALES_TAX',
        'COL24'=>'WITHHOLDING_PPH',
        'COL25'=>'TOTAL_NET',
        'COL26'=>'NPWP',
        'COL27'=>'PKP',
        'COL28'=>'SALES'
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
                'im_code,trx_date',
                'required'
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'trx_date'=>'Transaction Date',
            'im_code'=>'IM Name'
        );
    }
    public function executeSp()
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try
        {
            $query = "CALL  SP_GEN_CSV_IM_NEW(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
                                            :P_IM_CODE,
                                            :P_USER_ID,
                                          :P_RAND_VALUE,
                                            :P_vo_errcd,
                                            :P_vo_errmsg)";

            $command = $connection->createCommand($query);
            $command->bindValue(":P_TRX_DATE", $this->trx_date, PDO::PARAM_STR);
            $command->bindValue(":P_IM_CODE", $this->im_code, PDO::PARAM_STR);
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