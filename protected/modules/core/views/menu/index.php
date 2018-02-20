<?php
$this->breadcrumbs=array(
	'Menu'=>array('index'),
	'List',
);
$this->menu=array(
		array('label'=>'Menu', 'itemOptions'=>array('class'=>'nav-header')),
		array('label'=>'List','url'=>array('index'),'icon'=>'list','itemOptions'=>array('class'=>'active')),
);


	Yii::app()->clientscript->registerScript('menu','
		$(".btn-del").click(function(e){
			
			var href = $(this).attr("href");
			var temp = href.lastIndexOf("=")+1;
			var id 	 = href.substring(temp);
			var lirt = $(this).parent().parent();
			var menuname = lirt.find(".tree-menu-name").html();
			
			if(confirm("Are you sure want to delete menu: "+menuname+" ?"))
			{
				$.ajax({
					type 	: "POST",
					url  	: $(this).attr("href"),
					dataType: "html",
					data    : { "id" : id },
					success : function(data) {
						location.href = location.href; 	
					}
				}); 
				
			}
			
			return false;
		});
	');
	
	$this->widget('CTreeView',array(
        'data'=>$dtTreeView,
        'animated'=>'fast', 
        'htmlOptions'=>array(
              'class'=>'treeview-red',//there are some classes that ready to use
        ),
	));
?>
