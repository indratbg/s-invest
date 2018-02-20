<?php
$this->breadcrumbs=array(
	'Parameters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Parameter', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>

<h1>Create Bank</h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>