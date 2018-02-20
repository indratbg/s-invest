<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
	'type'=>'horizontal'
)); ?>\n"; ?>

<h4>Primary Attributes</h4>
<?php $flag = false; ?>
<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
	else if(strpos($column->name,'create_dttm') !== false)
		echo "<h4>Identity Attributes</h4>\n";
	else if(strpos($column->name,'_status') !== false && !$flag){
		echo "<h4>Status</h4>\n";
		$flag = true;
	}else if(strpos($column->name,'is_') !== false && !$flag){
		echo "<h4>Status</h4>\n";
		$flag = true;
	}
?>
	<?php 
		$temp = $this->generateActiveRowSearch($this->modelClass,$column); 
		
		if(strpos($temp,'#') !== false){
			$temp = explode('#',$temp);
			echo "<div class=\"control-group\">\n";
			echo "\t\t<?php echo ".$temp[0]."; ?>\n"; 
			echo "\t\t".$temp[1]."\n"; 
			
			for($i=2;$i<count($temp)-1;$i++){
				$stemp = $temp[$i];
				echo "\t\t\t<?php echo ".$stemp."; ?>\n"; 
			}
			
			echo "\t\t".$temp[count($temp)-1]."\n";
			echo "\t</div>\n";
		}else
			echo "<?php echo ".$temp."; ?>\n"; 

	?>
<?php endforeach; ?>

	<div class="form-actions">
		<?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>