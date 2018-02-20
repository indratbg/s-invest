<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
?>


<style type'text/css'>.help-block.error{display: none;}</style>
<div style="width:320px;border:1px solid grey;padding: 5px;margin:40px auto 40px auto; ">

<h3 class="title">LOGIN</h3>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
	'inlineErrors'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->errorSummary($modeluser); ?>
	<?php AHelper::showFlash($this) ?> <!-- show flash -->

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span2','maxlength'=>20)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span2','maxlength'=>16,'id'=>'passtxt')); ?>
	
    <div id="changepassword" style="display:none">
    	<br />
    	<?php echo $form->passwordFieldRow($modeluser,'old_pass',array('class'=>'span2','minLength'=>6,'value'=>'','disabled'=>'disabled','id'=>'oldpass')); ?>
    	<?php echo $form->passwordFieldRow($modeluser,'new_pass',array('class'=>'span2','minLength'=>6,'value'=>'','disabled'=>'disabled','id'=>'newpass')); ?>
    	<?php echo $form->passwordFieldRow($modeluser,'confirm_pass',array('class'=>'span2','minLength'=>6,'value'=>'','disabled'=>'disabled','id'=>'confirmpass')); ?>
    </div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Login',
			'id'=>'loginbtn'
		)); ?>
	</div>
	<div style="text-align: right"><a href="#" id="forgettxt">Change Password</a></div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<script language="JavaScript">
	//$("#changepassword").hide();
	$("#forgettxt").click(function(){
		$("#changepassword").toggle("medium");
		if($("#passtxt").attr('disabled') == 'disabled'){
			$("#passtxt").attr('disabled',false)
		}else{
			$("#passtxt").attr('disabled','disabled')
		}
		if($("#loginbtn").text() == 'Submit'){
			$("#loginbtn").text('Login');
		}else{
			$("#loginbtn").text('Submit');
		}
		if($("#oldpass").attr('disabled') == 'disabled'){
			$("#oldpass").attr('disabled',false)
		}else{
			$("#oldpass").attr('disabled','disabled')
		}
		if($("#newpass").attr('disabled') == 'disabled'){
			$("#newpass").attr('disabled',false)
		}else{
			$("#newpass").attr('disabled','disabled')
		}
		if($("#confirmpass").attr('disabled') == 'disabled'){
			$("#confirmpass").attr('disabled',false)
		}else{
			$("#confirmpass").attr('disabled','disabled')
		}
	});
	
	$( "#newpass" ).on('input', function() {
		$thisval = $(this).val();
	    if ($thisval.length>16) {
	    	$(this).val($thisval.substr(0,16));
	        alert('Maximum password is 16 characters!');
	    }
	});
</script>