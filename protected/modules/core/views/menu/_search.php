<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get', 
    'type'=>'horizontal' 
)); ?>

<!--<h4>Primary Attributes</h4>--> 
    <?php echo $form->textFieldRow($model,'menu_id',array('class'=>'span5')); ?>
    <?php echo $form->textFieldRow($model,'parent_id',array('class'=>'span5')); ?>
    <?php echo $form->textFieldRow($model,'menu_name',array('class'=>'span5','maxlength'=>100)); ?>
    <?php echo $form->textFieldRow($model,'default_url',array('class'=>'span5','maxlength'=>255)); ?>
    <?php echo $form->textFieldRow($model,'menu_order',array('class'=>'span5')); ?>
<h4>Status</h4>
    <?php echo $form->dropDownListRow($model,'is_active',AConstant::$is_flag); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType' => 'submit', 
            'type'=>'primary', 
            'label'=>'Search', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>