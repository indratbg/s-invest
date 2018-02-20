<?php
//ER
class XmlData extends Xml
{
	/**
	 * Generate xml for sub rekening
	 * Either individual or company(institutional)
	 * @arr is the array of data that was passed, in this case the $_POST('SDISubrek)
	 * @filename is the name of the file
	 * @filepath is a file path (Disk path not httpPath, C:/file)
	 */
	public static function generateXmlData($arr,$save_dt)
	{
		$model = new SDIData();
		$write001 = false;
		
		//$filename001 = date('Ymd').'UPD.sdi';
		$filename001 = XmlData::changeDateFormat($save_dt).'UPD.sdi';
		
		$filepath001 = FileUpload::getFilePath(FileUpload::SDI_FILE_XML,$filename001);

		$file001 = fopen($filepath001, 'w');
		
		$content001 = "<Message>\r\n";
		
		foreach($arr as $object)
		{
			$model->attributes = $object;
			$client = Client::model()->findByPk($model->client_cd);
			$model->subrek_001 = str_replace('-', '',$model->subrek_001);
			if($model->yn_001):
				$content001 .= "<Record name=\"data\">\r\n";
				$content001 .= "<Field name=\"action\">MODIFICATION</Field>\r\n";
				$content001 .= "<Field name=\"investorType\">".AConstant::$client_type[$client->client_type_1]."</Field>\r\n";
				$content001 .= "<Field name=\"investorClientType\">DIRECT</Field>\r\n";
				$content001 .= "<Field name=\"accountLocalCode\">YJ001</Field>\r\n";
				$content001 .= "<Field name=\"accountClientCode\">$model->subrek_001</Field>\r\n";
				$col = XmlData::getXMLCol($model->client_cd,$client->client_type_1);
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
			
			if($model->yn_001)$content001 .= "</Record>\r\n";
		}//end foreach
		
		$content001 .= "</Message>\r\n";
		try{
			if($write001):
				fwrite($file001,$content001) or die('Error While Writing XML');
				fclose($file001);
			endif;
		}
		catch(exception $e)
		{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
		
		$res = array('write001'=>$write001,'filename001'=>$filename001);
		return $res;
	}
}