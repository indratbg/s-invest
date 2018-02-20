<?php
$this->breadcrumbs=array(
	'Menu'=>array('index'),
	$model->menu_id,
);

$this->menu=array(
	array('label'=>'Menu', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->menu_id)),
);
?>

<h1>View Menu #<?php echo $model->menu_id; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'menu_id',
		array('name'=>'parent_id','type'=>'number'),
		'menu_name',
		'default_url',
		array('name'=>'menu_order','type'=>'number'),
		array('name'=>'is_active','value'=>AConstant::$is_flag[$model->is_active]),
	),
)); ?>

</br>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
	),
)); ?>
