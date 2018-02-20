<?php
$this->breadcrumbs=array( 
    'User Access List'=>array('index'), 
    'List', 
);

$this->menu=array( 
    array('label'=>'User Access List', 'itemOptions'=>array('class'=>'nav-header')), 
    array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
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

<h1>List of User Access</h1> 

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
        'menu_name',
        'access_level'
    ), 
)); ?>