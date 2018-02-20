<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

<h4>Primary Attributes</h4>
	<?php echo $form->textFieldRow($model,'fund_code',array('class'=>'span5','maxlength'=>25)); ?>
	<?php echo $form->textFieldRow($model,'fund_name',array('class'=>'span5','maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model,'im_code',array('class'=>'span5','maxlength'=>6)); ?>
	<?php echo $form->textFieldRow($model,'counterpart',array('class'=>'span5','maxlength'=>12)); ?>
	<?php echo $form->textFieldRow($model,'fund_type',array('class'=>'span5','maxlength'=>4)); ?>
	<?php echo $form->textFieldRow($model,'portfolio_id',array('class'=>'span5','maxlength'=>10)); ?>
	<?php echo $form->textFieldRow($model,'seller_tax_id',array('class'=>'span5','maxlength'=>20)); ?>
	<?php echo $form->textFieldRow($model,'cre_by',array('class'=>'span5','maxlength'=>8)); ?>
	<div class="control-group">
		<?php echo $form->label($model,'cre_dt',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'cre_dt_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
			<?php echo $form->textField($model,'cre_dt_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
			<?php echo $form->textField($model,'cre_dt_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
		</div>
	</div>
	<?php echo $form->textFieldRow($model,'upd_by',array('class'=>'span5','maxlength'=>8)); ?>
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
