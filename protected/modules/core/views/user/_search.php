<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'action'=>Yii::app()->createUrl($this->route), 
    'method'=>'get', 
    'type'=>'horizontal' 
)); ?>

<h4>Primary Attributes</h4> 
    <?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5','maxlength'=>8)); ?>
    <?php echo $form->textFieldRow($model,'user_name',array('class'=>'span5','maxlength'=>40)); ?>
    <div class="control-group">
        <?php echo $form->label($model,'expiry_date',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'expiry_date_date',array('maxlength'=>'2','class'=>'span1','placeholder'=>'dd')); ?>
            <?php echo $form->textField($model,'expiry_date_month',array('maxlength'=>'2','class'=>'span1','placeholder'=>'mm')); ?>
            <?php echo $form->textField($model,'expiry_date_year',array('maxlength'=>'4','class'=>'span1','placeholder'=>'yyyy')); ?>
        </div>
    </div>
    <?php echo $form->textFieldRow($model,'short_name',array('class'=>'span5','maxlength'=>10)); ?>
    <?php echo $form->dropDownListRow($model,'sts_suspended',AConstant::$susp_status,array('class'=>'span3','prompt'=>'All')); ?>
    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType' => 'submit', 
            'type'=>'primary', 
            'label'=>'Search', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>