<?php
$this->breadcrumbs=array(
	'Invest Management'=>array('index'),
	$model->im_code,
);

$this->menu=array(
	array('label'=>'Invest Management', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->im_code)),
);
?>

<h1>View Invest Management #<?php echo $model->im_code; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'im_code',
		'im_name'
			),
)); ?>


<h3>Identity Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
	'cre_by',
        array('name'=>'cre_dt','type'=>'datetime'),
        'upd_by',
        array('name'=>'upd_dt','type'=>'datetime'),
    
	),
)); ?>
