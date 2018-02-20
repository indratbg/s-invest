<?php

/**
 * This is the model class for table "tdpmenu".
 *
 * The followings are the available columns in table 'tdpmenu':
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string $menu_name
 * @property string $default_url
 * @property integer $menu_order
 * @property integer $is_active
 */
class Fitccancel extends Tbondtrx
{
    public $bond_cd;
    public $buy_sell;
    public $save_flg = 'N';
    public $im_code;
    public $security_code;
    public $price;
    public $face_value;
    public $trx_seq_no;
    public $tempDateCol = array();
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(array(
            array(
                'trx_date',
                'required'
            ),
            array(
                'trx_seq_no,security_code,price, face_value,im_code,save_flg,bond_cd,buy_sell',
                'safe'
            ),
        ), parent::rules());

    }

    public static function getListData($trx_date, $bond_cd, $im_code)
    {
        $sql = "SELECT TRX_SEQ_NO,TRADE_DATE,IM_CODE,SECURITY_CODE, DECODE(BUY_SELL,1,'BUY','SELL')BUY_SELL,
                PRICE, FACE_VALUE FROM FI_TC_DOWNLOAD 
                WHERE trade_date=to_date('$trx_date','dd/mm/yyyy')
                and (security_code = '$bond_cd' or '$bond_cd'='%')
                and (im_code = '$im_code' or '$im_code'='%')
                --and Tc_Reference_Id IS NOT NULL
                and transaction_status='NEWM'
                ORDER BY IM_CODE,SECURITY_CODE,BUY_SELL";
        return $sql;
    }

    public function attributeLabels()
    {
        return array(
            'trx_date'=>'Transaction Date',
            'bond_cd'=>'Security Code',
            'im_code'=>'Invest Management'
        );
    }

    public function executeSpCancel()
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try
        {
            $query = "CALL  SP_CANCEL_FI_TRADE_CONF(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
                                            :P_TRX_SEQ_NO,
                                            :P_ERROR_CODE,
                                            :P_ERROR_MSG)";

            $command = $connection->createCommand($query);
            $command->bindValue(":P_TRX_DATE", $this->trx_date, PDO::PARAM_STR);
            $command->bindValue(":P_TRX_SEQ_NO", $this->trx_seq_no, PDO::PARAM_STR);
            $command->bindParam(":P_ERROR_CODE", $this->error_code, PDO::PARAM_INT, 10);
            $command->bindParam(":P_ERROR_MSG", $this->error_msg, PDO::PARAM_STR, 200);
            $command->execute();
            $transaction->commit();
        }
        catch(Exception $ex)
        {
            $transaction->rollback();
            if ($this->error_code = -999)
                $this->error_msg = $ex->getMessage();
        }

        if ($this->error_code < 0)
            $this->addError('vo_errmsg', 'Error ' . $this->error_code . ' ' . $this->error_msg);

        return $this->error_code;
    }

}
