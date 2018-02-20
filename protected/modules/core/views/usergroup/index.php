<?php
$this->breadcrumbs=array(
	'Usergroup'=>array('index'),
	'List',
);


$leftMenuArr     = new ALeftMenuBar($model,'{list}{create}');
$leftMenuContent = array(array('label'=>'User', 'itemOptions'=>array('class'=>'nav-header')));
$leftMenuContent = array_merge($leftMenuContent,$leftMenuArr->getLeftMenu());

$this->menu 	 = $leftMenuContent;

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
<h1>List of Usergroups</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'usergroup-grid',
	'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'filterPosition'=>'',
	'columns'=>array(
		'usergroup_name',
		//array('name'=>'menu_id','value'=>'($data->menu !== null)? $data->menu->menu_name:null '),
		//array('name'=>'menuaction_id','value'=>'($data->menuaction !== null)? $data->menuaction->action_url:null'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions'=>array('width'=>'60px'),
			'template'=>'{user list}{menu config}{update}{delete}',
			'buttons'=>array(
		        'menu config'=>array(
		        		'label'=>'Menu Config',
		                'icon'=>'tasks',                          
		                'url' => 'Yii::app()->createUrl("/core/usergroup/menuconf", array("id" => $data->usergroup_id))',
		         ),
		         'user list'=>array(
		         		'label'=>'User List',
		                'icon'=> 'user',                          
		                'url' => 'Yii::app()->createUrl("/core/usergroup/userlist", array("id" => $data->usergroup_id))',
		         ),
        	 )
		),
	),
)); ?>
