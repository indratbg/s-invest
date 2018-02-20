<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->username), array('view', 'id'=>$data->username)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nama')); ?>:</b>
	<?php echo CHtml::encode($data->nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hakmenu')); ?>:</b>
	<?php echo CHtml::encode($data->hakmenu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastupdate')); ?>:</b>
	<?php echo CHtml::encode($data->lastupdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastupdateby')); ?>:</b>
	<?php echo CHtml::encode($data->lastupdateby); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>