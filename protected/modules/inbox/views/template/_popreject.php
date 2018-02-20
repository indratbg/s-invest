<?php if($is_successsave): ?>
<script>
	window.parent.closePopupModal();
	window.parent.location.reload();
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

	<?php echo $form->textAreaRow($model, 'reject_reason', array('class'=>'span5', 'rows'=>5,'label'=>false)); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Save', 
        )); ?>
    </div> 
<?php $this->endWidget(); ?>
