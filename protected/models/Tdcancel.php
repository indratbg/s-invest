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
class Tdcancel extends Stocktransaction
{
    //public $trx_date;
    //public $stk_cd;
    public $client_cd;
    public $beli_jual;
    public $save_flg='N';
    public $im_code;
    public $tempDateCol   = array();  
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
   
public function rules()
{
    return array_merge(
    array(
       array('trx_date','required'),
        array('im_code,save_flg,client_cd,beli_jual','safe'),
    
    ),parent::rules()); 
   
}
  
    public static function getListData($trx_date, $stk_cd, $client_cd, $im_code)
    {
        $sql = "SELECT t.trx_date, t.trx_seq_no,t.trx_due_date  ,trx_desc ,t.stk_cd ,t.price ,t.qty ,t.curr_value,decode(t.gl_code,'TBE','B','J')beli_jual ,t.sl_code client_cd
                FROM stock_transaction t,
                 mst_fund_client c, td_download d, mst_im m
                where t.sl_code=c.client_cd
                and c.im_code = m.im_code
                and m.approved_stat='A'
                and t.trx_date=d.trade_date
                and t.trx_status='A'
                and c.approved_stat='A'
                and d.transaction_status='NEWM'
                and t.trx_seq_no = d.trx_seq_no
                --and d.td_reference_id is not null
                AND trx_date=to_date('$trx_date','dd/mm/yyyy') 
                and ((stk_cd='$stk_cd' and '$stk_cd' <> '%') or ('$stk_cd' = '%')) 
                and ((sl_code ='$client_cd' and '$client_cd'<>'%' ) or ('$client_cd'='%'))
                and ((d.im_code ='$im_code' and '$im_code'<>'%' ) or ('$im_code'='%')) 
                order by t.sl_code,t.stk_cd,t.gl_code"; 
        return $sql;
    }

    public function attributeLabels()
    {
        return array(
            'trx_date' => 'Transaction Date',
            'client_cd'=>'Client Code',
            'stk_cd'=>'Stock Code',
            'im_code'=>'Invest Management'
        );
    }

    public function executeSpCancel()
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try
        {
            $query = "CALL  SP_CANCEL_TRADE_DETAIL(TO_DATE(:P_TRX_DATE,'YYYY-MM-DD'),
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