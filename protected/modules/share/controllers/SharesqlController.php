<?php

class SharesqlController extends ShareController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionGetClientNotCustodian()
	{
		 $i=0;
	      $src=array();
	      $term = strtoupper($_REQUEST['term']);
	      $qSearch = DAO::queryAllSql("
					Select client_cd, client_name FROM MST_CLIENT 
					Where (client_cd like '".$term."%')
	      			AND susp_stat = 'N' AND client_type_1 <> 'B' AND custodian_cd IS NULL
	      			AND rownum <= 11
	      			ORDER BY client_cd
	      			");
	      
	      foreach($qSearch as $search)
	      {
	      	$src[$i++] = array('label'=>$search['client_cd'].' - '.$search['client_name']
	      			, 'labelhtml'=>$search['client_cd'].' - '.$search['client_name'] //WT: Display di auto completenya
	      			, 'value'=>$search['client_cd']);
	      }
	      
	      echo CJSON::encode($src);
	      Yii::app()->end();
	}
	
	public function actionGetSlAcct()
	{
		$i=0;
      	$src=array();
      	$term = strtoupper($_REQUEST['term']);
      	$glAcctCd = $_REQUEST['gl_acct_cd'];
      	
      	$qSearch = DAO::queryAllSql("
					SELECT sl_a, acct_name FROM MST_GL_ACCOUNT 
					WHERE TRIM(gl_a) = '$glAcctCd' 
					AND sl_a LIKE '".$term."%'
					AND prt_type <> 'S'
					AND acct_stat = 'A'
					AND approved_stat = 'A'
	      			AND rownum <= 200
	      			ORDER BY sl_a
      			");
      
	    foreach($qSearch as $search)
	    {
	      	$src[$i++] = array('label'=>$search['sl_a'].' - '.$search['acct_name']
	      			, 'labelhtml'=>$search['sl_a'].' - '.$search['acct_name'] //WT: Display di auto completenya
	      			, 'value'=>$search['sl_a']);
	    }
      
      	echo CJSON::encode($src);
      	Yii::app()->end();
	}

    public function actionGetStock()
    {
      $i=0;
      $src=array();
      $term = isset($_POST['term'])?strtoupper($_POST['term']):'';
      $qSearch = DAO::queryAllSql("
                select  stk_cd, stk_desc from mst_counter 
                where approved_stat='A' 
               and (stk_cd like '" . $term . "%')
                and rownum<31 
                order by stk_cd
                ");
      foreach($qSearch as $search)
      {
        $src[$i++] = array('label'=>$search['stk_cd'].' - '.$search['stk_desc']
                , 'labelhtml'=>$search['stk_cd'] 
                , 'value'=>$search['stk_cd']);
      }
      
      echo CJSON::encode($src);
      Yii::app()->end();
    }
    
    public function actionGetclient()
    {
        $i = 0;
        $src = array();
        $term = strtoupper($_REQUEST['term']);
        $custody_flg = isset($_POST['custody_flg'])?$_POST['custody_flg']:'N';
        $qSearch = DAO::queryAllSql("
                Select client_cd, client_name FROM MST_CLIENT 
                Where (client_cd like '" . $term . "%')
                and ((custodian_cd is not null and '$custody_flg'='Y') or '$custody_flg'='N')
                AND rownum <= 30
                ORDER BY client_cd
                ");

        foreach ($qSearch as $search)
        {
            $src[$i++] = array(
                'label' => $search['client_cd'] . ' - ' . $search['client_name'],
                'labelhtml' => $search['client_cd'],
                'value' => $search['client_cd']
            );
        }

        echo CJSON::encode($src);
        Yii::app()->end();
    }
    public function actionGetclientStockTrx()
    {
        $i = 0;
        $src = array();
        $term = strtoupper($_REQUEST['term']);
        $custody_flg = isset($_POST['custody_flg'])?$_POST['custody_flg']:'N';
        $trx_date = isset($_POST['trx_date'])?$_POST['trx_date']:date('d/m/Y');
        
        $qSearch = DAO::queryAllSql("
                Select distinct client_cd, client_name FROM MST_CLIENT a, stock_transaction b
                Where 
                a.client_cd=b.sl_code
                and b.trx_date=to_date('$trx_date','dd/mm/yyyy')
                and b.trx_status='A'
                and (client_cd like '" . $term . "%')
                and ((custodian_cd is not null and '$custody_flg'='Y') or '$custody_flg'='N')
                AND rownum <= 30
                ORDER BY client_cd
                ");

        foreach ($qSearch as $search)
        {
            $src[$i++] = array(
                'label' => $search['client_cd'] . ' - ' . $search['client_name'],
                'labelhtml' => $search['client_cd'],
                'value' => $search['client_cd']
            );
        }

        echo CJSON::encode($src);
        Yii::app()->end();
    }
public function actionGetStockTrx()
    {
        $i = 0;
        $src = array();
        $term = strtoupper($_REQUEST['term']);
        $custody_flg = isset($_POST['custody_flg'])?$_POST['custody_flg']:'N';
        $trx_date = isset($_POST['trx_date'])?$_POST['trx_date']:date('d/m/Y');
        
        $qSearch = DAO::queryAllSql("
                 select distinct a.stk_cd, a.stk_desc from mst_counter a, stock_transaction b
                where a.stk_cd=b.stk_cd 
                and a.approved_stat='A' 
                and b.trx_status='A'
                and b.trx_date = to_date('$trx_date','dd/mm/yyyy')
                and (a.stk_cd like '" . $term . "%')
                and rownum<31 
                order by a.stk_cd
                ");

       foreach($qSearch as $search)
      {
        $src[$i++] = array('label'=>$search['stk_cd'].' - '.$search['stk_desc']
                , 'labelhtml'=>$search['stk_cd'] 
                , 'value'=>$search['stk_cd']);
      }

        echo CJSON::encode($src);
        Yii::app()->end();
    }
    
      
    public function actionGetDataXLS($schema='INSISTPRO_RPT',$table_name, $file_name,$rand_value, $col_name=array())
    {
        
          $excelFileName = Yii::app()->basePath.'/../upload/rpt_xls/'.$table_name.'.xls';
          $objPHPExcel= XPHPExcel::createPHPExcel();  
          $objPHPExcel->getProperties()->setCreator("SSS")
                                 ->setLastModifiedBy("SSS")
                                 //->setTitle("GENERAL LEDGER")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                // ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 //->setCategory("Accounting")
                                 ;
    
        $objPHPExcel->setActiveSheetIndex(0);
        
        $sql_col = "SELECT COLUMN_NAME FROM all_tab_columns WHERE OWNER='$schema' AND TABLE_NAME='$table_name' and column_name not in ('RAND_VALUE','GENERATE_DATE','USER_ID') ";
        $exec_col = DAO::queryAllSql($sql_col);
        
        $col='A';
        $x=0;
        $select_query='select ';
        foreach($exec_col as $row)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.'1', $row['column_name']);
            if($x > 0)$select_query .= ', ';
            $select_query.=$row['column_name'];
            $col++;
            $x++;
        }
        //build query select column
        $user_id=Yii::app()->user->id;
        $select_query.=" from $schema.$table_name  where user_id= '$user_id' and rand_value=$rand_value ";
        $exec_data = DAO::queryAllSql($select_query);
        
        //set detail     
        $y=2;
        foreach($exec_data as $row)
        {
            $col='A';
            foreach($exec_col as $column)
            {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$y, $row[strtolower($column['column_name'])]);
                $col++;
            }
          $y++;              
            
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($excelFileName);
        
        if(file_exists($excelFileName))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($excelFileName));
            ob_clean();
            flush();
            readfile($excelFileName);
            unlink($excelFileName);
        }       
            
    }
    public function actionCheckSIClient()
    {
        $resp['status']='error';
        if(isset($_POST['trx_date']))
        {
            $trx_date = $_POST['trx_date'];
            $resp['status']='success';
            $sql = "select count(1)cnt from stock_transaction a, mst_fund_client b
                    where a.sl_code=b.client_cd(+)
                    and a.trx_status='A'
                    AND B.CLIENT_CD IS NULL
                    AND TRX_DATE = to_date('$trx_date','dd/mm/yyyy')";
            $check = DAO::queryRowSql($sql);
            $resp['cnt_client']=$check['cnt'];
        }
        echo json_encode($resp);
    }
	
}
