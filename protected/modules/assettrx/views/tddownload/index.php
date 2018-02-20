<?php
$this->breadcrumbs = array(
    'Trade Detail Download'=> array('index'),
    'List',
);
$this->menu = array(
    array(
        'label'=>'Trade Detail Download',
        'itemOptions'=> array('style'=>'font-size:30px;font-weight:bold;color:black;margin-left:-17px;margin-top:8px;')
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
                'options'=> array('format'=>'dd/mm/yyyy'),
                'onchange'=>'checkClient()'
            ));
            ?>
        </div>
        <div class="span4">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnDownload',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Download'
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="span5">
            <?php echo $form->radioButtonListRow($model, 'trx_status', array(
                'A'=>'Active',
                'C'=>'Cancel'
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="span5">
            <?php echo $form->dropDownListRow($model,'im_code',CHtml::listData(Im::model()->findAll(array('select'=>"im_code||' - '||im_name as im_name,im_code",'condition'=>'approved_stat=\'A\'','order'=>'im_code')), 'im_code', 'im_name'),array('class'=>'span7','style'=>'font-family:courier','prompt'=>'-All-'));?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>


<script>

      function checkClient()
        {
              $.ajax({
                    'type'     :'POST',
                    'url'      : '<?php echo Yii::app()->CreateUrl('share/sharesql/CheckSIClient'); ?>',
                    'dataType' : 'json',
                    'data'     : {'trx_date' : $('#Tddownload_trx_date').val(),
                                },
                    'success'  : function(data){
                   if(data.cnt_client>0)
                   {
                        alert('Terdapat client yang belum diinput di fund client');
                   }
                       
                    },
                   
                });
        }
        
    
    
</script>
