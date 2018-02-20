<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>

	<div class="control-group">
		<?php echo $form->label($modeltc,'Client Code',array('class'=>'control-label')); ?>
		
		<?php echo $form->textField($modeltc,'client_cd',array('class'=>'span3')); ?>
	</div>
	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
