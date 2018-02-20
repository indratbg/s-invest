<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'fundclient-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
    <?php echo $form->textFieldRow($model,'fund_code',array('class'=>'span4','maxlength'=>25)); ?>
	<?php echo $form->textFieldRow($model,'fund_name',array('class'=>'span8','maxlength'=>100)); ?>

	<?php //echo $form->textFieldRow($model,'im_code',array('class'=>'span2','maxlength'=>6)); ?>
	 <?php echo $form->dropDownListRow($model,'im_code',CHtml::listData(Im::model()->findAll(array('select'=>"im_code||' - '||im_name im_name, im_code")), 'im_code', 'im_name'),array('class'=>'span2','style'=>'font-family:courier','prompt'=>'-Choose-'));?>

	<?php echo $form->textFieldRow($model,'client_cd',array('class'=>'span2','maxlength'=>9)); ?>

	<?php echo $form->textFieldRow($model,'fund_type',array('class'=>'span2','maxlength'=>4)); ?>

	<?php echo $form->textFieldRow($model,'portfolio_id',array('class'=>'span3','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'seller_tax_id',array('class'=>'span4','maxlength'=>20)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
