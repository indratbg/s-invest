<?php

class DownloadController extends Controller
{	
	/**
	 * 
	 * Digunakan untuk mendownload file yang sudah di upload.
	 * @param String $const 
	 * @param String $file_name nama file yang akan di download
	 */	
	public function actionDownloadFile($const,$file_name)
	{
		switch($const)
		{
			case FileUpload::SDI_FILE_XML:
				$path =FileUpload::getFilePath($const, $file_name);
				break;
		}
		return Yii::app()->getRequest()->sendFile($file_name, @file_get_contents($path));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}