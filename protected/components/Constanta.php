<?php

/**
 * Status contains status constant
*/
class Constanta
{
		
	//SETTING IP APPLIKASI
	const ip_db = 'oci:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.8.1)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=orclbo)))';
	const db_user = 'cpm';
	const db_pass = '123';
// 	
	// const ip_db = 'oci:dbname=//192.168.8.1:1521/orclbo';
	// const db_user = 'ipnextg';
	// const db_pass = 'st4rtr3k';
	
	const db_user_rpt = 'insistpro_rpt';
	const db_pass_rpt = '123';
	
		
	//const for birt url
	const URL = "http://192.168.8.129:8080/birt/frameset?__report=lotsreport/";
	
	const REPORT_PATH = '/opt/apache-tomcat-7.0.30/webapps/birt/lotsreport/';
	//Levy
	public static $stock_type	= array('EQ'=>'Equity','RT'=>'Right','WR'=>'Warrant');
	public static $market_type	= array('RG'=>'Regular','NG'=>'Pasar Nego','TN'=>'Tunai','TS'=>'Tutup Sendiri');
	//--levy
	
	//
	public static $client_type1	= array('I'=>'Individual','B'=>'Broker','H'=>'House Client','C'=>'Corporate','K'=>'Kelembagaan');
	public static $client_type2	= array('F'=>'Foreign','L'=>'Local','O'=>'O');
	public static $client_type3	= array('D'=>'Deposit','E'=>'E','R'=>'Regular','I'=>'Irregular','O'=>'O','M'=>'Margin','K'=>'Netting Simpan Hasil','L'=>'L','N'=>'Netting','T'=>'TPlus','S'=>'Simpan Hasil');
	public static $movement_type = array('R'=>'Setoran','W'=>'Tarik','B'=>'Bunga','P'=>'Pindah','I'=>'Recv IPO','O'=>'Withdraw IPO');//'M'=>'Masuk ke Nsb umum','K'=>'Keluar dr Nsb umum','I'=>'Adjustment IN','O'=>'Adjustment OUT');
	public static $sups_stat = array('N'=>'Active','Y'=>'Suspended');
	public static $gljournal= array('D'=>'DEBIT','C'=>'CREDIT');
	public static $acct_cd = array('DBEBAS'=>'Dana Nasabah','KNPR'=>'Dana Pemilik Rekening','DNU'=>'Debit Nasabah Umum','KNU'=>'Kredit Nasabah Umum');
	//levy
	//stock type
	const STOCK_TYPE_EQUITY 	= 'EQ';
	const STOCK_TYPE_RIGHT		= 'RT';
	const STOCK_TYPE_WARRANT	= 'WR';

	//market type
	const MARKET_TYPE_REGULAR		= 'RG';
	const MARKET_TYPE_PASAR_NEGO	= 'PN';
	const MARKET_TYPE_TUNAI			= 'TN';
	const MARKET_TYPE_TUTUP_SENDIRI	= 'TS';
	//--levy
	
	
	//clienttype
	//clienttype1
	const CLIENT_TYPE1_INDIVIDUAL	= 'I';
	const CLIENT_TYPE1_BROKER		= 'B';
	const CLIENT_TYPE1_HOUSE_CLIENT	= 'H';
	const CLIENT_TYPE1_CORPORATE	= 'C';
	const CLIENT_TYPE1_KELEMBAGAAN	= 'K';
	
	//clienttype2
	const CLIENT_TYPE2_FOREIGN	= 'F';
	const CLIENT_TYPE2_LOCAL	= 'L';
	
	//clienttype3
	const CLIENT_TYPE3_REGULAR		= 'R';
	const CLIENT_TYPE3_IRREGULAR	= 'I';
	//--clienttype

}













