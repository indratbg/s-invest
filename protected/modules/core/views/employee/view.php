<?php
$this->breadcrumbs=array(
	'Employees'=>array('index'),
	$model->employee_cd,
);

$this->menu=array(
	array('label'=>'Employee', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->employee_cd)),
	array('label'=>'Delete','icon'=>'trash','url'=>'#',
    		'linkOptions'=>array('submit'=>array('delete','id'=>$model->employee_cd),
            'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Employee #<?php echo $model->employee_cd; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'employee_cd',
		'company_cd',
		'branch_cd',
		'employee_name',
		'employee_type',
		'email',
		array('name'=>'join_dt','type'=>'date'),
	),
)); ?>


<h3>Identity Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'create_by',
		array('name'=>'create_dttm','type'=>'datetime'),
		'update_by',
		array('name'=>'update_dttm','type'=>'datetime'),
	),
)); ?>
