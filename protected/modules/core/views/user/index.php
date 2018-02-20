<?php
$this->breadcrumbs=array( 
    'Users'=>array('index'), 
    'List', 
);

$this->menu=array( 
    array('label'=>'User', 'itemOptions'=>array('class'=>'nav-header')), 
    array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
    array('label'=>'Restart Login','url'=>array('restartLogin'),'icon'=>'list'), 
    array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
    array('label'=>'Approval','url'=>Yii::app()->request->baseUrl.'?r=inbox/user/index','icon'=>'list'),
); 

Yii::app()->clientScript->registerScript('search', " 
$('.search-button').click(function(){ 
    $('.search-form').toggle(); 
    return false; 
}); 
$('.search-form form').submit(function(){ 
    $.fn.yiiGridView.update('user-grid', { 
        data: $(this).serialize() 
    }); 
    return false; 
}); 
"); 
?> 

<h1>List of Users</h1> 

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?> 
<div class="search-form" style="display:none"> 
<?php $this->renderPartial('_search',array( 
    'model'=>$model, 
)); ?>
</div><!-- search-form --> 

<?php AHelper::showFlash($this) ?> <!-- show flash --> 

<?php $this->widget('bootstrap.widgets.TbGridView',array( 
    'id'=>'user-grid', 
    'type'=>'striped bordered condensed', 
    'dataProvider'=>$model->search(), 
    'filter'=>$model, 
    'filterPosition'=>'', 
    'columns'=>array(
    	'user_id',
        'user_name',
        array('name'=>'expiry_date','type'=>'date'),
        array('name'=>'sts_suspended','value'=>'AConstant::$susp_status[$data->sts_suspended]'),
        array( 
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
		        'delete'=>array(
		        	'url' => 'Yii::app()->createUrl("/core/user/Delete", array("id" => $data->primaryKey))',			// AH : change
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