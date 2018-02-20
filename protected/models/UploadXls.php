<?php

class UploadXls extends CFormModel
{
	public $upload_file;
	public $scenario;
	
	public function rules()
	{
		return array(
			array('upload_file','file','allowEmpty'=>false,'types'=>'xls','wrongType'=>'File type must be "xls"'),
			array('scenario','safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'upload_file' => 'File',
			'scenario' => 'Scenario',
		);
	}
	
}
