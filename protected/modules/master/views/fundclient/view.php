<?php
$this->breadcrumbs=array(
	'Fund Clients'=>array('index'),
	$model->fund_code,
);

$this->menu=array(
	array('label'=>'Fund Client', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->fund_code)),
);
?>

<h1>View Fundclient #<?php echo $model->fund_code; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'fund_code',
		'fund_name',
		'im_code',
		'client_cd',
		'fund_type',
		'portfolio_id',
		'seller_tax_id',
	),
)); ?>


<h3>Identity Attributes</h3>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
	array('name'=>'cre_dt','type'=>'datetime'),
	'cre_by',
	array('name'=>'upd_dt','type'=>'datetime'),
	'upd_by'
	),
)); ?>
