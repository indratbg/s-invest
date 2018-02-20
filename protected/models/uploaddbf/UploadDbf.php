<?php

class UploadDbf extends CFormModel
{
	public $upload_file;
	public $scenario;
	
	public function rules()
	{
		return array(
			array('upload_file','file','allowEmpty'=>false,'types'=>'dbf','wrongType'=>'File type must be "dbf"'),
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
