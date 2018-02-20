<?php
$this->breadcrumbs=array( 
    'Users'=>array('index'), 
    $model->user_id, 
);

$this->menu=array( 
    array('label'=>'User', 'itemOptions'=>array('class'=>'nav-header')), 
    array('label'=>'List','icon'=>'list','url'=>array('index')), 
    array('label'=>'Create','icon'=>'plus','url'=>array('create')), 
    array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->user_id)), 
); 
?> 

<h1>View User #<?php echo $model->user_id; ?></h1> 

<?php AHelper::showFlash($this) ?> <!-- show flash --> 

<h3>Primary Attributes</h3> 
<?php $this->widget('bootstrap.widgets.TbDetailView',array( 
    'data'=>$model, 
    'attributes'=>array( 
        'user_id',
        'user_name',
        'short_name',
        array('name'=>'expiry_date','type'=>'date'),
        array('name'=>'dept','value'=>Parameter::getParamDesc('USRDEP', $model->dept)),
        array('name'=>'user_level','value'=>AConstant::$user_level[$model->user_level]),
        'def_addr_1',
        'def_addr_2',
        'def_addr_3',
        'post_cd',
        array('name'=>'regn_cd','value'=>Parameter::getParamDesc('REGNCD', $model->regn_cd)),
        'phone_num',
        array('name'=>'ic_type','value'=>Parameter::getParamDesc('IDTYPE', $model->ic_type)),
        'ic_num',
        array('name'=>'sts_suspended','value'=>AConstant::$susp_status[$model->sts_suspended]),
    ), 
)); ?> 


<h3>Identity Attributes</h3> 
<?php $this->widget('bootstrap.widgets.TbDetailView',array( 
    'data'=>$model, 
    'attributes'=>array(
        'user_cre_id',
        array('name'=>'cre_dt','type'=>'datetime'),
        array('name'=>'upd_dt','type'=>'datetime'), 
    ), 
)); ?> 