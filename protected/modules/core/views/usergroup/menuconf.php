<style>
	.lastCollapsable
	{
		font-weight:bold;
	}
	.tree-menu-name,.tree-menu-act-group,.tree-menu-act
	{
	
		font-weight:normal;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Usergroup'=>array('index'),
	);
?>
<?php 
$this->menu=array(
	array('label'=>'Usergroup', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>
<?php AHelper::showFlash($this) ?>
<h1>Add menuconfig: <?php  echo $model->usergroup_name; ?> </h1>
<?php
	Yii::app()->clientscript->registerScript('menu','
		$(".rad-act-group").click(function(){
			var actgroupval = $(this).val();
			var divparentel = $(this).parent().parent();
			var actgroupel  = divparentel.find("."+actgroupval);
			
			divparentel.find("input:checkbox").removeAttr("checked");
			if(actgroupval != "act-1")
			{
				if($(this).is(":checked"))
					actgroupel.attr("checked","checked");
			}
		});
	');
	
 	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'menuconf-form',
		'enableAjaxValidation'=>false,
	));

	$this->widget('CTreeView',array(
        'data'=>$dtTreeView,
        'animated'=>'fast', //quick animation
        'htmlOptions'=>array(
                'class'=>'treeview-red',//there are some classes that ready to use

        ),
	));
	
	echo CHtml::submitButton('Save Changes');

	$this->endWidget(); 
?>