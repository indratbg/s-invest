<?php
$this->breadcrumbs=array(
	'Fund Clients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Fund Client', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>

<h1>Create Fund Client</h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>