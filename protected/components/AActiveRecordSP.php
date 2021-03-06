<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AActiveRecordSP extends CActiveRecord
{
    /**
     * turn this on at inherited class for log user when accessing this record.
     * @var boolean a flag for log create_by,create_dt,update_by,update_dt of 1 single record.
     */
    protected $logRecord    = false;
    
    
    // AH  
    public $ip_address;
    public $error_code    = -999;
    public $error_msg     = 'Initial Value';
    public $cancel_reason = '';
    public $update_date;
    public $update_seq;
    public $tempDateCol   = array();  
    
    public function __construct($scenario = 'insert')
    {
        parent::__construct($scenario);
        $this->attachEventHandler('onAfterValidate', '');
    }

    /**
     * By provide '$this->attachEventHandler('onBeforeSave', '');' at inherited class model,
     * this function will run before the record save to database.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter.
     */
    public function onAfterValidate($event)
    {
        //Don't provide 'parent::onBeforeSave($event);' at this function.
        //Because it will call this function multiple times.
        
        if($this->logRecord)
        {
            if($this->isNewRecord):
                if($this->hasAttribute('cre_dt'))
                    $this->cre_dt  = Yii::app()->datetime->getDateTimeNow();
                    //$this->cre_dt  = "TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')";
                    //$this->cre_dt = new CDbExpression("TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')");
                
                if($this->hasAttribute('create_dt'))
                    $this->create_dt  = Yii::app()->datetime->getDateTimeNow();
                    
                if($this->hasAttribute('user_cre_id'))
                    $this->user_cre_id =  Yii::app()->user->id;
                
                if($this->hasAttribute('cre_user_id'))
                $this->cre_user_id = Yii::app()->user->id;
                if($this->hasAttribute('userid'))
                    $this->userid = Yii::app()->user->id;
                if($this->hasAttribute('cre_by'))
                    $this->cre_by = Yii::app()->user->id;
            endif;
            if($this->hasAttribute('user_cre_id'))
                    $this->user_cre_id =  Yii::app()->user->id;
            
            if($this->hasAttribute('upd_dt'))
                $this->upd_dt  = Yii::app()->datetime->getDateTimeNow();
                //$this->upd_dt = "TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')";
                //$this->upd_dt = new CDbExpression("TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')");
            
            if($this->hasAttribute('upd_by'))
                $this->upd_by  = Yii::app()->user->id;
            
            
            if($this->hasAttribute('user_id') &&  !$this->hasAttribute('sts_suspended'))    // AH: not master user
                $this->user_id =  Yii::app()->user->id;
            
            if($this->hasAttribute('upd_user_id'))
                $this->upd_user_id = Yii::app()->user->id;
            
            // AR: adding ip address 
            $ip = Yii::app()->request->userHostAddress;
            if($ip=="::1")
                $ip = '127.0.0.1';
            $this->ip_address = $ip;
            
        }
    }

    public function executeSpHeader($exec_status,$menuName)
    {
        $connection  = Yii::app()->db;
        
        try{
            $query  = "CALL SP_T_MANY_HEADER_INSERT(
                        :P_MENU_NAME,
                        :P_STATUS,
                        :P_USER_ID,
                        :P_IP_ADDRESS,
                        :P_CANCEL_REASON,
                        :P_UPDATE_DATE,
                        :P_UPDATE_SEQ,
                        :P_ERROR_CODE,
                        :P_ERROR_MSG)";
                
            $command = $connection->createCommand($query);
            $command->bindValue(":P_MENU_NAME",$menuName,PDO::PARAM_STR);
            $command->bindValue(":P_STATUS",$exec_status,PDO::PARAM_STR);
            $command->bindValue(":P_USER_ID",$this->user_id,PDO::PARAM_STR);            
            $command->bindValue(":P_IP_ADDRESS",$this->ip_address,PDO::PARAM_STR);
            $command->bindValue(":P_CANCEL_REASON",$this->cancel_reason,PDO::PARAM_STR);
            
            $command->bindParam(":P_UPDATE_DATE",$this->update_date,PDO::PARAM_STR,50);
            $command->bindParam(":P_UPDATE_SEQ",$this->update_seq,PDO::PARAM_STR,10);
            $command->bindParam(":P_ERROR_CODE",$this->error_code,PDO::PARAM_INT,10);
            $command->bindParam(":P_ERROR_MSG",$this->error_msg,PDO::PARAM_STR,1000);

            $command->execute();
            
        }catch(Exception $ex){
            if($this->error_code = -999)
                $this->error_msg = $ex->getMessage();
        }   
        
        if($this->error_code < 0)
            $this->addError('error_msg', 'Error '.$this->error_code.' '.$this->error_msg);
        
        return $this->error_code;
    }
    
}
?>
