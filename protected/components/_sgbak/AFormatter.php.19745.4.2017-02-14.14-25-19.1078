<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Extend the default yii framework of CFormatter.
 *
 */
class AFormatter extends CFormatter {
    public $dateFormat='d M Y';
    public $datetimeFormat='d M Y, H:i';
    public $numberFormat=array('decimals'=>'2', 'decimalSeparator'=>'.', 'thousandSeparator'=>',');
    public $timeFormat='H:i';
    
	
	public static function formatNumberNonDec($value)
	{
		return number_format($value,0,'.',',');
	}
	
	public static function revertDate($datevalue)
	{
		$temp = $datevalue;
		if(!empty($temp)){
			if(strpos($temp,' ')){
				$temp = explode(' ', $temp);
				$temp = $temp[0];
			}
				 	
			$var = str_replace('/', '-', $temp);
			return $temp;
		}else
			return $temp;
	}
	
	public static function cleanDate($datevalue)
	{
		if(strpos($datevalue,' ')){
			$datevalue = explode(' ', $datevalue);
			$datevalue = $datevalue[0];
		}
		return $datevalue;
	}
	
	
	private static function getIndexFormat($format)
    {
    	$val = "";
    	switch($format)
    	{
    		case Yii::app()->params['datepick_1st']: $val = 0; break;
    		case Yii::app()->params['datepick_2nd']: $val = 1; break;
    		case Yii::app()->params['datepick_3rd']: $val = 2; break;
    	}
    	
    	return $val;
    }
	
    //put your code here
    public function formatDate($value)
    {
    	if($value !== NULL):
	    	if(strpos($value, '/') !== false)
	    		$value = AFormatter::revertDate($value);
				
	        //if(!is_numeric($value)) $value=strtotime ($value);
	        //return date($this->dateFormat,$value);
	        
	        
	        //LO: To format date that falls outside of 32 bit integer range 
	        if(DateTime::createFromFormat('Y-m-d',$value))
	       		return DateTime::createFromFormat('Y-m-d',$value)->format($this->dateFormat);
			else if(DateTime::createFromFormat('Y-m-d G:i:s',$value))
				return DateTime::createFromFormat('Y-m-d G:i:s',$value)->format($this->dateFormat);	
			else if(DateTime::createFromFormat('Y/m/d',$value))
				return DateTime::createFromFormat('Y/m/d',$value)->format($this->dateFormat);
			else if(DateTime::createFromFormat('d-m-Y',$value))
				return DateTime::createFromFormat('d-m-Y',$value)->format($this->dateFormat);
			else 
				return $value;

		else:
			return $value;
		endif;
    }

    public function formatDatetime($value)
    {
            if(!is_numeric($value)) $value=strtotime ($value);
            return date($this->datetimeFormat,$value);
    }
    
    public function formatTime($value)
    {
            if(!is_numeric($value)) $value=strtotime ($value);
            return date($this->timeFormat,$value);
    }
    
    private function terbilang($satuan)
    {
    	$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    	if ($satuan < 12)
    		return " " . $huruf[$satuan];
    	elseif ($satuan < 20)
    		return $this->terbilang($satuan - 10) . " Belas";
    	elseif ($satuan < 100)
    		return $this->terbilang($satuan / 10) . " Puluh" . $this->terbilang($satuan % 10);
    	elseif ($satuan < 200)
    		return " Seratus" . $this->terbilang($satuan - 100);
    	elseif ($satuan < 1000)
    		return $this->terbilang($satuan / 100) . " Ratus" . $this->terbilang($satuan % 100);
    	elseif ($satuan < 2000)
    		return " Seribu" . $this->terbilang($satuan - 1000);
    	elseif ($satuan < 1000000)
    		return $this->terbilang($satuan / 1000) . " Ribu" . $this->terbilang($satuan % 1000);
    	elseif ($satuan < 1000000000)
    		return $this->terbilang($satuan / 1000000) . " Juta" . $this->terbilang($satuan % 1000000);
    	elseif ($satuan < 10000000000)
    		return $this->terbilang($satuan / 10000000) . " Milyar" . $this->terbilang($satuan % 10000000);
    	elseif ($satuan < 100000000000)
    		return $this->terbilang($satuan / 100000000) . " Triliun" . $this->terbilang($satuan % 10000000);
    }
    
    private function koma($satuan)
    {
    	$huruf = array("Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    	return " " . $huruf[$satuan];
    }
     
    
    public function formatMoneyTerbilang($value,$currency)
    {
    	$a 	  = (string)number_format($value, 2, '.',',');
    	$temp = strstr($a,'.');
    	$coma = substr($temp,-2,2);
    		
    	if($coma=='00') $coma = 0;
    		
    		
    	#bilangan tidak memiliki koma
    	if(empty($coma))
    		return $this->terbilang($value)." ".$currency;
    	else
    		return$this->terbilang($value)." Koma ".$this->terbilang($coma)." ".$currency;
    }
}
?>
