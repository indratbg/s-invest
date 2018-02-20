<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns="http://www.w3.org/TR/REC-html40">
<?php //http://codesnipers.com/?q=excel-compatible-html?>
<head>
<meta charset="UTF-8">
<style type="text/css">
	.trbl, .trb, .trl{border-top: 1px solid black;}
	.trbl, .trb, .trl, .rb, .rbl, .rl, .r{border-right: 1px solid black;}
	.trbl, .trb, .rb, .rbl, .b{border-bottom: 1px solid black;}
	.trbl, .trl, .rbl, .rl {border-left: 1px solid black;}
	#title {font-size: 2em;}
	.col-left {text-align: left;}
	.col-center {text-align: center;}
	.col-right {text-align: right;}
	.bigsize {font-size: 1.5em;}
	@page {
	size:8.27in 11.69in;
	margin:.2in .0in .0in .6in;
	mso-header-margin:.0in;
	mso-footer-margin:.0in;
	mso-page-orientation:portrait;
	}
	
	@print {
	size:8.27in 11.69in;
	margin:.2in .0in .0in .6in;
	mso-header-margin:.0in;
	mso-footer-margin:.0in;
	mso-page-orientation:portrait;
	}
</style>
</head>

<body>
<?php echo $content; ?>
</body>
</html>