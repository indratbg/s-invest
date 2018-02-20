<?php

/**
 * This is the model class for table "T_MANY_DETAIL".
 *
 * The followings are the available columns in table 'T_MANY_DETAIL':
 * @property string $update_date
 * @property integer $update_seq
 * @property string $table_name
 * @property integer $record_seq
 * @property string $table_rowid
 * @property string $field_name
 * @property string $field_type
 * @property string $field_value
 * @property string $upd_status
 * @property string $upd_flg
 */
class Tmanydetail extends AActiveRecord
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $update_date_date;
	public $update_date_month;
	public $update_date_year;
	//AH: #END search (datetime || date)  additional comparison
	
	public $record_cnt; 
	
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'T_MANY_DETAIL';
	}

	public function rules()
	{
		return array(
		
			array('update_date', 'application.components.validator.ADatePickerSwitcherValidator'),
			array('update_seq, record_seq', 'application.components.validator.ANumberSwitcherValidator'),
			
			array('table_rowid', 'length', 'max'=>10),
			array('field_type, upd_status, upd_flg', 'length', 'max'=>1),
			array('field_value', 'length', 'max'=>200),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('update_date, update_seq, table_name, record_seq, table_rowid, field_name, field_type, field_value, upd_status, upd_flg,update_date_date,update_date_month,update_date_year', 'safe', 'on'=>'search'),
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
			'update_date' => 'Update Date',
			'update_seq' => 'Update Seq',
			'table_name' => 'Table Name',
			'record_seq' => 'Record Seq',
			'table_rowid' => 'Table Rowid',
			'field_name' => 'Field Name',
			'field_type' => 'Field Type',
			'field_value' => 'Field Value',
			'upd_status' => 'Status',
			'upd_flg' => 'Upd Flg',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;

		if(!empty($this->update_date_date))
			$criteria->addCondition("TO_CHAR(t.update_date,'DD') LIKE '%".$this->update_date_date."%'");
		if(!empty($this->update_date_month))
			$criteria->addCondition("TO_CHAR(t.update_date,'MM') LIKE '%".$this->update_date_month."%'");
		if(!empty($this->update_date_year))
			$criteria->addCondition("TO_CHAR(t.update_date,'YYYY') LIKE '%".$this->update_date_year."%'");		$criteria->compare('update_seq',$this->update_seq);
		/*
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('record_seq',$this->record_seq);
		$criteria->compare('table_rowid',$this->table_rowid,true);
		$criteria->compare('field_name',$this->field_name,true);
		$criteria->compare('field_type',$this->field_type,true);
		$criteria->compare('field_value',$this->field_value,true);
		$criteria->compare('upd_status',$this->upd_status,true);
		$criteria->compare('upd_flg',$this->upd_flg,true);
		*/
		$this->table_name?$criteria->addCondition("table_name = '$this->table_name'"):'';
		$this->record_seq?$criteria->addCondition("record_seq = $this->record_seq"):'';
		$this->table_rowid?$criteria->addCondition("table_rowid = '$this->table_rowid'"):'';
		$this->field_name?$criteria->addCondition("field_name = '$this->field_name'"):'';
		$this->field_type?$criteria->addCondition("field_type = '$this->field_type'"):'';
		$this->field_value?$criteria->addCondition("field_value = '$this->field_value'"):'';
		$this->upd_status?$criteria->addCondition("upd_status = '$this->upd_status'"):'';
		$this->upd_flg?$criteria->addCondition("upd_flg = '$this->upd_flg'"):'';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
/*
	public static function generateModelAttributes(&$modelDetail,$listttempdetail,$parametercol = NULL, $constantcol  = NULL )
	{
		// AH : Distinction for increasing performance
		$listAttributes = $modelDetail->attributes;
		
		if($modelDetail->isNewRecord):	
			foreach ($listttempdetail as $modelttempdetail) 
			{
				foreach($listAttributes as $key => $value)
				{
					$compkey  = strtoupper($key);
					if($compkey == $modelttempdetail->field_name){
						$modelDetail->setAttribute($key,$modelttempdetail->field_value);
						unset($listAttributes[$key]);
						break;
						
					}
				}	
			}
		else:
			foreach ($listttempdetail as $modelttempdetail) 
			{
				$valuettemp  	= trim($modelttempdetail->field_value);
				$keyttemp    	= strtolower($modelttempdetail->field_name);
				$fieldtypettemp = $modelttempdetail->field_type;
				
				$finalattr  = '';
				$type 		= 'STRING';
				
				if($parametercol !== NULL && array_key_exists($keyttemp,$parametercol)){ 
					$finalattr = '<span class="label label-success">NEW : '.Parameter::getParamDesc($parametercol[$keyttemp],$valuettemp).'</span>';
					$type = 'PARAM';
				}else if($constantcol !== NULL && array_key_exists($keyttemp,$constantcol)){
					$finalattr = '<span class="label label-success">NEW : '.AConstant::${$constantcol[$keyttemp]}[$valuettemp].'</span>';
					$type = 'CONSTANT';
				}else if($fieldtypettemp == 'D'){
					$finalattr = '<span class="label label-success">NEW : '.Yii::app()->format->formatDate(Ttempdetail::revertDate($valuettemp)).'</span>';
					$type = 'DATE';
				}else
					$finalattr = '<span class="label label-success">NEW : '.$valuettemp.'</span>';
				
				
				foreach($listAttributes as $keyattr => $valueattr)
				{
					$valueattr = trim($valueattr);	
					if($keyattr == $keyttemp)
					{
						if($valueattr !== NULL && $valueattr !== ''){
							if($type === 'PARAM')
								$finalattr .= '<br/><span class="label label-warning">OLD : '.Parameter::getParamDesc($parametercol[$keyattr],$valueattr).'</span>';
							else if($type === 'CONSTANT')
								$finalattr .= '<br/><span class="label label-warning">OLD : '.AConstant::${$constantcol[$keyattr]}[$valueattr].'</span><br/>';
							else if($type === 'DATE')
							 	$finalattr .= '<br/><span class="label label-warning">OLD : '.Yii::app()->format->formatDate($valueattr).'</span><br/>';
							else
								$finalattr .= '<br/><span class="label label-warning">OLD : '.$valueattr.' </span><br/>';
						}
						
						unset($listAttributes[$keyattr]);
						break;
					}
				}
				$modelDetail->setAttribute($keyttemp,$finalattr);			
			}
			
			foreach($listAttributes as $keyattr => $valueattr)
				$modelDetail->setAttribute($keyattr,'NOT CHANGED');
			
		endif;
	}
*/
	public static function generateModelAttributes2(&$modelDetail,$listtmanydetail)
	{
		// AH : Distinction for increasing performance
		$listAttributes = $modelDetail->attributes;
		
		if($modelDetail->isNewRecord):	
			foreach ($listtmanydetail as $modeltmanydetail) 
			{
				foreach($listAttributes as $key => $value)
				{
					$compkey  = strtoupper($key);
					if($compkey == $modeltmanydetail->field_name){
						$modelDetail->setAttribute($key,$modeltmanydetail->field_value);
						unset($listAttributes[$key]);
						break;
						
					}
				}	
			}
		else:
			foreach($listAttributes as $keyattr => $valueattr)
			{
				$valuetmany  = '';
				foreach ($listtmanydetail as $modeltmanydetail) 
				{
					$keytmany = strtolower($modeltmanydetail->field_name);
					
					if($keyattr == $keytmany)
					{
						$valuetmany  	= $modeltmanydetail->field_value;
						$fieldtypetmany = $modeltmanydetail->field_type;
						
						if($fieldtypetmany == 'D')
							$valuetmany = Yii::app()->format->formatDate(Tmanydetail::revertDate($valuetmany));
						break;
					}
				}
				
				if($valuetmany !== '')
					$modelDetail->setAttribute($keyattr,$valuetmany);
				//else
					//$modelDetail->setAttribute($keyattr,'NOT CHANGED');
			}
			
		endif;
	} 

	public static function reformatDate($dateValue)
	{
		$temp = $dateValue;
		if(!empty($temp)){
			if(strpos($temp,' ')){
				$temp = explode(' ', $temp);
				$temp = $temp[0];
				$temp = DateTime::createFromFormat('Y/m/d',$temp)?DateTime::createFromFormat('Y/m/d',$temp)->format('d M Y'):DateTime::createFromFormat('Y-m-d',$temp)->format('d M Y');
			}
			else if(DateTime::createFromFormat('d/m/Y',$temp))$temp = DateTime::createFromFormat('d/m/Y',$temp)->format('d M Y');
		}
		return $temp;
	}
	
	public static function reformatNumber($numberValue)
	{
		$temp = $numberValue;
		$temp = number_format($temp,2,'.',',');	
		return $temp;
	}

	private static function revertDate($datevalue)
	{
		$temp = $datevalue;
		if(!empty($temp)){
			if(strpos($temp,' ')){
				$temp = explode(' ', $temp);
				$temp = $temp[0];
			}
				 	
			$var = explode('/', $temp);
			if(count($var) == 3)
				return $var[Tmanydetail::getIndexFormat('yy')].'-'.$var[Tmanydetail::getIndexFormat('mm')].'-'.$var[Tmanydetail::getIndexFormat('dd')];
			else 
				return $temp;
		}else
			return $temp;
	}
	
	private static function getIndexFormat($format)
    {
    	$val = "";
    	switch($format)
    	{
    		case Yii::app()->params['datepick_1st']: $val = 0; break;
    		case Yii::app()->params['datepick_2nd']: $val = 1; break;
    		case Yii::app()->params['datepick_3rd']: $val = 2; break;
    	}
    	
    	return $val;
    }
}