<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
 
$notGenerateField = array('create_dttm','update_dttm','update_by','create_by');
$isFileUpload 	  = false;
foreach($this->tableSchema->columns as $column)
	if(strpos($column->name,'_file') !== false) $isFileUpload = true;

if($isFileUpload):
echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>\n"; 
else:
echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
)); ?>\n"; 
endif;
?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php

foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement || in_array($column->name,$notGenerateField))
		continue;
?>
	<?php echo "<?php echo ".$this->generateActiveRowForm($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	<div class="form-actions">
		<?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>\$model->isNewRecord ? 'Create' : 'Save',
		)); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
