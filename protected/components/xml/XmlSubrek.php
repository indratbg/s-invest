<?php
//ER
class XmlSubrek extends Xml
{
	/**
	 * Generate xml for sub rekening
	 * Either individual or company(institutional)
	 * @arr is the array of data that was passed, in this case the $_POST('SDISubrek)
	 * @filename is the name of the file
	 * @filepath is a file path (Disk path not httpPath, C:/file)
	 */
	public static function generateXmlSubrek($arr)
	{
		$model = new SDISubrek();
		$write001 = false;
		$write004 = false;
		
		$filename001 = date('Ymd').'new.sdi';
		$filename004 = date('Ymd').'new4.sdia';
		
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
			
			if($model->type_001):
				$content001 .= "<Record name=\"data\">\r\n";
				$content001 .= "<Field name=\"action\">CREATION</Field>\r\n";
				$content001 .= "<Field name=\"investorType\">".AConstant::$client_type[$client->client_type_1]."</Field>\r\n";
				$content001 .= "<Field name=\"investorClientType\">DIRECT</Field>\r\n";
				$content001 .= "<Field name=\"accountLocalCode\">YJ001</Field>\r\n";
				$content001 .= "<Field name=\"accountClientCode\">$model->digit</Field>\r\n";
				$col = XmlSubrek::getXMLCol($model->client_cd,$client->client_type_1);
				foreach ($col as $key => $value) 
				{
					$value = trim($value);
					$colname = XmlSubrek::getColDesc($key,$client->client_type_1);
					$prm_desc = $colname['prm_desc'];
					if($colname['prm_desc2']=='C'&&!empty($value))
					$content001 .= "<Field name=\"$prm_desc\"><![CDATA[$value]]></Field>\r\n";
					else
					$content001 .= "<Field name=\"$prm_desc\">$value</Field>\r\n";
				}//end foreach
				$write001 = true;
			endif;
			
			if($model->type_004):
				$content004 .= "<Record name=\"data\">\r\n";
				$content004 .= "<Field name=\"accountNumber\">YJ001".$model->digit."004</Field>\r\n";
				$content004 .= "<Field name=\"action\">CREATE</Field>\r\n";
				$write004 = true;
			endif;
			
			if($model->type_001)$content001 .= "</Record>\r\n";
			if($model->type_004)$content004 .= "</Record>\r\n";
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
