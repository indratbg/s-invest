<?php
$this->breadcrumbs=array(
	'Usergroups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Usergroups', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
);
?>

<h1>Create Usergroup</h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>