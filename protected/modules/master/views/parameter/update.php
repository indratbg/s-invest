<?php
$this->breadcrumbs=array(
	'Parameters'=>array('index'),
	$model->prm_cd_1=>array('view','id'=>$model->prm_cd_1),
	'Update',
);

$this->menu=array(
	array('label'=>'Parameter', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model->prm_cd_1),'icon'=>'eye-open'),
);
?>

<h1>Update Bank <?php echo $model->prm_cd_1; ?></h1>


<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>