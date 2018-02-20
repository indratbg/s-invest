<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>
<?php
	// AH : change this to adding datetime and number validator
	$notGenerateField = array('cre_dt','upd_dt','cre_user_id');
?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
<?php
	$arrDtOrDttm = NULL;
?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	<?php 
		// AH : this code is for generating comparison
		if(count($columns) > 0):
		echo "//AH: #BEGIN search (datetime || date) additional comparison";		
		foreach($columns as $name=>$column){
			$dbType = $column->dbType;
			if($dbType === 'DATETIME'){ 
				echo "\n\tpublic \${$column->name}_date;\n";
				echo "\tpublic \${$column->name}_month;\n";
				echo "\tpublic \${$column->name}_year;\n";
				echo "\tpublic \${$column->name}_time;\n";
				
				$arrDtOrDttm[] = $column->name.'_date';
				$arrDtOrDttm[] = $column->name.'_month';
				$arrDtOrDttm[] = $column->name.'_year';
				$arrDtOrDttm[] = $column->name.'_time';
			}else if($dbType === 'DATE'){
				echo "\n\tpublic \${$column->name}_date;\n";
				echo "\tpublic \${$column->name}_month;\n";
				echo "\tpublic \${$column->name}_year;\n";
				
				$arrDtOrDttm[] = $column->name.'_date';
				$arrDtOrDttm[] = $column->name.'_month';
				$arrDtOrDttm[] = $column->name.'_year';
			}
		}
		echo "\t//AH: #END search (datetime || date)  additional comparison\n";
		endif;
	?>
	
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
<?php if($connectionId!='db'):?>
	public function getDbConnection()
	{
		return Yii::app()-><?php echo $connectionId ?>;
	}
<?php endif?>

	public function tableName()
	{
		return '<?php echo strtoupper($tableName); ?>';
	}

	public function rules()
	{
		return array(
<?php $arrDt = $arrNum = null;
	foreach($columns as $name=>$column):
		$dbType = $column->dbType;	
		if($dbType === 'DATE' && !in_array($column->name,$notGenerateField))
			$arrDt[] = $column->name;
		elseif(strpos($dbType,'NUMBER') !== false && $dbType !== 'NUMBER(1)')
			$arrNum[] = $column->name;
	endforeach;
	if($arrDt !== null):?>		
			array('<?php echo implode(', ',$arrDt); ?>', 'application.components.validator.ADatePickerSwitcherValidatorSP'),
<?php endif; ?><?php if($arrNum !== null):?>		
			array('<?php echo implode(', ',$arrNum); ?>', 'application.components.validator.ANumberSwitcherValidator'),
<?php endif; ?>
			
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
<?php $arrColumns = array_keys($columns); 
if($arrDtOrDttm !== NULL):
	foreach($arrDtOrDttm as $delitem):
		if(($key = array_search($delitem, $arrColumns)) !== false)
			unset($arrColumns[$key]);
	endforeach;
endif;
?>	
			array('<?php echo implode(', ', $arrColumns).(($arrDtOrDttm === null)?'':','.implode(',',$arrDtOrDttm)); ?>', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php if(strpos($name,'_dttm') !== false) echo "'$name' => '".str_replace('Dttm','Datetime',$label)."',\n"; 
				  else if(strpos($name,'_dt') !== false) echo "'$name' => '".str_replace('Dt','Date',$label)."',\n"; 
				  else if(strpos($name,'_cd') !== false) echo "'$name' => '".str_replace('Cd','Code',$label)."',\n";
				  else  echo "'$name' => '$label',\n" ?>
<?php endforeach; ?>
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
<?php
foreach($columns as $name=>$column)
{
	$dbType = $column->dbType;
	if($dbType === 'DATETIME'){
		echo "\n\t\tif(!empty(\$this->{$column->name}_date))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'DD') LIKE '%\"."."\$this->{$column->name}_date".".\"%'\");";
		
		echo "\n\t\tif(!empty(\$this->{$column->name}_month))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'MM') LIKE '%\"."."\$this->{$column->name}_month".".\"%'\");";
		
		echo "\n\t\tif(!empty(\$this->{$column->name}_year))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'YYYY') LIKE '%\"."."\$this->{$column->name}_year".".\"%'\");";
		
		echo "\n\t\tif(!empty(\$this->{$column->name}_time))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'HH:MM') LIKE '%\"."."\$this->{$column->name}_time".".\"%'\");\n";
	
	}
	elseif($dbType === 'DATE')
	{
		echo "\n\t\tif(!empty(\$this->{$column->name}_date))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'DD') LIKE '%\"."."\$this->{$column->name}_date".".\"%'\");";
		
		echo "\n\t\tif(!empty(\$this->{$column->name}_month))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'MM') LIKE '%\"."."\$this->{$column->name}_month".".\"%'\");";
		
		echo "\n\t\tif(!empty(\$this->{$column->name}_year))";
		echo "\n\t\t\t\$criteria->addCondition(\"TO_CHAR(t.{$column->name},'YYYY') LIKE '%\"."."\$this->{$column->name}_year".".\"%'\");";
		
	}elseif(strpos($column->type,'string') !== false){	
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}else{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}