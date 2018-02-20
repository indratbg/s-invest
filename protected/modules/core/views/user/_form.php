<style>
	.help-inline.error{display: none;}
	.radio.inline label{margin-left: 15px;}
</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row-fluid">
		<div class="span12">
	<?php if($model->isNewRecord):?>
		<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span2','maxlength'=>8,'autocomplete'=>'off')); ?>
	<?php else: ?>
		<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span2','disabled'=>'disabled')); ?>
	<?php endif; ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<?php echo $form->textFieldRow($model,'user_name',array('class'=>'span8')); ?>
		</div>
		<div class="span6">
			<?php echo $form->textFieldRow($model,'short_name',array('class'=>'span8')); ?>	
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<?php echo $form->dropDownListRow($model, 'user_level', User::$USER_LEVEL,array('class'=>'span8','prompt'=>'-Choose User Level-')); ?>
		</div>
		<div class="span6">
			<?php echo $form->dropDownListRow($model, 'dept', User::$USER_DEPARTMENT,array('class'=>'span8','prompt'=>'-Choose Department')); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<?php echo $form->radioButtonListInlineRow($model, 'ic_type', Parameter::getRadioList('IDTYPE',0,3)); ?>
		</div>
		<div class="span6">
			<?php echo $form->textFieldRow($model,'ic_num',array('class'=>'span8')); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<?php echo $form->label($model,'Address',array('class'=>'control-label'));?>
			<?php echo $form->textFieldRow($model,'def_addr_1',array('class'=>'span8','label'=>false)); ?>
			<?php echo $form->label($model,'&nbsp',array('class'=>'control-label'));?>
			<?php echo $form->textFieldRow($model,'def_addr_2',array('class'=>'span8','label'=>false)); ?>
			<?php echo $form->label($model,'&nbsp',array('class'=>'control-label'));?>
			<?php echo $form->textFieldRow($model,'def_addr_3',array('class'=>'span8','label'=>false)); ?>
		</div>
		<div class="span6">
			<?php echo $form->dropDownListRow($model, 'regn_cd', Parameter::getCombo('REGNCD','-Choose Region-'),array('class'=>'span8')); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">		
			<?php echo $form->textFieldRow($model,'post_cd',array('class'=>'span8')); ?>
			<?php echo $form->textFieldRow($model,'phone_num',array('class'=>'span8')); ?>
			<?php echo $form->datePickerRow($model,'expiry_date',array('prepend'=>'<i class="icon-calendar"></i>','placeholder'=>'dd/mm/yyyy','class'=>'tdate span8','options'=>array('format' => 'dd/mm/yyyy'))); ?>
			<?php echo $form->radioButtonListInlineRow($model, 'sts_suspended', AConstant::$susp_status ); ?>
			
			<?php echo $form->passwordFieldRow($model,'new_pass',array('class'=>'span8','autocomplete'=>'off','minLength'=>6,'id'=>'newpass')); ?>
			<?php echo $form->passwordFieldRow($model,'confirm_pass',array('class'=>'span8','autocomplete'=>'off','minLength'=>6,'id'=>'confirmpass')); ?>		
		</div>
		<div class="span6">
			&nbsp;
		</div>
	</div>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<script language="JavaScript">
	$( "#newpass" ).on('input', function() {
		$thisval = $(this).val();
	    if ($thisval.length>16) {
	    	$(this).val($thisval.substr(0,16));
	        alert('Maximum password is 16 characters!');
	    }
	});
</script>