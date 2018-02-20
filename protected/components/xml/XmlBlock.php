<?php
//ER
class XmlBlock extends Xml
{
	/**
	 * Generate xml for sub rekening
	 * Either individual or company(institutional)
	 * @arr is the array of data that was passed, in this case the $_POST('SDISubrek)
	 * @filename is the name of the file
	 * @filepath is a file path (Disk path not httpPath, C:/file)
	 */
	public static function generateXmlBlock($arr,$save_dt)
	{
		$model = new SDIBlock();
		$write001 = false;
		$write004 = false;
		
		$filename001 = XmlBlock::changeDateFormat($save_dt).'block1.sdiap';
		$filename004 = XmlBlock::changeDateFormat($save_dt).'block4.sdiap';
		
		$filepath001 = FileUpload::getFilePath(FileUpload::SDI_FILE_XML,$filename001);
		$filepath004 = FileUpload::getFilePath(FileUpload::SDI_FILE_XML,$filename004);

		$file001 = fopen($filepath001, 'w');
		$file004 = fopen($filepath004, 'w');
		
		$content001 = "<Message>\r\n";
		$content004 = "<Message>\r\n";
		
		foreach($arr as $object)
		{
			$model->attributes = $object;
			$client = Client::model()->findByPk($model->client_cd);
			$model->subrek_001 = str_replace('-', '',$model->subrek_001);
			$model->subrek_004 = str_replace('-', '', $model->subrek_004);
			if($model->yn_001):
				$content001 .= "<Record name=\"data\">\r\n";
				$content001 .= "<Field name=\"accountNumber\">$model->subrek_001</Field>\r\n";
				$content001 .= "<Field name=\"actionTobeApprove\">BLOCK</Field>\r\n";
				$content001 .= "<Field name=\"blockingReasonTobeApprove\">JSX</Field>\r\n";
				$content001 .= "<Field name=\"approveOrReject\">A</Field>\r\n";
				$write001 = true;
			endif;
			
			if($model->yn_004):
				$content004 .= "<Record name=\"data\">\r\n";
				$content004 .= "<Field name=\"accountNumber\">$model->subrek_004</Field>\r\n";
				$content004 .= "<Field name=\"actionTobeApprove\">BLOCK</Field>\r\n";
				$content004 .= "<Field name=\"blockingReasonTobeApprove\">JSX</Field>\r\n";
				$content004 .= "<Field name=\"approveOrReject\">A</Field>\r\n";
				$write004 = true;
			endif;
			
			if($model->yn_001)$content001 .= "</Record>\r\n";
			if($model->yn_004)$content004 .= "</Record>\r\n";
		}//end foreach
		
		$content001 .= "</Message>\r\n";
		$content004 .= "</Message>\r\n";
		
		try{
			if($write001):
				fwrite($file001,$content001) or die('Error While Writing XML');
				fclose($file001);
			endif;
			
			if($write004):
				fwrite($file004,$content004) or die('Error While Writing XML');
				fclose($file004);
			endif;
		}
		catch(exception $e)
		{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
		
		$res = array('write001'=>$write001,'filename001'=>$filename001,
					'write004'=>$write004, 'filename004'=>$filename004);
		return $res;	
	}

	
}
