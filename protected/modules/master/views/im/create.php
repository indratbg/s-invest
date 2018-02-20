<?php
$this->breadcrumbs=array(
	'Invest Management'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Invest Management', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>

<h1>Create Invest Management</h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>