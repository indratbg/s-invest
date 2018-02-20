<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'parameter-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>
	
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->dropDownListRow($model, 'prm_cd_1', array('BANKCD'=>'BANKCD','FPAJAK'=>'FPAJAK','TIMOUT'=>'TIMOUT','ARAGIN'=>'ARAGIN'),array('class'=>'span3','prompt'=>'--Select Code 1--')); ?>
	
	<?php echo $form->textFieldRow($model,'prm_cd_2',array('class'=>'span3','maxlength'=>6)); ?>

	<?php echo $form->textFieldRow($model,'prm_desc',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->label($model,'&nbsp',array('class'=>'control-label'));?>
	
	<?php echo $form->textAreaRow($model,'prm_desc2',array('class'=>'span5','maxlength'=>1000,'label'=>false)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
