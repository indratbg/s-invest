<?php
$this->breadcrumbs=array(
	'Fund Counter Party Inbox'=>array('index'),
	'Unprocessed Fund Counter Party',
);

$this->menu=array(
	array('label'=>'Fund Counter Party Inbox', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'Unprocessed','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
	array('label'=>'Processed','url'=>array('indexProcessed'),'icon'=>'list'),
	array('label'=>'List','url'=>Yii::app()->request->baseUrl.'?r=master/fundcounterpart/index','icon'=>'list'),
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

<h1>Unprocessed Fund Counter Party</h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('/template/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'ttempheader-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'filterPosition'=>'',
    
    'bulkActions' => array(
		'actionButtons' => array(
			array(
				'buttonType' => 'button',
				'type' => 'secondary',
				'size' => 'small',
				'id'	=>'btnApproveInbox',
				'icon'=> 'ok',
				'label' => 'Approve Checked',
				'click' => 'js:function(checked_element){
						var temp = new Array();
						for(var i =0;i<checked_element.length;i++)	
							temp[i] = checked_element[i].value;
							
						$.ajax({
							type    :"POST",
							url     :"'.$this->createUrl('approveChecked').'",
							data    :{ arrid  : temp },
							dataType:"html",
							success:function(data){
								window.location.reload();								
							}
						});
					}'
			),
			array(
				'buttonType' => 'button',
				'type'  => 'secondary',
				'size'  => 'small',
				'id'	=> 'btnRejectInbox',
				'icon'  => 'remove',
				'label' => 'Reject Checked',
				'click' => 'js:function(checked_element){
						var temp = "&";
						for(var i =0;i<checked_element.length;i++)	
							temp += ("arrid[]="+checked_element[i].value)+"&";
						temp = temp.substring(0,temp.length -1);
						
						showPopupModal("Reject Reason","'.(Yii::app()->getBaseUrl(true).'/index.php?r=inbox/fundcounterpart/AjxPopRejectChecked').'"+temp);	
				}'
			)
		),
		'checkBoxColumnConfig' => array(
		    'name' => 'id',
		    'value'=> '$data->getPrimaryKey()'
		),
	),
	'columns'=>array(
		'user_id',
		array('name'=>'update_date','type'=>'datetime'),
		array('name'=>'status','value'=>'AConstant::$inbox_stat[$data->status]'),
		'ip_address',
		array(
			'class'	  =>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view}{approve}{reject}',
			'buttons'=>array(
		        'approve'=>array(
		        	'label'=>'approve', 
		            'icon' =>'ok',                          
		            'url'  =>'Yii::app()->createUrl("/inbox/fundcounterpart/approve", array("id" => $data->primaryKey))',				// AH : change 
		         ),
		         'reject'=>array(
		         	'label'=>'reject',
		            'icon'=> 'remove',
		            'url' => 'Yii::app()->createUrl("/inbox/fundcounterpart/AjxPopReject", array("id" => $data->primaryKey))',			// AH : change
		            'click'=>'js:function(e){
		            	e.preventDefault();
						showPopupModal("Reject Reason",this.href);
		            }'
		         ),
        	 )
			
		),
	),
)); ?>


<?php
  	AHelper::popupwindow($this, 600, 500);
?>
