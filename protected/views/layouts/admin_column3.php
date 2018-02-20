<?php $this->beginContent('//layouts/main'); ?>
<div id="content" class="row-fluid">
	<div class="span12">
		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
			)); ?>
		<?php endif?>
		<?php
			$this->widget('bootstrap.widgets.TbMenu', array(
			    'type'=>'pills',
			    'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'bs-docs-sidenav')	
			));
		?>
		<?php echo $content; ?>
	</div>
    <div class="clear"></div>
</div>
<?php $this->endContent(); ?>