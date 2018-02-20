<style>
    #tableList {
        background-color: #C3D9FF;
    }
    #tableList thead, #tableList tbody {
        display: block;
    }
    #tableList tbody {
        max-height: 300px;
        overflow: auto;
        background-color: #FFFFFF;
    }

</style>
<?php
$this->breadcrumbs = array(
    'Client Without Fund'=> array('index'),
    'List',
);
$this->menu = array(
    array(
        'label'=>'Client Without Fund',
        'itemOptions'=> array('style'=>'font-size:30px;font-weight:bold;color:black;margin-left:-17px;margin-top:8px;')
    ),
  
);
?>
<?php AHelper::showFlash($this)
?>
<?php AHelper::applyFormatting()
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'List-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal'
    ));
?>
<?php echo $form->errorSummary($modeldummy);?>
<br/>
<div class="row-fluid">
    <div class="control-group">
        <div class="span5">
            <?php echo $form->datePickerRow($modeldummy, 'trx_date', array(
                'prepend'=>'<i class="icon-calendar"></i>',
                'placeholder'=>'dd/mm/yyyy',
                'class'=>'tdate span7',
                'options'=> array('format'=>'dd/mm/yyyy')
            ));
            ?>
        </div>
        <div class="span4">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'id'=>'btnRetrieve',
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>'Retrieve'
            ));
            ?>
        </div>
    </div>
 
</div>
<br />
<table id="tableList" class="table-bordered table-condensed" style="width: 50%">
    <thead>
        <tr>
            <th width="100px"> Date</th>
            <th width="100px"> Client Code</th>
            <th width="350px"> Client Name</th>
        </tr>
    </thead>
    <tbody>
        <?php $x = 1;
        foreach($model as $row){
        ?>
        <tr id="row<?php echo $x ?>" >
           <td>
               <?php echo $form->textField($row,'trx_date',array('name'=>'Clientwofund['.$x.'][trx_date]','class'=>'span','readonly'=>'readonly')) ;?>
           </td>
           <td><?php echo $form->textField($row,'client_cd',array('name'=>'Clientwofund['.$x.'][client_cd]','class'=>'span','readonly'=>'readonly')) ;?></td>
           <td><?php echo $form->textField($row,'client_name',array('name'=>'Clientwofund['.$x.'][client_name]','class'=>'span','readonly'=>'readonly')) ;?></td>
        </tr>

        <?php $x++;
    }
        ?>
    </tbody>
</table>
<?php $this->endWidget(); ?>

<script>
  
  var rowCount = '<?php echo count($model)?>';
  init();
  function init()
  {
      if(rowCount==0)
      {
        $('#tableList').hide();
      }
  }
  
  $(window).resize(function()
    {
        alignColumn();
    })
    $(window).trigger('resize');

    function alignColumn()//align columns in thead and tbody
    {
        var header = $("#tableList").find('tbody tr:eq(0)');
        var firstRow = $("#tableList").find('thead tr');
        header.find('td:eq(0)').css('width', firstRow.find('th:eq(0)').width() + 'px');
        header.find('td:eq(1)').css('width', firstRow.find('th:eq(1)').width() + 'px');
        header.find('td:eq(2)').css('width', firstRow.find('th:eq(2)').width() + 'px');
    }
</script>