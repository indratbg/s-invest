<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'usergroup-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
)); ?>
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'usergroup_name',array('class'=>'span5','maxlength'=>20)); ?>
	
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<?php 
	Yii::app()->clientScript->registerScript('dependentmenuaction',"
	
		genCmbMenuAction();
		
		$('#cmbmenuid').change(function(){
			genCmbMenuAction();
		});
		
		function genCmbMenuAction()
		{
			$.ajax({
				'type'    :'POST',
				'url'     :'".$this->createUrl('ajxCmbMenuAction')."',
				'data'    :$('#usergroup-form').serialize(),
				'dataType':'json',
				'success' :function(data){
					if(data.status == 'success'){
						var menu_id  = '".$model->menu_id."';
						$('#cmbmenuactionid').html('');
						$.each(data.content, function(key, value) {
							  
						     $('#cmbmenuactionid')
						         .append($('<option></option>')
						         .attr('value',key)
						         .text(value)); 
						});
						
						$('#cmbmenuactionid').val(menu_id);		
					}else{
						alert(data.content);
						$('#cmbmenuactionid').html('');
						$('#cmbmenuactionid').val('');
					}
				}
			});
		}	
	");
?>