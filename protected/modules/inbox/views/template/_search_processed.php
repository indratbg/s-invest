<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

	<div class="control-group">
		<?php echo $form->label($model,'update_date',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'update_date_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'update_date_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'update_date_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
		</div>
	</div>
	
	<?php echo $form->dropDownListRow($model, 'status', AConstant::getArraySearch('inbox_stat')); ?>
	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5','maxlength'=>10)); ?>
	<?php echo $form->textFieldRow($model,'ip_address',array('class'=>'span5','maxlength'=>15)); ?>
	
	<hr/>
	
	<div class="control-group">
		<?php echo $form->label($model,'approved_date',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'approved_date_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'approved_date_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'approved_date_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
		</div>
	</div>
	
	<?php echo $form->dropDownListRow($model, 'approved_status', AConstant::getArraySearch('inbox_app_stat')); ?>
	<?php echo $form->textFieldRow($model,'approved_user_id',array('class'=>'span5','maxlength'=>10)); ?>
	<?php echo $form->textFieldRow($model,'approved_ip_address',array('class'=>'span5','maxlength'=>15)); ?>
	
	<hr/>
	
	<?php echo $form->textFieldRow($model,'reject_reason',array('class'=>'span5','maxlength'=>200)); ?>
	<?php echo $form->textFieldRow($model,'cancel_reason',array('class'=>'span5','maxlength'=>200)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
