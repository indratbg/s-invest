<?php
$this->breadcrumbs=array(
	'Fund Client Inbox'=>array('index'),
	'Processed Fund Client',
);

$this->menu=array(
	array('label'=>'Fund Client Inbox', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'Unprocessed','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Processed','url'=>array('indexProcessed'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
	array('label'=>'List','url'=>Yii::app()->request->baseUrl.'?r=master/fundclient/index','icon'=>'list'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});

$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ttempheader-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Processed Fund Client</h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('/template/_search_processed',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'ttempheader-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'filterPosition'=>'',
	'columns'=>array(
		'user_id',
		array('name'=>'update_date','type'=>'datetime'),
		array('name'=>'status','value'=>'AConstant::$inbox_stat[$data->status]'),
		array('name'=>'approved_status','value'=>'AConstant::$inbox_app_stat[$data->approved_status]'),
		'approved_user_id',
		array('name'=>'approved_date','type'=>'datetime'),
		array('name'=>'reject_reason','type'=>'raw','value'=>'nl2br($data->reject_reason)'),
		array(
			'class'	  =>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view}',
		),
	),
)); ?>


<?php
  	AHelper::popupwindow($this, 600, 500);
?>
