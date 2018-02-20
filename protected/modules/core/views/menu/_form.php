<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'menu-form', 
    'enableAjaxValidation'=>false, 
    'type'=>'horizontal' 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>
     	
    <?php echo $form->dropDownListRow($model, 'parent_id', CHtml::listData(Menu::model()->findAll(array('order'=>'menu_name, menu_id')), 'menu_id', 'menu_name'),array('prompt'=> 'Webroot')); ?>

    <?php echo $form->textFieldRow($model,'menu_name',array('class'=>'span5','maxlength'=>100)); ?>

    <?php echo $form->textFieldRow($model,'default_url',array('class'=>'span5','maxlength'=>255)); ?>

    <?php echo $form->textFieldRow($model,'menu_order',array('class'=>'span5 tnumber','maxlength'=>'')); ?>

    <?php echo $form->checkBoxRow($model,'is_active'); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>$model->isNewRecord ? 'Create' : 'Save', 
        )); ?>
        &nbsp;
         <?php
         if(!$model->isNewRecord):
         $this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Add Detail',
			'size' => 'medium',
			'id'=>'popup-menu',
		));
		 endif;
		?>
    </div>

<?php if(!$model->isNewRecord):
		$this->widget('bootstrap.widgets.TbGridView',array(
			'id'=>'menuaction-grid',
		    'type'=>'striped bordered condensed',
			'dataProvider'=>$modelMenuAction->search(),
			'filter'=>$modelMenuAction,
		    'filterPosition'=>'',
			'columns'=>array(
				'action_url',
				array('name'=>'group_id','value'=>'AConstant::$menuactiongroup[$data->group_id]'),
				'menuaction_desc',
				array('name'=>'is_popup_window','value'=>'AConstant::$is_popup[$data->is_popup_window]'),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'template'=>'{update} {delete}',
					'updateButtonUrl'=>'CHtml::normalizeUrl(array("menu/AjxUpdateDetail", "id"=>$data->menuaction_id))',
					'updateButtonOptions'=>array(
						'onclick'=>'showPopupModal("Update Menu Detail",this); return false;',
					),
					'deleteButtonUrl'=>'CHtml::normalizeUrl(array("menu/deleteDetail", "id"=>$data->menuaction_id))',
				),
			),
		)); 
	 endif;
?>

<?php $param  = array(array('id'=>'popup-menu','title'=>'Tambah Detail','url'=>'AjxCreateDetail','urlparam'=>array('id'=>$model->menu_id)));
  	  AHelper::popupwindow($this, 700, 500, $param);
?>

<?php $this->endWidget(); ?>
