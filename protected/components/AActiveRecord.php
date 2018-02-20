<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AActiveRecord extends CActiveRecord
{
    /**
     * turn this on at inherited class for log user when accessing this record.
     * @var boolean a flag for log create_by,create_dt,update_by,update_dt of 1 single record.
     */
    protected $logRecord 	= false;
	
	public $ip_address = '127.0.0.1';
	public $error_code = -999;
	public $error_msg  = 'Initial Value';
	
	
	public $tempDateCol  = array();  
    
    public function __construct($scenario = 'insert')
	{
        parent::__construct($scenario);
        $this->attachEventHandler('onBeforeSave', '');
    }

    /**
     * By provide '$this->attachEventHandler('onBeforeSave', '');' at inherited class model,
     * this function will run before the record save to database.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter.
     */
    public function onBeforeSave($event)
	{
        //Don't provide 'parent::onBeforeSave($event);' at this function.
        //Because it will call this function multiple times.
        
        if($this->logRecord)
        {
        	if($this->isNewRecord):
	        	if($this->hasAttribute('cre_dt'))
					$this->cre_dt = new CDbExpression("TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')");
					//$this->cre_dt  = Yii::app()->datetime->getDateNow();
					//$this->cre_dt  = "TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')";
					
					
				if($this->hasAttribute('user_cre_id'))
              		$this->user_cre_id =  Yii::app()->user->id;
				
			endif;
			
			if($this->hasAttribute('upd_dt'))
				$this->upd_dt = new CDbExpression("TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')");
				//$this->upd_dt  = Yii::app()->datetime->getDateNow();
				//$this->upd_dt = "TO_DATE('".Yii::app()->datetime->getDateNow()."','YYYY-MM-DD')";
			
			if($this->hasAttribute('upd_by'))
              	$this->upd_by  = Yii::app()->user->id;
			
			
			if($this->hasAttribute('user_id') &&  !$this->hasAttribute('sts_suspended'))	// AH: not master user
				$this->user_id =  Yii::app()->user->id;
			
			
			
			// AR: adding ip address 
			$ip = Yii::app()->request->userHostAddress;
			if($ip=="::1")
				$ip = '127.0.0.1';
			$this->ip_address = $ip;
			
        }
    }

}
?>
