<?php

// AR: uncomment this if you wanna generate model 
//     http://www.yiiframework.com/wiki/99/using-yii-with-oracle-through-pdo#c4992
//     according to that statement oracle seems have poor reverse engineering 

ini_set('max_execution_time', 30000);

require('protected/components/Constanta.php');
 
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'timeZone' => 'Asia/Jakarta',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'STOCK EXPERT 1.0',

	//AH: Setting theme. ada di folder "project/theme"
	'theme'=>'bootstrap',
	
	// preloading 'log' component
	'preload'=>array('log','bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.report.*',
		'application.models.uploaddbf.*',
		'application.components.*',
		'application.components.xml.*',
		'application.components.directory.*',
		'application.components.phpxbase.*',
		'ext.YiiMailer.YiiMailer',
		'ext.phpexcel.XPHPExcel'

	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' => array(
         		 'bootstrap.gii'
       		),
		),
		'core',
		'assettrx',
		 'master',
		 'inbox',
		// 'custody',
		// 'glaccounting',
		// 'fixedincome',
		// 'contracting',
		// 'finance',
		 'share',
		// 'riskmanagement'
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
// 		'urlManager'=>array(
// 			'urlFormat'=>'path',
// 			'rules'=>array(
// 				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
// 				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
// 				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
// 			),
// 		), 		
		'db'=>array(
              'connectionString' => Constanta::ip_db,
              'username' => Constanta::db_user,
              'password' => Constanta::db_pass,
              'charset' => 'utf8',
              //'enableProfiling' => true,
              'enableParamLogging' => true,
              //'schemaCachingDuration' => '500000',
              'attributes' => array(PDO::ATTR_CASE => PDO::CASE_LOWER),
			  'initSQLs' => array(
				  	// AH : changing oracle behaviour
				  	"ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'",
	        		"ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '. '",
        		
			   )
        ),
		
		 
		'dbrpt'=>array(
			  'class'=>'CDbConnection',
              'connectionString' => Constanta::ip_db,
              'username' => Constanta::db_user_rpt,
              'password' => Constanta::db_pass_rpt,
              'charset' => 'utf8',
              //'enableProfiling' => true,
              'enableParamLogging' => true,
              //'schemaCachingDuration' => '500000',
              'attributes' => array(PDO::ATTR_CASE => PDO::CASE_LOWER),
              
			  'initSQLs' => array(
				  	// AH : changing oracle behaviour
				  	"ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'",
	        		"ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '. '",
        		
			   )	
		),
		
		'dbfo'=>array(
			  'class'=>'CDbConnection',
              'connectionString' => 'oci:dbname=//aventador-pc:1521/orclfo',
              'username' => 'lotsfo',
              'password' => 'lotsfo',
              'charset' => 'utf8',
              //'enableProfiling' => true,
              'enableParamLogging' => true,
              //'schemaCachingDuration' => '500000',
              'attributes' => array(PDO::ATTR_CASE => PDO::CASE_LOWER),
              
			  'initSQLs' => array(
				  	// AH : changing oracle behaviour
				  	"ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'",
	        		"ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '. '",
        		
			   )	
		),
		
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
				),
			),
		),
		
		// mPDF & HTML2PDF
		'ePdf' => array(
	        'class' => 'ext.yii-pdf.EYiiPdf',
	        'params' => array(
	            'mpdf' => array(
	                'librarySourcePath' => 'application.vendors.mpdf60.*',
	                'constants'         => array(
	                    '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
	                ),
	                'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
	                'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
	                    'mode'              => '', //  This parameter specifies the mode of the new document.
	                    'format'            => 'Letter', // format A4, A5, ...
	                    'default_font_size' => 11, // Sets the default document font size in points (pt)
	                    'default_font'      => 'Arial', // Sets the default font-family for the new document.
	                    'mgl'               => 8, // margin_left. Sets the page margins for the new document.
	                    'mgr'               => 8, // margin_right
	                    'mgt'               => 8, // margin_top
	                    'mgb'               => 10, // margin_bottom
	                    'mgh'               => 5, // margin_header
	                    'mgf'               => 5, // margin_footer
	                    'orientation'       => 'P', // landscape or portrait orientation
	                )
	            ),
	            'HTML2PDF' => array(
	                'librarySourcePath' => 'application.vendors.html2pdf.*',
	                'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
	                /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
	                    'orientation' => 'P', // landscape or portrait orientation
	                    'format'      => 'A4', // format A4, A5, ...
	                    'language'    => 'en', // language: fr, en, it ...
	                    'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
	                    'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
	                    'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
	                )*/
	            )
	        )
        ),
		
        // AR : 
        'datetime'=>array(
            'class'=>'application.components.ADateTimeParser',
        ),
		
		// AR : Component include for bootstrap
		'bootstrap' => array(
		    'class' => 'ext.bootstrap.components.Bootstrap',
		    'responsiveCss' => true
		),
		
		'format'=>array(
			'class'=>'application.components.AFormatter',
		),
	),

	'params'=>array(
		// this is used in contact page
		'socket_fo_ip'=>'192.168.8.200',//push ke FO
        'socket_fo_port'=>'49994',//Push ke FO
		'adminEmail'=>'admin@saranasistemsolusindo.com',
		'datepick_1st'=>'dd',
        'datepick_2nd'=>'mm',
        'datepick_3rd'=>'yy',
        'datepick_separator'=>'/',
        'datepick_phpDateFormat'=>'d/m/Y', //php date format pada saat mau menampilkan ke textbox dari mysql.
	),
);