<?php
$this->breadcrumbs=array(
    'Menu'=>array('index'),
    (($modelParent == null)?'Webroot':$modelParent->menu_name)=>array('view','id'=>(($modelParent == null)?0:$modelParent->menu_id)),
    'Reorder',
);

$this->menu=array(
    array('label'=>'Menu', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>
<style>
    #dp-menu-ordering {list-style-type: none; width: 50%; margin:0px; padding: 0px; }
    #dp-menu-ordering li {padding:5px; margin: 0px 3px 3px 3px;}
</style>
<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'menu-order-form',
		'enableAjaxValidation'=>false,
	)); 
?>
<?php
	$listMenu = CHtml::listData($model,'menu_id','menu_name');
	$this->widget('zii.widgets.jui.CJuiSortable', array(
	    'items'=>$listMenu,
	    'itemTemplate'=>'<li value="{id}" class="ui-state-default">{content}
	    					<input type="hidden" name="Menu[menu_id][]" value="{id}" />
	    				</li>',
	    'htmlOptions'=>array('id'=>'dp-menu-ordering'),
	    'options'=>array(
	        'delay'=>'200',
	    ),
	));
?>
<div class="form-actions">
   <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Save', 
        )); ?>
   
</div>
<?php $this->endWidget(); ?>