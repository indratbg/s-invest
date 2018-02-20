<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

<h4>Primary Attributes</h4>
	<?php echo $form->textFieldRow($model,'fund_code',array('class'=>'span5','maxlength'=>25)); ?>
	<?php echo $form->textFieldRow($model,'fund_name',array('class'=>'span5','maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model,'im_code',array('class'=>'span5','maxlength'=>6)); ?>
	<?php echo $form->textFieldRow($model,'client_cd',array('class'=>'span5','maxlength'=>9)); ?>
	<?php echo $form->textFieldRow($model,'fund_type',array('class'=>'span5','maxlength'=>4)); ?>
	<?php echo $form->textFieldRow($model,'portfolio_id',array('class'=>'span5','maxlength'=>10)); ?>
	<?php echo $form->textFieldRow($model,'seller_tax_id',array('class'=>'span5','maxlength'=>20)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
