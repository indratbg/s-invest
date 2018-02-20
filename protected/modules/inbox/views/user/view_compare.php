<?php
$this->breadcrumbs=array(
	'User Inbox'=>array('index'),
	$model->update_seq,
);

$this->menu=array(
	array('label'=>'User Inbox', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'Unprocessed','icon'=>'list','url'=>array('index')),
	array('label'=>'Processed','icon'=>'list','url'=>array('indexProcessed')),
);
?>

<h1>View User Inbox #<?php echo $model->update_seq; ?></h1>

<?php AHelper::showFlash($this) ?> <!-- show flash -->

<h4>Data User</h4> 
<div class="row-fluid">
	<div class="span6">
		<?php $this->widget('bootstrap.widgets.TbDetailView',array(
				'data'=>$modelDetail,
				'id'=>'tablecompare',
				'attributes'=>array(
					'user_id',
			        'user_name',
			        'short_name',
			        array('name'=>'password','value'=>'******'),
			        array('name'=>'expiry_date','type'=>'date'),
			        array('name'=>'dept','value'=>Parameter::getParamDesc('USRDEP', $modelDetail->dept)),
			        array('name'=>'user_level','value'=>AConstant::$user_level[$modelDetail->user_level]),
			        'def_addr_1',
			        'def_addr_2',
			        'def_addr_3',
			        'post_cd',
			        array('name'=>'regn_cd','value'=>Parameter::getParamDesc('REGNCD', $modelDetail->regn_cd)),
			        'phone_num',
			        array('name'=>'ic_type','value'=>Parameter::getParamDesc('IDTYPE', $modelDetail->ic_type)),
			        'ic_num',
			        array('name'=>'sts_suspended','value'=>AConstant::$susp_status[$modelDetail->sts_suspended]),
				),
			)); ?>
	</div>
	<div class="span6">
		<?php $this->widget('bootstrap.widgets.TbDetailView',array(
				'data'=>$modelDetailUpd,
				'id'=>'tableupdated',
				'attributes'=>array(
					'user_id',
			        'user_name',
			        'short_name',
			        array('name'=>'password','value'=> $modelDetailUpd->password == $modelDetail->password?'******':'****** [changed]'),
			        array('name'=>'expiry_date','type'=>'date'),
			        array('name'=>'dept','value'=>Parameter::getParamDesc('USRDEP', $modelDetailUpd->dept)),
			        array('name'=>'user_level','value'=>AConstant::$user_level[$modelDetailUpd->user_level]),
			        'def_addr_1',
			        'def_addr_2',
			        'def_addr_3',
			        'post_cd',
			        array('name'=>'regn_cd','value'=>Parameter::getParamDesc('REGNCD', $modelDetailUpd->regn_cd)),
			        'phone_num',
			        array('name'=>'ic_type','value'=>Parameter::getParamDesc('IDTYPE', $modelDetailUpd->ic_type)),
			        'ic_num',
			        array('name'=>'sts_suspended','value'=>AConstant::$susp_status[$modelDetailUpd->sts_suspended]),
				),
			)); ?>
	 </div>
  </div>
  <script>
  		var ctr  = 0;
  		$('#tableupdated tbody tr td').each(function() {
  			var temp  = $(this).html().trim();
  			var temp2 = ($('#tablecompare tbody tr td').get(ctr).innerHTML).trim();
  			
  			console.log(temp+" "+temp2);
  			if(temp != temp2){
  				temp = '<span class="label label-success">'+temp+'</span>';
  				$(this).html(temp);
  			}
  			ctr++;
  		});
  </script>

<h4>Approval Attributes</h4>
<div class="row-fluid">
	<div class="span6">
		<?php $this->widget('bootstrap.widgets.TbDetailView',array(
			'data'=>$model,
			'attributes'=>array(
				array('name'=>'status','value'=>AConstant::$inbox_stat[$model->status]),
				array('name'=>'update_date','type'=>'datetime'),
				'user_id',
				'ip_address',
				array('name'=>'cancel_reason','type'=>'raw','value'=>nl2br($model->cancel_reason)),
			),
		)); ?>
	</div>
	<div class="span6">
		<?php $this->widget('bootstrap.widgets.TbDetailView',array(
			'data'=>$model,
			'attributes'=>array(
				array('name'=>'approved_status','value'=>AConstant::$inbox_app_stat[$model->approved_status]),
				array('name'=>'approved_date','type'=>'datetime'),
				'approved_user_id',
				array('name'=>'reject_reason','type'=>'raw','value'=>nl2br($model->reject_reason)),
			),
		)); ?>
	</div>
</div>

<?php if($model->approved_status == AConstant::INBOX_APP_STAT_ENTRY): ?>	
	<br/>
	<div style="text-align:right;">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type'=>'secondary',
			'icon'=>'ok',
			'url'=>$this->createUrl('approve',array('id'=>$model->primaryKey)),
			'label'=>'Approve',
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type'=>'secondary',
			'icon'=>'remove',
			'url'=>$this->createUrl('reject',array('id'=>$model->primaryKey)),
			'htmlOptions'=>array('class'=>'reject-inbox'),
			'label'=>'Reject',
		)); ?>
	</div>
	<?php 
		$param  = array(array('class'=>'reject-inbox','title'=>'Reject Reason','url'=>'AjxPopReject','urlparam'=>array('id'=>$model->primaryKey,'label'=>false)));
	  	AHelper::popupwindow($this, 600, 500, $param);
	?>
<?php endif; ?>
