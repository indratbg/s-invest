<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/kodam1.ico" type="image/x-icon" />
    
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    
    
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->
    
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
 
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <script> 
      // Tell the parent iframe what height the iframe needs to be
      function parentIframeResize()
      {
         //console.log("parentIframeResize() Called");
         // This works as our parent's parent is on our domain..
         window.parent.resizeIframe($(document).height());
      }
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/small-scale.css" />
    
    <style>
        #content{margin: 0px;}
        input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
            height: 20px;
        }
        textarea{
    margin-bottom: 10px; 
}
    </style>
</head>

<body onload="parentIframeResize()">
 <script type="text/javascript"> 

	function stopEKey(evt) { 
	  var evt = (evt) ? evt : ((event) ? event : null); 
	  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
	} 
	
	document.onkeypress = stopEKey; 

</script>
    <div class="container">
        <div id="content">
            <?php echo $content; ?>
        </div>
    </div>
</body>
</html>