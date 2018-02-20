<?php
$this->breadcrumbs=array(
	'Invest Management'=>array('index'),
	$model->im_code=>array('view','id'=>$model->im_code),
	'Update',
);

$this->menu=array(
	array('label'=>'Invest Management', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model->im_code),'icon'=>'eye-open'),
);
?>

<h1>Update Invest Management <?php echo $model->im_code; ?></h1>


<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>