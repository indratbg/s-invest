<?php
$this->breadcrumbs = array(
    'Trade Detail Upload'=> array('index'),
    'List',
);
$this->menu = array(
    array(
        'label'=>'Trade Detail Upload',
        'itemOptions'=> array('style'=>'font-size:30px;font-weight:bold;color:black;margin-left:-17px;margin-top:8px;')
    ),
    array(
        'label'=>'List',
        'url'=> array('index'),
        'icon'=>'list',
        'itemOptions'=> array(
            'class'=>'active',
            'style'=>'float:right'
        )
    ),
);
?>
<?php AHelper::showFlash($this)
?>
<?php AHelper::applyFormatting()
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'Tdupload-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal',
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
?>
<?php echo $form->errorSummary(array($model)); ?>
<br/>
<pre>File yang diupload bersumber dari aplikasi S-INVEST yang di download dari Trade Detail(TD) Inquiry, ekstensi file harus *.CSV</pre>
<div class="row-fluid">
    <div class="control-group">
        <div class="span5">
           <?php  echo CHTML::activeFileField($model,'file_upload');?>
        </div>
        <div class="span4">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnUpload',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Upload'
            ));
            ?>
        </div>
    </div>
    
</div>
<?php $this->endWidget(); ?>

