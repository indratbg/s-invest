<?php
$this->breadcrumbs=array(
	'Fund Counter Party'=>array('index'),
	'List',
);

$this->menu=array(
	array('label'=>'Fundcounterpart', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'Approval','url'=>Yii::app()->createUrl('inbox/fundcounterpart/index'),'icon'=>'check')
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('fundcounterpart-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>List Fund Counter Party</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'fundcounterpart-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'filterPosition'=>'',
	'columns'=>array(
		'fund_name',
		'im_code',
		'counterpart',
		'fund_type',
		'portfolio_id',
		'seller_tax_id',
		 array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'delete'=>array(
                    'url' => 'Yii::app()->createUrl("/master/fundcounterpart/AjxPopDelete", array("id" => $data->primaryKey))',         // AH : change
                    'click'=>'js:function(e){
                        e.preventDefault();
                        showPopupModal("Cancel Reason",this.href);
                    }'
                 ),
             )
        ),
	),
)); ?>

<?php
    AHelper::popupwindow($this, 600, 500);
?>
