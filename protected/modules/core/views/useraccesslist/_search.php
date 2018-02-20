<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get', 
    'type'=>'horizontal' 
)); ?>

<h4>Primary Attributes</h4> 
    <?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5','maxlength'=>8)); ?>
    <?php echo $form->textFieldRow($model,'menu_name',array('class'=>'span5','maxlength'=>40)); ?>
    <?php echo $form->textFieldRow($model,'access_level',array('class'=>'span5','maxlength'=>40)); ?>
    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType' => 'submit', 
            'type'=>'primary', 
            'label'=>'Search', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>