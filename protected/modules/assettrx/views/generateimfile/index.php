<?php
$this->breadcrumbs = array(
    'Generate IM File'=> array('index'),
    'List',
);
$this->menu = array(
    array(
        'label'=>'Generate IM File',
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
        'id'=>'Generateimfile-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal'
    ));
?>
<?php echo $form->errorSummary(array($model)); ?>
<br/>
<div class="row-fluid">
    <div class="control-group">
        <div class="span5">
            <?php echo $form->datePickerRow($model, 'trx_date', array(
                'prepend'=>'<i class="icon-calendar"></i>',
                'placeholder'=>'dd/mm/yyyy',
                'class'=>'tdate span7',
                'options'=> array('format'=>'dd/mm/yyyy')
            ));
            ?>
        </div>
        <div class="span4">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnSaveCSV',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Export CSV/Excel'
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="span5">
            <?php echo $form->dropDownListRow($model, 'im_code', CHtml::listData($im_code, 'im_code', 'im_name'), array(
                'class'=>'span8',
                'prompt'=>'-Select-',
                'style'=>'font-family:courier'
            ));
            ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

