<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'List',
);\n";
?>

$this->menu=array(
	array('label'=>'<?php echo $this->modelClass; ?>', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>List <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<?php echo "<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>"; ?>

<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php "; ?>AHelper::showFlash($this) ?> <!-- show flash -->

<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'filterPosition'=>'',
	'columns'=>array(
<?php
$count			  = 0;
$notGenerateField = array('create_dttm','create_by');
$dbType 		  = null;

foreach($this->tableSchema->columns as $column)
{
	$dbType = $column->dbType;
	
	if($column->autoIncrement || in_array($column->name,$notGenerateField) ||
		 strpos($column->name,'_file') !== false || stripos($column->dbType,'text') !== false)
		continue;
	
	if(++$count==7)
		echo "\t\t/*\n";
		
	if($column->type === 'integer' && strpos($column->name,'is_') !== false)
		echo "\t\tarray('name'=>'".$column->name."','value'=>'AConstant::\$is_flag[\$data->".$column->name."]'),\n";
	else if($dbType === 'datetime')	
		echo "\t\tarray('name'=>'".$column->name."','type'=>'datetime'),\n";
	else if($dbType === 'date')
		echo "\t\tarray('name'=>'".$column->name."','type'=>'date'),\n";
	else 
		echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
