<?php
/**
 * Connect to SSS Front office socket.
 * This class require parameter socket_fo_ip and socket_fo_port at params section of "config/main.php".
 * @author Wiyanto
 *
 */
require_once("java/Java.inc");
class SocketToFront {
	private $sock;
	
	/**
	 * 
	 * @throws CHttpException 
	 */
	 
	public static function getInstance()
	{
		static $instance=null;
		if(null===$instance){
			$instance=new static();
		}
		
		return $instance;
	}
	protected function __construct()
	{
		$this->sock = new Java("com.sss.push.PushToFront");
		$this->sock->setFoIp(Yii::app()->params['socket_fo_ip']);
		$this->sock->setFoPort(Yii::app()->params['socket_fo_port']);
		//$this->sock->connect();
	}
	
	public function connectFO()
	{
		$connectSts="";	
		
		if($this->sock==null){
		 	$connectSts= "create instance socket first";
		} else {
			$connectSts=$this->sock->connect();
		}
		return $connectSts;
	}
	
	public function socketURL()
	{
		$ipURL=$this->sock->getFoIp().' : '.$this->sock->getFoPort();	
		return $ipURL;
	}
	/**
	 * Push client cash
	 * 
	 * @param String $client_cd client code nasabah yang mau di push cash ke FO
	 * @throws CHttpException
	 * @return string hasil return dari server socket. antara gagal / sukses
	 */
	public function pushClientCash($client_cd)
	{
		if(empty($client_cd)) {
			throw new CHttpException(400,'Bad request! Client code must be supplied.');
		}
		 $msg= 'CCP'.chr(9).$client_cd;
		 $this->sock->sendData($msg);
		return 'OK1';
	}
	
	/**
	 * Push client stock to FO
	 * 
	 * @param String $client_cd client code nasabah yang mau di push cash ke FO
	 * @param String $stock_cd kode saham.
	 * @throws CHttpException
	 * @return string hasil return dari server socket. antara gagal / sukses
	 */
	public function pushClientStock($client_cd, $stock_cd)
	{
		if(empty($client_cd) || empty($stock_cd)) {
			throw new CHttpException(400,'Bad request! Client code and Stock code must be supplied.');
		}
		$msg =  'CSP'.chr(9).$client_cd.chr(9).$stock_cd;
		$this->sock->sendData($msg);
		return $client_cd.' - '.$stock_cd; 
	}
	
	/**
	 * Close socket connection
	 */
	public function closeConnection()
	{
		$result=$this->sock->close();
		return $result; 
	}
	
	private function __clone()
    {
    }
	
	 private function __wakeup()
    {
    }
}