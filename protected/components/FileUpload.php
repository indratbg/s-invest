<?php
class FileUpload 
{
	const TD_UPLOAD = 1;
    const TC_UPLOAD = 2;
	/***
	 * Untuk dapatin file path untuk upload file. "C:\xampp\htdocs\del\upload\foto\file.ext"
	 * @param String $fileName filename dan extention dari file yang mau di upload
	 * @param String $constPath variable constant yang ada diatas
	 * @return String a Complete upload path for file upload.
	 */
	public static function getFilePath($constPath,$fileName) 
	{
		$pathFile = '';
		$pathFile = Yii::app()->basePath.''.$fileName;
		switch($constPath) {
			case FileUpload::TD_UPLOAD:
				$pathFile = Yii::app()->basePath.'/../upload/im_file/'.$fileName;
				break;
            case FileUpload::TC_UPLOAD:
                $pathFile = Yii::app()->basePath.'/../upload/im_file/'.$fileName;
                break;
			
		}
		return $pathFile;
	}
	
	
	/**
	 * 
	 * Digunakan untuk mencari path file yang sudah di upload.
	 * @param String $const 
	 * @param String $file_name nama file yang akan di download
	 * NOTE : Return String berisi path file
	 */	
	public static function getHttpPath($const,$file_name)
	{
		$path = '';
		switch ($const) {
			case FileUpload::SDI_FILE_XML:
				$path = Yii::app()->request->hostInfo . Yii::app()->request->baseURL .'/upload/sdi_xml/'. $file_name;
				break;
		}
		return $path;
	}
}