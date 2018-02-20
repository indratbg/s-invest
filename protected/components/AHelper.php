<?php

class AHelper
{
    
    // AR : create fade animation towards flash 
	public static function showFlash($controller)
	{
		Yii::app()->clientScript->registerScript(
		    '_hideFlash',
		    '$(".alert").animate({opacity: 1.0}, 20000).fadeOut("slow");',
	        CClientScript::POS_READY              
		);
        
        $controller->widget('bootstrap.widgets.TbAlert', array(
            'block'=>true,
            'fade'=>true, 
            'closeText'=>'x', // close link text - if set to false, no close link is displayed
        ));
	}
	
    // AR: function apply formatting
	public static function applyFormatting()
	{
	    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/setting.js');
		Yii::app()->clientScript->registerScript(
		    '__applyformatting',
		    "registerFormatField('.tdate','.tnumber')
		     registerFormatField('.tdetaildate','.tdetailnumber')
		     registerFormatNumberDec('.tnumberdec')
		    ",
		    CClientScript::POS_READY
		);
	}
	
	public static function alphanumericValidator(){
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.alphanumeric.pack.js');
		
		Yii::app()->clientScript->registerScript(
		    '__alphanumeric',
		    "$('.tvalAlphaNum').alphanumeric()
		     $('.tvalAlpha').alpha()
		     $('.tvalNum').numeric()
		    ",
		    CClientScript::POS_READY
		);
	}
	// AR: Applying header detail
	public static function applyHeaderDetail()
	{
	    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/header-detail.js');
	}
	
	// LO: Apply vertical scrollbar to grid view
	public static function applyScrollableGridView($checkInterval = false)
	{
		$intervalScript = $checkInterval? "var interval = 
										setInterval(function()
										{
											checkGridScroll();
										},
										25);
										
									setTimeout(function()
									{
										clearInterval(interval);
									},
									300);" 
								: "";
		
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/grid-scroll.js');
		Yii::app()->clientScript->registerScript(
		    '__applyScrollableGridView',
		    
		    "checkGridScroll();".
		    
		    $intervalScript.
			
			"
			$(document).ajaxStop(function() 
			{
				checkGridScroll();
			});
			
			$(window).resize(function()
			{
				checkGridScroll();
			});
		    ",
		    
		    CClientScript::POS_END
		);
		Yii::app()->clientScript->registerCss(
			'__applyScrollableGridViewCss',
			
			".grid-view>table
		   	{
		   		background-color:#C3D9FF;
		   	}
		   
		   	.grid-view>table thead, .grid-view>table tbody
			{
				display:block;
			}
			
			.grid-view>table tbody
			{
				max-height:320px;
				overflow:auto;
				background-color:#FFFFFF;
			}
			
			.grid-view>table tfoot
			{
				background-color:#FFFFFF;
			}"
		);
	}
	
	//AH: function for registering popup window
	public static function popupwindow($controller,$width,$height,$param = NULL)
	{ 
		$controller->beginWidget('bootstrap.widgets.TbModal',
									array('id'=>'modal-popup','htmlOptions'=>array('style'=>'min-width:'.$width.'px ;max-height:1000px;"'))); ?>
		    <div class="modal-header">
		        <a class="close" data-dismiss="modal">&times;</a>
		        <h4>List Personel</h4>
		    </div>
		    <div class="modal-body" style="overflow-y:auto;padding:0px;">	<!-- AH : change modal min-height - 50px; -->
		    	
		    </div>
		    
 <?php $controller->endWidget(); ?> 	    
			<script> 	
			 	function closePopupModal()
				{
					$('#modal-popup').modal('hide');
				}
				
				function closePopupModalAndRedirect(url)
				{
					$('#modal-popup').modal('hide');
					location.href  = url; 	
				}
				
				function closePopupAndRefreshGrid(grid_id){
					$('#modal-popup').modal('hide');
					$.fn.yiiGridView.update(grid_id);				
				}
				
			    function resizeIframe(height)
			    {
				    // "+60" is a general rule of thumb to allow for differences in
				    // IE & and FF height reporting, can be adjusted as required..
				    var temp = parseInt(height)-15;
				    $('#popup-iframe').height(temp);
				    $('.modal-body').height(temp+' !important');
			    }
				
				function showPopupModal(alink)
				{
					var temp = '<iframe id="popup-iframe" style="width:100%;"'; 
						temp += 'src="'+alink.href+'" scrolling="no"></iframe>';
							
					$('.modal-body').html(temp);	
					$('.modal-body iframe')[0].contentWindow.focus();
					$('#modal-popup').modal('show');
				}
				
				function showPopupModal(atitle,alink)
				{
					var temp = '<iframe id="popup-iframe" style="width:100%;" src="'+alink+'" scrolling="no"></iframe>';
					
					$('.modal-header h4').html(atitle);
					$('.modal-body').html(temp);
					$('.modal-body iframe')[0].contentWindow.focus();
					$('#modal-popup').modal('show');
				}
				
				<?php
				if($param !== NULL):
					foreach($param as $item): 
						$selector = null; 
						if(!empty($item['id']))
							$selector = '#'.$item['id'];
						else if(!empty($item['class']))  
						  	$selector = '.'.$item['class']; 
				?>
					
					$('<?php echo $selector; ?>').click(function (e) {
						e.preventDefault();
						$('.modal-header h4').html('<?php echo $item['title'] ?>');
						
						<?php $urlparam = !empty($item['urlparam'])?$item['urlparam']:array();?>
						var temp = '<iframe id="popup-iframe" style="width:100%;"'; 
							temp += 'src="<?php echo $controller->createUrl($item['url'],$urlparam); ?>" scrolling="no"></iframe>';
							
						$('.modal-body').html(temp);	
						$('.modal-body iframe')[0].contentWindow.focus();
						$('#modal-popup').modal('show');
					})
				<?php 
					endforeach;
				endif; 
				?>
			</script><?php
 	}
}