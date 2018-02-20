<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

<h4>Primary Attributes</h4>
	<?php echo $form->dropDownListRow($model, 'prm_cd_1', array('BANKCD'=>'BANKCD','FPAJAK'=>'FPAJAK','TIMOUT'=>'TIMOUT','ARAGIN'=>'ARAGIN'),
											array('class'=>'span3','prompt'=>'--Select Code 1--','id'=>'prmcd1')); ?>
	<?php echo $form->textFieldRow($model,'prm_cd_2',array('class'=>'span5','maxlength'=>6)); ?>
	<?php echo $form->textFieldRow($model,'prm_desc',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'prm_desc2',array('class'=>'span5','maxlength'=>1000)); ?>
	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5','maxlength'=>8)); ?>
	<div class="control-group">
		<?php echo $form->label($model,'cre_dt',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'cre_dt_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'cre_dt_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'cre_dt_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $form->label($model,'upd_dt',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'upd_dt_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'upd_dt_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'upd_dt_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
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

<script>
	$("#prmcd1").change(function(){
		
	});
</script>