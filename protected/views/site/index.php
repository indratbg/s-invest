<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<br />
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully entered <?php echo CHtml::encode(Yii::app()->name); ?>.</p>

<script>
	// AS : If the range between expiry date and current system date is less than or equal to 3 days then notification appears
	<?php 
		$expdate = Yii::app()->request->cookies['expdate']->value;
		if ($expdate != null){?>
		<?php if ($expdate == 1){?>
			alert("Your password will be expired in <?php echo $expdate?> day. Please change your password now!");
		<?php }else if ($expdate == 0){?>
			alert("Your password will be expired today. Please change your password now!");
		<?php }else if ($expdate <= 3){?>
			alert("Your password will be expired in <?php echo $expdate?> days. Please change your password now!");
		<?php }else{?>
		<?php }?>
	<?php }?>
</script>

