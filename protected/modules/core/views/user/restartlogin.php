<?php 
$this->breadcrumbs=array(
	'User'=>array('index'),
	'Restart Login',
);?>
<?php 
$this->menu=array(
	array('label'=>'User', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'List','url'=>array('index'),'icon'=>'list'),
	array('label'=>'Create','url'=>array('create'),'icon'=>'plus'),
);
?>
<h1>Restart Login For</h1>
<?php AHelper::showFlash($this); ?>

<?php
	Yii::app()->clientScript->registerScript('userlist','
	
		$("#next").click(function(){
	        $("#unregis-userlist option:selected").appendTo("#regis-userlist");
	    });
	    $("#moveall").click(function(){
	        $("#unregis-userlist option").appendTo("#regis-userlist");
	    });
		
	    $("#clear_all").click(function(){
	        $("#regis-userlist").html("");
	    });
		
		$("#btnSaveChanges").click(function(e){
			e.preventDefault();
			$("#regis-userlist option").attr("selected", "selected");
			if($("#regis-userlist option").length == 0)
				$("#flag").val("0");
			$("#regform").submit();
		});
		
	    $("#remove_registered").click(function(){
	        $("#regis-userlist option:selected").remove();
	    });
	
	
		$("#usersearch").submit(function(){	
	        $action = $(this).attr("action");
	        $self = $(this);
	        $data = $self.serialize();
	        $self.find("input").attr("disabled" , "disabled");
	        $.ajax({
	            url     : $action,
	            type    : "post",
	            data    : $data,
	            dataType: "json",
	            success : function(json){
	            	if(json.status == "success"){
		                $("#unregis-userlist").html("");
		                for(var i in json.content.users)
		                {
		                	
		                	if($("#regis-userlist option[value="+json.content.users[i].user_id+"]").length > 0) continue;
		                    	$("#unregis-userlist").append("<option value="+json.content.users[i].user_id+">"+json.content.users[i].user_id_n_name+"</option>");
		                }
					}else{
						alert(json.content);
					}
	            },
	            error : function()
	            {
	                alert("Failed to fetch data from server");
	            },
	            complete: function()
	            {
	                $self.find("input").removeAttr("disabled");
	            }
	        });
	        return false;
	    });	
	');
?>
<div>
    <div style="width:50%;float:left;">
        <div>Inside Application User List</div>
         <?php  echo CHtml::dropDownList('User', '', $listStuckLoginUser , array("style" => 'width: 90%' , 'size'=>'10', 'multiple'=>'multiple' , 'id'=>'unregis-userlist'));?>
        <div align="right" style="padding-right: 10%;">
            <form id="usersearch" style="display: inline;" action="<?php echo $this->createUrl('ajxSearchUser'); ?>" method="post">
                <?php echo CHtml::textField('search'); ?>
                <?php echo CHtml::submitButton('Search',array('id' => 'usersearch')); ?>
            </form>
            <?php echo CHtml::button('>', array("id" => "next")); ?>
            <?php echo CHtml::button('>>' , array('id' => 'moveall'));?>
        </div>
    </div>
    <div style="width:50%;float:left;">
        <div>&nbsp;</div>
        <form method="post" id="regform">
            <?php
				echo CHtml::dropDownList('registereduser[]', '',array(), array("style" => 'width: 90%' , 'size'=>'10' , 'multiple'=>'multiple', 'id'=>'regis-userlist')); 
            ?>
            <div align="left">
                <?php echo CHtml::button('Remove Selected', array("id" => 'remove_registered')); ?>
                <?php echo CHtml::button('Clear All', array("id" => "clear_all")); ?>
                <?php echo CHtml::submitButton('Save Changes',array('id'=>'btnSaveChanges')); ?>
            </div>
            <input type="hidden" name="flag" id="flag" />
        </form>
    </div>
    <div style="clear: both;"></div>
</div>
<hr/>