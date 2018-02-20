<style>
    #tableCancel {
        background-color: #C3D9FF;
    }
    #tableCancel thead, #tableCancel tbody {
        display: block;
    }
    #tableCancel tbody {
        max-height: 300px;
        overflow: auto;
        background-color: #FFFFFF;
    }

</style>
<?php
$this->breadcrumbs = array(
    'Fixed Income Trade Detail Cancel'=> array('index'),
    'List',
);
$this->menu = array(
    array(
        'label'=>'Fixed Income Trade Detail Cancel',
        'itemOptions'=> array('style'=>'font-size:30px;font-weight:bold;color:black;margin-left:-17px;margin-top:8px;')
    ),
  
);
?>
<?php AHelper::showFlash($this)
?>
<?php AHelper::applyFormatting()
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'Fitdcancel-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal'
    ));
?>
<?php echo $form->errorSummary(array($model)); 
       foreach($modelDetail as $row)
       {
           echo $form->errorSummary($row);
       } ?>

<input type="hidden" name="scenario" id="scenario" />
<input type="hidden" name="rowCount" id="rowCount"/>
<br/>
<div class="row-fluid">
    <div class="control-group">
        <div class="span6">
            <?php echo $form->datePickerRow($model, 'trx_date', array(
                'prepend'=>'<i class="icon-calendar"></i>',
                'placeholder'=>'dd/mm/yyyy',
                'class'=>'tdate span7',
                'options'=> array('format'=>'dd/mm/yyyy')
            ));
            ?>
        </div>
        </div>
        <div class="control-group">
            <div class="span6">
            <?php echo $form->textFieldRow($model,'bond_cd',array('class'=>'span3','style'=>'font-family:courier'));?>
         </div>       
        </div>
         <div class="control-group">
        <div class="span5">
            <?php echo $form->dropDownListRow($model,'im_code',CHtml::listData(Im::model()->findAll(array('select'=>"im_code||' - '||im_name as im_name,im_code",'condition'=>'approved_stat=\'A\'','order'=>'im_code')), 'im_code', 'im_name'),array('class'=>'span7','style'=>'font-family:courier','prompt'=>'-All-'));?>
        </div>
        </div>
        <div class="control-group">
            <div class="span4">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnRetrieve',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Retrieve'
            ));
            ?>
            &emsp;
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnCancel',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Cancel'
            ));
            ?>
          </div>
       </div>
       
       
     
 
   
</div>
<br />
<table id="tableCancel" class="table-bordered table-condensed" style="width: 50%">
    <thead>
      
        <tr>
            <th width="100px"> IM Code</th>
            <th width="100px"> Security Code</th>
            <th width="20px"> Beli/Jual</th>
            <th width="150px">Price</th>
            <th width="120px"> Face Value</th>
            <th width="30px"></th>
        </tr>
    </thead>
    <tbody>
        <?php $x = 1;
        foreach($modelDetail as $row){
        ?>
        <tr id="row<?php echo $x ?>" >
           <td>
               <?php echo $form->textField($row,'im_code',array('name'=>'Fitdcancel['.$x.'][im_code]','class'=>'span','readonly'=>'readonly')) ;?>
               <?php echo $form->textField($row,'trx_seq_no',array('name'=>'Fitdcancel['.$x.'][trx_seq_no]','style'=>'display:none')) ;?>
           </td>
           <td><?php echo $form->textField($row,'security_code',array('name'=>'Fitdcancel['.$x.'][security_code]','class'=>'span','readonly'=>'readonly')) ;?></td>
           <td><?php echo $form->textField($row,'buy_sell',array('name'=>'Fitdcancel['.$x.'][buy_sell]','class'=>'span','readonly'=>'readonly')) ;?></td>
           <td><?php echo $form->textField($row,'price',array('name'=>'Fitdcancel['.$x.'][price]','class'=>'span tnumberdec','readonly'=>'readonly','style'=>'text-align:right')) ;?></td>
            <td><?php echo $form->textField($row,'face_value',array('name'=>'Fitdcancel['.$x.'][face_value]','class'=>'span tnumber','readonly'=>'readonly','style'=>'text-align:right')) ;?> </td>
            <td><?php echo $form->checkBox($model,'save_flg',array('name'=>'Fitdcancel['.$x.'][save_flg]','value'=>'Y'));?></td>
        </tr>

        <?php $x++;
    }
        ?>
    </tbody>
</table>

<?php $this->endWidget(); ?>
<script>

    var rowCount = '<?php echo count($modelDetail);?>';
    init();
    function init()
    {
        if(rowCount==0)
        {
            $('#tableCancel').hide();
            $('#btnCancel').prop('disabled',true);
        }   
    }
    

    $('#btnRetrieve').click(function() {
      $('#scenario').val('retrieve');
     });
     $('#btnCancel').click(function() {
      $('#scenario').val('cancel');
      $('#rowCount').val(rowCount);
      });
      $(window).resize(function()
    {
        alignColumn();
    })
    $(window).trigger('resize');

    function alignColumn()//align columns in thead and tbody
    {
        var header = $("#tableCancel").find('tbody tr:eq(0)');
        var firstRow = $("#tableCancel").find('thead tr');
        header.find('td:eq(0)').css('width', firstRow.find('th:eq(0)').width() + 'px');
        header.find('td:eq(1)').css('width', firstRow.find('th:eq(1)').width() + 'px');
        header.find('td:eq(2)').css('width', firstRow.find('th:eq(2)').width() + 'px');
        header.find('td:eq(3)').css('width', firstRow.find('th:eq(3)').width() + 'px');
        header.find('td:eq(4)').css('width', firstRow.find('th:eq(4)').width() + 'px');
        header.find('td:eq(5)').css('width', firstRow.find('th:eq(5)').width() - 17 + 'px');
    }
   
</script>
