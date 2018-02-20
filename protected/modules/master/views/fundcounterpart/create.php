<?php
$this->breadcrumbs=array(
	'Fund Counter Party'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Fundcounterpart', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>

<h1>Create Fund Counter Party</h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>