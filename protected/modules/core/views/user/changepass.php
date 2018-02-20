<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'type'=>'horizontal'
)); ?>

    <br />
    
    <h1>Change Password</h1>
    
    <?php AHelper::showFlash($this) ?> <!-- show flash -->
    
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->passwordFieldRow($model,'old_pass',array('class'=>'span3','minLength'=>6)); ?>

	<?php echo $form->passwordFieldRow($model,'new_pass',array('class'=>'span3','minLength'=>6,'id'=>'newpass')); ?>
	
    <?php echo $form->passwordFieldRow($model,'confirm_pass',array('class'=>'span3','minLength'=>6)); ?>
	

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
