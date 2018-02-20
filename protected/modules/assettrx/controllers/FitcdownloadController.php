<?php

class FitcdownloadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Fitcdownload('FIXED INCOME TC DOWNLOAD', 'FI_TC_DOWNLOAD', '');
        $model->trx_date = date('d/m/Y');
        $model->trx_status = 'A';
        $batch_file = array();

        if (isset($_POST['Fitcdownload']))
        {
            $model->attributes = $_POST['Fitcdownload'];
            $im_code = $model->im_code ? $model->im_code : '%';
            if ($model->validate() && $model->executeSp($im_code) > 0)
            {
                $condition = '';

                if ($model->trx_status == 'A')
                {
                    $condition = "and TRANSACTION_STATUS='NEWM' and (im_code='$im_code' or '$im_code'='%') ";
                }
                else
                {
                    $condition = "and TRANSACTION_STATUS='CANC' and (im_code='$im_code' or '$im_code'='%')";
                }
                
                $this->getCSV($model, $condition);
            }
        }

        $this->render('index', array('model'=>$model));
    }
  public function getCSV(&$model, $condition)
    {
        $fileName = $model->module . '_' . date('YmdHis') .'.csv';
        $csvFileName = 'upload/im_file/' . $fileName;
        $handle = fopen($csvFileName, 'wb');

       
        //build query select column
        //buy
        $select_query_buy = "select * from FI_TC_DOWNLOAD  where update_seq=$model->vo_random_value $condition AND BUY_SELL='1' ";
        $exec_data_buy = DAO::queryAllSql($select_query_buy);
        $select_query_sell = "select * from FI_TC_DOWNLOAD  where update_seq=$model->vo_random_value $condition AND BUY_SELL='2' ";
        $exec_data_sell = DAO::queryAllSql($select_query_sell);
        
         $x = 0;     
        $cnt_col = count($exec_data_sell)>0? count($model->col_name_sell):count($model->col_name_buy);
        $col_name = count($exec_data_sell)>0?$model->col_name_sell:$model->col_name_buy;
        foreach ($col_name as $col=>$alias)
        {
            if ($x > 0)
            {
                fwrite($handle, ",");
            }
            fwrite($handle, $alias);
            if (($cnt_col - 1) == $x)
            {
                fwrite($handle, "\r\n");
            }
            $x++;
        }
        
        
        
        if (count($exec_data_buy) > 0 || count($exec_data_sell) > 0)
        {
        //BUY
        foreach ($exec_data_buy as $row)
        {
            //get list column name
            $x = 0;
            foreach ($model->col_name_buy as $col=>$alias)
            {
                if ($x > 0)
                {
                    fwrite($handle, ",");
                }
                $value = $row[strtolower($col)];
                if(DateTime::createFromFormat('Y-m-d H:i:s',$value))$value =DateTime::createFromFormat('Y-m-d H:i:s',$value)->format('Ymd'); 
                fwrite($handle, $value);
                if ((count($model->col_name_buy) - 1) == $x)
                {
                    fwrite($handle, "\r\n");
                }
                $x++;
            }

        }
        //SELL
        foreach ($exec_data_sell as $row)
        {
            //get list column name
            $x = 0;
            foreach ($model->col_name_sell as $col=>$alias)
            {
                if ($x > 0)
                {
                    fwrite($handle, ",");
                }
                $value = $row[strtolower($col)];
                if(DateTime::createFromFormat('Y-m-d H:i:s',$value))$value =DateTime::createFromFormat('Y-m-d H:i:s',$value)->format('Ymd'); 
                fwrite($handle, $value);
                if ((count($model->col_name_sell) - 1) == $x)
                {
                    fwrite($handle, "\r\n");
                }
                $x++;
            }

        }        
        
        fclose($handle);

        if (file_exists($csvFileName))
        {
            header('Pragma: public');
            header('Content-Description: File Transfer');
            header('Content-Length: ' . filesize($csvFileName));
            header('Content-Disposition: attachment; filename="' . $fileName.'"');
            header('Content-Type: application/csv; charset=UTF-8');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header("Content-Transfer-Encoding: binary");
            ob_clean();
            flush();
            readfile($csvFileName);
            unlink($csvFileName);
            exit ;
        }
        }
        else
        {
            $model->addError('trx_date', 'No Data Found');
        }

    }
}
