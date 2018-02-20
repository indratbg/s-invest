<?php
$this->breadcrumbs=array(
	'Employees'=>array('index'),
	$model->employee_cd=>array('view','id'=>$model->employee_cd),
	'Update',
);

$this->menu=array(
	array('label'=>'Employee', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model->employee_cd),'icon'=>'eye-open'),
);
?>

<h1>Update Employee <?php echo $model->employee_cd; ?></h1>


<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>