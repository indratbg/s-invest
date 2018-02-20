<?php

/**
 * This is the model class for table "MST_TOKEN".
 *
 * The followings are the available columns in table 'MST_TOKEN':
 * @property string $token_cd
 * @property string $user_id
 * @property string $token_date
 * @property string $module
 */
class Token extends AActiveRecord
{
	public $error_msg;
	
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	public function getDbConnection() 
	{
    	return Yii::app()->db;
	}

	public function tableName()
	{
		return 'MST_TOKEN';
	}

	public function rules()
	{
		return array(
			array('user_id', 'length', 'max'=>8),
			array('module', 'length', 'max'=>50),
			array('token_date', 'safe'),
			
			array('token_date', 'application.components.validator.ADatePickerSwitcherValidator'),
			

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('token_cd, user_id, token_date, module,token_date_date,token_date_month,token_date_year', 'safe', 'on'=>'search'),
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
			'token_cd' => 'Token Code',
			'user_id' => 'User',
			'token_date' => 'Token Date',
			'module' => 'Module',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('token_cd',$this->token_cd,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('module',$this->module,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function insertToken($user_id,$module)
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		die($connection);
		//insert token here
		$this->user_id 		= $user_id;
		$this->module 		= $module;
		$this->token_cd 	= $user_id.$module.date("YmdHis");
		$this->token_date 	= date("Y-m-d H:i:s");
		
		try{
			$modelDelete = Token::model()->find("user_id =:user_id AND module =:module", array(':user_id'=>$this->user_id,':module'=>$this->module));
			if($modelDelete != null)
				$modelDelete->delete();
			
			$this->save();
			$transaction->commit();
			
			return $this->token_cd;
		}//end try
		catch(Exception $ex)
		{
			throw new Exception('Error '.$ex);
		}//end catch
		
		return "";
		
	}//end public function insertToken
	
	public function insertToken2($user_id,$module,$random_val,$tablename)
	{
		$connection  = Yii::app()->dbrpt;
		$transaction = $connection->beginTransaction();
		
		//insert token here
		$this->user_id 		= $user_id;
		$this->module 		= $module;
		$this->token_cd 	= $user_id.$module.date("YmdHis");
		$this->token_date 	= date("Y-m-d H:i:s");
		$this->random_value = $random_val;
		$this->tablename	= $tablename;
		
		try{
			$modelDelete = Token::model()->find("user_id =:user_id AND module =:module", array(':user_id'=>$this->user_id,':module'=>$this->module));
			
			if($modelDelete != null)
			{
				$modelDelete->delete();
				
			}
			
			$this->save();
			$transaction->commit();
			
			return $this->token_cd;
		}//end try
		catch(Exception $ex)
		{
			throw new Exception('Error '.$ex);
			$transaction->rollback();
		}//end catch
		
	}//end public function insertToken
	
}



















