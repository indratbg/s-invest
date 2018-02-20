<?php
$this->breadcrumbs=array(
	'Invest Management'=>array('index'),
	'List',
);

$this->menu=array(
	array('label'=>'Invest Management', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
	array('label'=>'Approval','url'=>Yii::app()->createUrl('inbox/im/index'),'icon'=>'check'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('im-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>List Invest Management</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'im-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'filterPosition'=>'',
	'columns'=>array(
		'im_code',
		'im_name',
	      array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'delete'=>array(
                    'url' => 'Yii::app()->createUrl("/master/im/AjxPopDelete", array("id" => $data->primaryKey))',         // AH : change
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