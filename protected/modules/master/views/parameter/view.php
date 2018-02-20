<?php
$this->breadcrumbs=array(
	'Parameters'=>array('index'),
	$model->prm_cd_1,
);

$this->menu=array(
	array('label'=>'Parameter', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->prm_cd_1)),
);
?>

<h1>View Bank <?php echo $model->prm_cd_1; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'prm_cd_1',
		'prm_cd_2',
		'prm_desc',
		'prm_desc2',
	),
)); ?>


<h3>Identity Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		array('name'=>'cre_dt','type'=>'date'),
		array('name'=>'upd_dt','type'=>'date'),
	),
)); ?>
