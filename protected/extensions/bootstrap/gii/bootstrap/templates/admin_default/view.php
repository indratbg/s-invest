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
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
	array('label'=>'<?php echo $this->modelClass; ?>', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','icon'=>'list','url'=>array('index')),
	array('label'=>'Create','icon'=>'plus','url'=>array('create')),
	array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
);
?>

<h1>View <?php echo $this->modelClass." #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php "; ?>AHelper::showFlash($this) ?> <!-- show flash -->

<h3>Primary Attributes</h3>
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
<?php
$notGenerateField = array('create_dttm','update_dttm','update_by','create_by');
$imagepath        = strtolower($this->modelClass);
foreach($this->tableSchema->columns as $column):
	if(!in_array($column->name,$notGenerateField)):
	  if(strpos($column->name,'_dttm') !== false)		
		echo "\t\tarray('name'=>'".$column->name."','type'=>'datetime'),\n";
	  else if(strpos($column->name,'_dt') !== false)
		echo "\t\tarray('name'=>'".$column->name."','type'=>'date'),\n";
  	  else if(strpos($column->name,'picture') !== false){
		$temp = "\t\tarray('name'=>'".$column->name."', 'type'=>'raw',\n";
		$temp .= "\t\t\t'value'=>CHtml::image(AConstant::getImagePath('$imagepath').\$model->{$column->name}, \$model->{$column->name})),\n";
		echo $temp;
	  }else if($column->type === 'integer' && strpos($column->name,'is_') !== false)
		echo "\t\tarray('name'=>'".$column->name."','value'=>AConstant::\$is_flag[\$model->{$column->name}]),\n";
	  else if($column->type === 'integer' && !$column->autoIncrement)
		echo "\t\tarray('name'=>'".$column->name."','type'=>'number'),\n";
	  else if (stripos($column->dbType,'text') !== false)
		echo "\t\tarray('name'=>'".$column->name."','type'=>'raw'),\n";
	  else
		echo "\t\t'".$column->name."',\n";
	endif;
endforeach;
?>
	),
)); ?>


<h3>Identity Attributes</h3>
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column):
	if(in_array($column->name,$notGenerateField)):
		if(strpos($column->name,'_dttm') !== false)		
			echo "\t\tarray('name'=>'".$column->name."','type'=>'datetime'),\n";
		else if(strpos($column->name,'_dt') !== false)
			echo "\t\tarray('name'=>'".$column->name."','type'=>'date'),\n";
		else
			echo "\t\t'".$column->name."',\n";
	endif;	
endforeach;

?>
	),
)); ?>
