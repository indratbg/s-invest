<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>

$this->menu=array(
	array('label'=>'<?php echo $this->modelClass; ?>', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'View','url'=>array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'icon'=>'eye-open'),
);
?>

<h1>Update <?php echo $this->modelClass." <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>


<?php echo "<?php "; ?>AHelper::applyFormatting() ?> <!-- apply formatting to date and number -->
<?php echo "<?php "; ?>AHelper::showFlash($this) ?> <!-- show flash -->
<?php echo "<?php echo \$this->renderPartial('_form',array('model'=>\$model)); ?>"; ?>