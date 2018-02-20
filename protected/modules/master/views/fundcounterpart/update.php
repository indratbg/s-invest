<?php
$this->breadcrumbs=array(
	'Fund Counter Party'=>array('index'),
	$model->fund_code=>array('view','id'=>$model->fund_code),
	'Update',
);

$this->menu=array(
	array('label'=>'Fundcounterpart', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model->fund_code),'icon'=>'eye-open'),
);
?>

<h1>Update Fund Counter Party <?php echo $model->fund_code; ?></h1>


<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>