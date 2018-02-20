<?php if($is_successsave): ?>
<script>
	window.parent.closePopupAndRefreshGrid('menuaction-grid');
</script>
<?php endif; ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'menuaction-form', 
    'enableAjaxValidation'=>false, 
    'type'=>'horizontal' 
)); ?>
    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->textFieldRow($model,'action_url',array('maxlength'=>255,'id'=>'actionurl','onkeyup'=>'lowercase()')); ?>    

    <?php echo $form->dropDownListRow($model, 'group_id', AConstant::$menuactiongroup,array('prompt'=> '-- Choose Action Group --')); ?>

    <?php echo $form->textAreaRow($model, 'menuaction_desc', array('rows'=>5)); ?>
    
   <?php echo $form->checkBoxRow($model,'is_popup_window'); ?>
    
    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>$model->isNewRecord ? 'Create' : 'Save', 
        )); ?>
    </div> 
<script>
	function lowercase(){
		$("#actionurl").val(($("#actionurl").val()).toLowerCase());
	}
</script>
<?php $this->endWidget(); ?>
