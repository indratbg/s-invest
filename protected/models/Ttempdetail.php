<?php

/**
 * This is the model class for table "T_TEMP_DETAIL".
 *
 * The followings are the available columns in table 'T_TEMP_DETAIL':
 * @property string $update_date
 * @property string $table_name
 * @property integer $update_seq
 * @property string $field_name
 * @property string $field_type
 * @property string $field_value
 * @property integer $column_id
 */
class Ttempdetail extends AActiveRecord
{
	//AH: #BEGIN search (datetime || date) additional comparison
	public $update_date_date;
	public $update_date_month;
	public $update_date_year;
	//AH: #END search (datetime || date)  additional comparison
	
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
		return 'T_TEMP_DETAIL';
	}

	public function rules()
	{
		return array(
			array('column_id', 'numerical', 'integerOnly'=>true),
			array('field_type', 'length', 'max'=>1),
			array('field_value', 'length', 'max'=>200),
			
			
			array('update_date', 'application.components.validator.ADatePickerSwitcherValidator'),
			array('update_seq, column_id', 'application.components.validator.ANumberSwitcherValidator'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
	
			array('update_date, table_name, update_seq, field_name, field_type, field_value, column_id,update_date_date,update_date_month,update_date_year', 'safe', 'on'=>'search'),
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
			'table_name' => 'Table Name',
			'update_seq' => 'Update Seq',
			'field_name' => 'Field Name',
			'field_type' => 'Field Type',
			'field_value' => 'Field Value',
			'column_id' => 'Column',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;

		if(!empty($this->update_date_date))
			$criteria->addCondition("TO_CHAR(t.update_date,'DD') LIKE '%".($this->update_date_date)."%'");
		if(!empty($this->update_date_month))
			$criteria->addCondition("TO_CHAR(t.update_date,'MM') LIKE '%".($this->update_date_month)."%'");
		if(!empty($this->update_date_year))
			$criteria->addCondition("TO_CHAR(t.update_date,'YYYY') LIKE '%".($this->update_date_year)."%'");		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('update_seq',$this->update_seq);
		$criteria->compare('field_name',$this->field_name,true);
		$criteria->compare('field_type',$this->field_type,true);
		$criteria->compare('field_value',$this->field_value,true);
		$criteria->compare('column_id',$this->column_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


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

	public static function generateModelAttributes2(&$modelDetail,$listttempdetail)
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
			foreach($listAttributes as $keyattr => $valueattr)
			{
				$valuettemp  = '';
				foreach ($listttempdetail as $modelttempdetail) 
				{
					$keyttemp = strtolower($modelttempdetail->field_name);
					
					if($keyattr == $keyttemp)
					{
						$valuettemp  	= $modelttempdetail->field_value;
						$fieldtypettemp = $modelttempdetail->field_type;
						
						if($fieldtypettemp == 'D')
							$valuettemp = Yii::app()->format->formatDate(Ttempdetail::revertDate($valuettemp));
						
						break;
					}
				}
				
				if($valuettemp !== '')
					$modelDetail->setAttribute($keyattr,$valuettemp);
				else
					$modelDetail->setAttribute($keyattr,'NOT CHANGED');
			}
			
		endif;
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
				return $var[Ttempdetail::getIndexFormat('yy')].'-'.$var[Ttempdetail::getIndexFormat('mm')].'-'.$var[Ttempdetail::getIndexFormat('dd')];
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