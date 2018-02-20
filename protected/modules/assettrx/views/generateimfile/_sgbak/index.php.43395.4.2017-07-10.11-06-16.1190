<style>
		#tableGenerateexcelforctp
	{
		background-color:#C3D9FF;
	}
	#tableGenerateexcelforctp thead, #tableGenerateexcelforctp tbody
	{
		display:block;
	}
	#tableGenerateexcelforctp tbody
	{
		max-height:300px;
		overflow:auto;
		background-color:#FFFFFF;
	}
</style>
<?php
$this->breadcrumbs=array(
	'Generate Excel for CTP'=>array('index'),
	'List',
);

$this->menu=array(
	array('label'=>'Generate Excel for CTP', 'itemOptions'=>array('style'=>'font-size:30px;font-weight:bold;color:black;margin-left:-17px;margin-top:8px;')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active','style'=>'float:right')),
);
?>

<?php AHelper::showFlash($this) ?> <!-- show flash -->
<?php AHelper::applyFormatting() ?> 

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'Generateexcelforctp-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal'
)); ?>

<?php echo $form->errorSummary(array($model));
	foreach($modelDetail as $row)echo $form->errorSummary(array($row));
	foreach($modelDetailCSV as $row)echo $form->errorSummary(array($row));
?>
<br/>

<input type="hidden" name="scenario" id="scenario" />
<input type="hidden" name="rowCount" id="rowCount" />

<div class="row-fluid">
	<div class="span8">
		<div class="control-group">
		<div class="span7">
			<?php echo $form->datePickerRow($model,'trx_date',array('prepend'=>'<i class="icon-calendar"></i>',
					'placeholder'=>'dd/mm/yyyy','class'=>'tdate span7','options'=>array('format' => 'dd/mm/yyyy'))); ?>
		</div>
		<div class="span1">
			<label>ID</label>
		</div>
		<div class="span2">
			<?php echo $form->textField($model,'trx_id',array('class'=>'span7'));?>
		</div>
		<div class="span2">
			<?php echo $form->checkBox($model,'all',array('value'=>'ALL','onchange'=>'cek_trx_id()'))."&nbsp ALL";?>
		</div>
	</div>
	</div>
	<div class="span4">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id' => 'btnFilter',
				'buttonType' => 'submit',
				'type'=>'primary',
				'label'=>'Retrieve'
			)); ?>
			&emsp;
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id' => 'btnSaveCSV',
				'buttonType' => 'submit',
				'type'=>'primary',
				'label'=>'Save CSV'
			)); ?>
	</div>
</div>


<br />
<!-- Begin Block -->
<div style="background-color: #white; border: 0px solid 00000; overflow: auto; height: 410px;padding-bottom: -20px; width: 100%;margin-top: -20px;">
	<br/>
	
<table id='tableGenerateexcelforctp'  class='table-bordered table-condensed' >
	<thead>
		<tr>
			<th width="90px">Trx Date</th>
			<th width="80px">Value Dt</th>
			<th width="50px">Trx ID</th>
			<th width="50px">Ref</th>
			<th width="100px">Seller</th>
			<th width="115px">Buyer</th>
			<th width="100px">Bond Cd</th>
			<th width="115px">Nominal</th>
			<th width="100px">Price (%)</th>
			<th width="100px">Yield</th>
			<th width="60px"><input type="checkbox" name="checkAll" class="checkAll" id="checkAll" />&nbsp;Xls</th>
			<th width="100px"></th>
		</tr>
	</thead>	
	<tbody>
	<?php $x = 1;
	
		foreach($modelDetail as $row){
	?>
		<tr id="row<?php echo $x ?>"  class="save">
		<td >
			<?php echo $form->textField($row,'trx_date',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][trx_date]','readonly'=>true));?>
			<?php echo $form->textField($row,'trx_seq_no',array('name'=>'Generateexcelforctp['.$x.'][trx_seq_no]','style'=>'display:none'));?>
		</td>
		<td>
			<?php echo $form->textField($row,'value_dt',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][value_dt]','readonly'=>true));?>
		</td>
		<td>
			<?php echo $form->textField($row,'trx_id',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][trx_id]','maxlength'=>5,'readonly'=>true));?>
		</td>
		<td>
			<?php echo $form->textField($row,'trx_ref',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][trx_ref]','style'=>'font-size:8pt;height:20px;','maxlength'=>15,'readonly'=>true));?>
		</td>
		<td>
			<?php  echo $form->textField($row,'seller',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][seller]','style'=>'font-size:8pt;height:20px;','maxlength'=>25,'readonly'=>true));?>
		</td>
		<td>
			<?php  echo $form->textField($row,'buyer',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][buyer]','style'=>'font-size:8pt;height:20px;','readonly'=>true));?>
		</td>
		<td>
			<?php  echo $form->textField($row,'bond_cd',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][bond_cd]','style'=>'font-size:8pt;height:20px;','readonly'=>true));?>
		</td>
		<td>
			<?php  echo $form->textField($row,'nominal',array('class'=>'span tnumber','name'=>'Generateexcelforctp['.$x.'][nominal]','maxlength'=>25,'style'=>'font-size:8pt;height:20px;text-align:right','readonly'=>true));?>
		</td>
		
		<td>
			<?php echo $form->textField($row,'price',array('value'=>number_format((float)$row->price,6,'.',','),'class'=>'span','name'=>'Generateexcelforctp['.$x.'][price]','style'=>'font-size:8pt;height:20px;text-align:right','readonly'=>true));?>
		</td>
		<td>
			<?php echo $form->textField($row,'n_yield',array('class'=>'span ','name'=>'Generateexcelforctp['.$x.'][n_yield]','style'=>'font-size:8pt;height:20px;text-align:right','maxlength'=>18,'readonly'=>false));?>
		</td>
		<td class="save_flg">
			<?php echo $form->checkBox($row,'save_flg',array('class'=>'checkBoxDetail','value' => 'Y','name'=>'Generateexcelforctp['.$x.'][save_flg]')); ?>
		</td>
		<td>
			<?php echo $form->textField($row,'sdh_upload',array('class'=>'span','name'=>'Generateexcelforctp['.$x.'][sdh_upload]','style'=>'display:none','readonly'=>true));?>
			<?php echo $row->sdh_upload;?>
		</td>
	
		</tr>
		<tr>
			<td>
				<label>Counterparty</label>
			</td>
			<td colspan="3">
			<strong><?php echo $row->lawan_name;?></strong>
				<?php echo $form->textField($row,'lawan_name',array('name'=>'Generateexcelforctp['.$x.'][lawan_name]','style'=>'display:none',));?>
			</td>
			<td >
				<label style="text-align: right">Deliverer Cust</label>
			</td>
			<td colspan="1">
				<?php echo $form->dropdownList($row,'lawan_custody_cd',CHtml::listData($custody_cd, 'sr_custody_cd','CustodianCdAndSr'),array('name'=>'Generateexcelforctp['.$x.'][lawan_custody_cd]','class'=>'span','prompt'=>'-Select-'));?>
			</td>
			<td >
				<label style="text-align: right">Receiver Cust</label>
			</td>
			<td colspan="1">
				<?php echo $form->dropdownList($row,'sr_custody_cd',CHtml::listData($custody_cd, 'sr_custody_cd','CustodianCdAndSr'),array('name'=>'Generateexcelforctp['.$x.'][sr_custody_cd]','class'=>'span','prompt'=>'-Select-'));?>
			</td>
			<td>
				<label>Trx Time</label>
			</td>
			<td colspan="2">
				<?php echo $form->textField($row,'trx_time',array('id'=>'datetimepicker','class'=>'span','name'=>'Generateexcelforctp['.$x.'][trx_time]','placeholder'=>'dd/mm/yyy H:i:s'));?>
			</td>
		
			
		</tr>
		
	<?php $x++;} ?>
	</tbody>	
</table>

</div>
<!-- End Block -->


<?php 
 $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal','htmlOptions'=>array('style'=>'width:80%;margin-left:-40%;margin-top:10%;'))
); ?>

  
    <div class="modal-body">
       <?php $this->renderPartial('_showCSV',array(
		'model'=>$model,
		'modelDetailCSV'=>$modelDetailCSV,
		'url'=>$url
		)); ?>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
            'buttonType'=>'submit',
                'type' => 'primary',
                'label' => 'Save to CSV',
                // 'url'=> $this->createUrl('index'),
               'htmlOptions' => array('id'=>'btnSaveFile','class'=>'btn btn-success btn-small'),
                // 'htmlOptions' => array('data-dismiss' => 'modal','class'=>'btn btn-small'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => 'Close',
                'url' => '#',
                'htmlOptions' => array('id'=>'btnCloseXls','data-dismiss' => 'modal', 'class'=>'btn btn-small'),
            )
        ); ?>
    </div>

<?php $this->endWidget(); ?>
<!--

<?php 
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => 'Print All Report',
        'type' => 'primary',
        'htmlOptions' => array(
        	'class'=>'btn btn-small',
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
            'style'=>'margin-left:1em;'
        ),
    )
);

?>-->



<?php $this->endWidget();?>
<?php 	
	$base = Yii::app()->baseUrl;
	$urlDateCss = $base.'/css/jquery.datetimepicker.css';
	$urlDate = $base.'/js/jquery.datetimepicker.js'; 

?>
<link href="<?php echo $urlDateCss ;?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo $urlDate ;?>" type="text/javascript"></script>

<script>

var rowCount = '<?php echo count($modelDetail);?>';
var flg = '<?php echo $flg;?>';
var url = '<?php echo $url;?>'
init();

function init()
{
	$('.tdate').datepicker({'format':'dd/mm/yyyy'});
	$('#datetimepicker').datetimepicker({format:'d/m/y H:i'});
	cek_trx_id();
	if(rowCount==0)
	{
		$('#tableGenerateexcelforctp').hide();
	}
	else
	{
		$('#tableGenerateexcelforctp').show();
	}
	if(flg == 'Y')
	{
		$('#myModal').modal('show');	
	}
	cekAll();
}

	$(window).resize(function() {
		adjustWidth();
	})
	$(window).trigger('resize');
	
	function adjustWidth(){
		var header = $("#tableGenerateexcelforctp").find('thead');
		var firstRow = $("#tableGenerateexcelforctp").find('tbody tr:eq(0)');
		
		firstRow.find('td:eq(0)').css('width',header.find('th:eq(0)').width() + 'px');
		firstRow.find('td:eq(1)').css('width',header.find('th:eq(1)').width() + 'px');
		firstRow.find('td:eq(2)').css('width',header.find('th:eq(2)').width() + 'px');
		firstRow.find('td:eq(3)').css('width',header.find('th:eq(3)').width() + 'px');
		firstRow.find('td:eq(4)').css('width',header.find('th:eq(4)').width() + 'px');
		firstRow.find('td:eq(5)').css('width',header.find('th:eq(5)').width() + 'px');
		firstRow.find('td:eq(6)').css('width',header.find('th:eq(6)').width() + 'px');
		firstRow.find('td:eq(7)').css('width',header.find('th:eq(7)').width() + 'px');
		firstRow.find('td:eq(8)').css('width',header.find('th:eq(8)').width() + 'px');
		firstRow.find('td:eq(9)').css('width',header.find('th:eq(9)').width() + 'px');
		firstRow.find('td:eq(10)').css('width',header.find('th:eq(10)').width() + 'px');
		firstRow.find('td:eq(11)').css('width',(header.find('th:eq(11)').width())-17 + 'px');
	}
	
	$('#btnFilter').click(function(){
		$('#scenario').val('filter');
	})
	$('#btnSaveCSV').click(function(){
		$('#scenario').val('save_csv');
		$('#rowCount').val(rowCount);
	})
	$('#btnSaveFile').click(function(){
		$('#scenario').val('save_file');
	})
	function cek_trx_id()
	{
		if($('#Generateexcelforctp_all').is(':checked'))
		{
			$('#Generateexcelforctp_trx_id').val('');
			$('#Generateexcelforctp_trx_id').prop('disabled',true);
		}
		else
		{
			//$('#Generateexcelforctp_trx_id').val('');
			$('#Generateexcelforctp_trx_id').prop('disabled',false);
		}
	}
	
	
	$('.checkAll').change(function(){
		if($('#checkAll').is(':checked'))
		{
			$('.checkBoxDetail').prop('checked',true);
		}
		else
		{
			$('.checkBoxDetail').prop('checked',false);
		}
	});
	
	$('.checkBoxDetail').change(function(){
		cekAll();
	})
	$('#btnCloseXls').click(function(){
		window.open(window.location.href,'_self');
	})
	
	function cekAll()
	{
		var sign='Y';
		$("#tableGenerateexcelforctp").children('tbody').children('tr.save').each(function()
		{
			var cek = $(this).children('td.save_flg').children('[type=checkbox]').is(':checked');
			//console.log(cek);
			if(!cek){
				sign='N';
			}
		});
		if(sign=='N'){
			$('#checkAll').prop('checked',false)	
		}
		else{
			$('#checkAll').prop('checked',true)
		}
	}
</script>
