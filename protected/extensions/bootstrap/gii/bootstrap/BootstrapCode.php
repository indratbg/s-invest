<?php
/**
 * BootstrapCode class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('gii.generators.crud.CrudCode');

class BootstrapCode extends CrudCode
{
	public $modeldetail;
	public $idheader;
	
	private $_modeldetailclass;
	private $_tabledetail;
	
	public function getTableDetailSchema()
	{
		return $this->_tabledetail;
	}
	
	public function getModelDetailClass()
	{
		return $this->_modeldetailclass;
	}
	
	
	public function validateModelDetail($attribute,$params)
	{
		if(!empty($this->modeldetail)):
			if($this->hasErrors('modeldetail'))
				return;
			$class=@Yii::import($this->modeldetail,true);
			if(!is_string($class) || !$this->classExists($class))
				$this->addError('model', "Class '{$this->model}' does not exist or has syntax error.");
			elseif(!is_subclass_of($class,'CActiveRecord'))
				$this->addError('model', "'{$this->model}' must extend from CActiveRecord.");
			else
			{
				$table=CActiveRecord::model($class)->tableSchema;
				if($table->primaryKey===null)
					$this->addError('model',"Table '{$table->name}' does not have a primary key.");
				elseif(is_array($table->primaryKey))
					$this->addError('model',"Table '{$table->name}' has a composite primary key which is not supported by crud generator.");
				else
				{
					$this->_modeldetailclass =$class;
					$this->_tabledetail      =$table;
				}
			}
		endif;
	}
	
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'modeldetail'=>'Model Detail',
			'idheader'=>'ID Header connector between header and detail',
		));
	}
	
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('modeldetail', 'filter', 'filter'=>'trim'),
			array('idheader', 'filter', 'filter'=>'trim'),
			array('modeldetail','validateModelDetail'),
		));
	}
	
	public function generateActiveRowForm($modelClass, $column)
	{
		if ($column->type === 'boolean')
			return "\$form->checkBoxRow(\$model,'{$column->name}')";
		else if ($column->type === 'integer' && strpos($column->name,'is_') !== false)
			return "\$form->checkBoxRow(\$model,'{$column->name}')";
		else if ($column->type === 'integer')
			return "\$form->textFieldRow(\$model,'{$column->name}',array('class'=>'span5 tnumber','maxlength'=>$column->size))";
		else if ($column->type === 'string' && strpos($column->name,'_file') !== false)
			return "\$form->fileFieldRow(\$model,'{$column->name}')";
		else if (stripos($column->dbType,'text') !== false)
			return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span5'))";
		else if (stripos($column->dbType,'date') !== false)
			return "\$form->datePickerRow(\$model,'{$column->name}',array('prepend'=>'<i class=\"icon-calendar\"></i>','placeholder'=>'dd/mm/yyyy','class'=>'tdate','options'=>array('format' => 'dd/mm/yyyy')))";
		else
		{
			if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='passwordFieldRow';
			else
				$inputField='textFieldRow';

			if ($column->type!=='string' || $column->size===null)
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5'))";
			else
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5','maxlength'=>$column->size))";
		}
		
	}
	
	 
	public function generateActiveRowSearch($modelClass, $column)
	{
		if ($column->type === 'boolean')
			return "\$form->checkBoxRow(\$model,'{$column->name}')";
		else if ($column->type === 'integer' && strpos($column->name,'is_') !== false)
			return "\$form->dropDownListRow(\$model,'{$column->name}',AConstant::\$is_flag)";
		else if (stripos($column->dbType,'text') !== false)
			return "\$form->textAreaRow(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50, 'class'=>'span5'))";
		else if (stripos($column->dbType,'datetime') !== false){
			$temp  = "\$form->label(\$model,'$column->name',array('class'=>'control-label'))#";
			$temp .= "<div class=\"controls\">#";
			$temp .= "\$form->textField(\$model,'{$column->name}_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd'))#";
			$temp .= "\$form->textField(\$model,'{$column->name}_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm'))#";
			$temp .= "\$form->textField(\$model,'{$column->name}_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy'))#";
			$temp .= "\$form->textField(\$model,'{$column->name}_time',array('maxlength'=>'5','class'=>'span1','placeholder'=>'hh:mm','style'=>'margin-left:10px;'))#";
			$temp .= "</div>";
			return $temp;
		}else if (stripos($column->dbType,'date') !== false){
			$temp  = "\$form->label(\$model,'$column->name',array('class'=>'control-label'))#";
			$temp .= "<div class=\"controls\">#";
			$temp .= "\$form->textField(\$model,'{$column->name}_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd'))#";
			$temp .= "\$form->textField(\$model,'{$column->name}_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm'))#";
			$temp .= "\$form->textField(\$model,'{$column->name}_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy'))#";
			$temp .= "</div>";
			return $temp;
		}else{
			if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='passwordFieldRow';
			else
				$inputField='textFieldRow';

			if ($column->type!=='string' || $column->size===null)
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5'))";
			else
				return "\$form->{$inputField}(\$model,'{$column->name}',array('class'=>'span5','maxlength'=>$column->size))";
		}
	}
}
