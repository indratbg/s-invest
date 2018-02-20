<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

<h4>Primary Attributes</h4>
	<?php echo $form->textFieldRow($model,'employee_cd',array('class'=>'span5','maxlength'=>50)); ?>
	<?php echo $form->textFieldRow($model,'company_cd',array('class'=>'span5','maxlength'=>20)); ?>
	<?php echo $form->textFieldRow($model,'branch_cd',array('class'=>'span5','maxlength'=>20)); ?>
	<?php echo $form->textFieldRow($model,'employee_name',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'employee_type',array('class'=>'span5','maxlength'=>50)); ?>
	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>200)); ?>
	<div class="control-group">
		<?php echo $form->label($model,'join_dt',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'join_dt_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'join_dt_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'join_dt_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
		</div>
	</div>
	<?php echo $form->textFieldRow($model,'create_by',array('class'=>'span5','maxlength'=>150)); ?>
<h4>Identity Attributes</h4>
	<div class="control-group">
		<?php echo $form->label($model,'create_dttm',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'create_dttm_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'create_dttm_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'create_dttm_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
			<?php echo $form->textField($model,'create_dttm_time',array('maxlength'=>'5','class'=>'span1','placeholder'=>'hh:mm','style'=>'margin-left:10px;')); ?>
		</div>
	</div>
	<?php echo $form->textFieldRow($model,'update_by',array('class'=>'span5','maxlength'=>150)); ?>
	<div class="control-group">
		<?php echo $form->label($model,'update_dttm',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'update_dttm_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'update_dttm_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'update_dttm_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
			<?php echo $form->textField($model,'update_dttm_time',array('maxlength'=>'5','class'=>'span1','placeholder'=>'hh:mm','style'=>'margin-left:10px;')); ?>
		</div>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
