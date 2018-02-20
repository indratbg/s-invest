<?php if($is_successsave): ?>
<script>
	window.parent.closePopupModalAndRedirect('<?php echo Yii::app()->createUrl('/master/parameter/index'); ?>')
</script>
<?php endif; ?>

<style>
	.form-horizontal .controls{margin-left: 0px;}
	.form-actions{text-align: right;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'menuaction-form', 
    'enableAjaxValidation'=>false, 
    'type'=>'horizontal' 
)); ?>
	
	<?php echo $form->errorSummary($model); ?>
	<?php if($model1 != null) echo $form->errorSummary($model1); ?>
	
	<?php echo $form->textAreaRow($model, 'cancel_reason', array('class'=>'span5', 'rows'=>5,'label'=>false)); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Save', 
        )); ?>
    </div> 
<?php $this->endWidget(); ?>
