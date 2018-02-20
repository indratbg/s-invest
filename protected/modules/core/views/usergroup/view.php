<?php
$this->breadcrumbs=array(
	'Usergroup'=>array('index'),
	$model->usergroup_id,
);

$this->menu=array(
	array('label'=>'Usergroup', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->usergroup_id)),
);
?>

<h1>View Usergroup #<?php echo $model->usergroup_id; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'usergroup_id',
		'usergroup_name',
		array('name'=>'menu_id','value'=>($model->menu !== NULL)?$model->menu->menu_name:NULL),
		array('name'=>'menuaction_id','value'=>($model->menuaction !== NULL)?$model->menuaction->action_url:NULL),
	),
));
?>