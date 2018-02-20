<?php
$this->breadcrumbs=array(
	'Usergroups'=>array('index'),
	$model->usergroup_id=>array('view','id'=>$model->usergroup_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Usergroup', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model->usergroup_id),'icon'=>'eye-open'),
);
?>

<h1>Update Usergroup <?php echo $model->usergroup_name; ?></h1>

<?php AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>