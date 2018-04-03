<?php

class GenerateimfileController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Generateimfile('GENERATE IM FILE', 'IM_FILE_EXPORT', '');
        $im_code = DAO::queryAllSql("select im_code,im_code||' - '||im_name im_name from mst_im order by im_code");
        $model->trx_date = date('d/m/Y');
        if (isset($_POST['Generateimfile']))
        {

            $model->attributes = $_POST['Generateimfile'];

            if ($model->validate() && $model->executeSp() > 0)
            {
                if ($model->im_code == 'DH002')//Sinarmas
                {
                    $this->getXls($model, 'xls');
                }
                else if ($model->im_code == 'YJ002')//LIM
                {
                    $model->col_name = $model->col_name_lim;
                    $this->getCSV($model, 'csv');
                }
				else if ($model->im_code == 'AH002')//LIM
                {
                    $model->col_name = $model->col_name_lim;
                    $this->getCSV($model, 'csv');
                }
                else if ($model->im_code == 'SCH02')//Schroder
                {
                    $model->col_name = $model->col_name_sch;
                    $this->getXls($model, 'xls');
                }
                else if ($model->im_code == 'SYA02')//syailendra
                {
                    $model->col_name = $model->col_name_sya;
                    $this->getXls($model, 'xls');
                }
            }
        }

        $this->render('index', array(
            'model'=>$model,
            'im_code'=>$im_code
        ));
    }

    public function getXls(&$model, $ext)
    {

        $excelFileName = Yii::app()->basePath . '/../upload/im_file/' . $model->module . '.' . $ext;
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("SSS")->setLastModifiedBy("SSS")->setTitle($model->module)->setSubject("Office 2007 XLS Document")->setKeywords("office 2007 openxml php");

        $objPHPExcel->setActiveSheetIndex(0);
        $col = 'A';
        $x = 0;
        $select_query = 'select ';
        foreach ($model->col_name as $col_name=>$alias)
        {
            //set header
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col . '1', $alias);
            if ($x > 0)
                $select_query .= ', ';
            $select_query .= strtolower($col_name);
            $col++;
            $x++;
        }

        //build query select column
        $user_id = Yii::app()->user->id;
        $select_query .= " from $model->tablename  where user_id= '$user_id' and update_seq=$model->vo_random_value ";
        $exec_data = DAO::queryAllSql($select_query);
        //set detail
        if (count($exec_data) > 0)
        {

            $y = 2;
            foreach ($exec_data as $row)
            {
                $col = 'A';
                foreach ($model->col_name as $col_name=>$alias)
                {
                    $value = $row[strtolower($col_name)];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col . $y, $value);
                    $col++;
                }
                $y++;

            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save($excelFileName);

            if (file_exists($excelFileName))
            {
                header('Content-Description: File Transfer');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="' . $model->module . ' ' . $model->im_code . '.' . $ext . '"');
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
        else
        {
            $model->addError('trx_date', 'No Data Found');
        }
    }

    public function getCSV(&$model, $ext)
    {
        $fileName = $model->module . '_' . date('Ymd') . $ext;
        $csvFileName = 'upload/im_file/' . $fileName;
        $handle = fopen($csvFileName, 'wb');
        $user_id = Yii::app()->user->id;

        $x = 0;
        $select_query = 'select ';
        $cnt_col = count($model->col_name);
        foreach ($model->col_name as $col=>$alias)
        {
            if ($x > 0)
            {
                $select_query .= ', ';
                fwrite($handle, ",");
            }
            fwrite($handle, $alias);
            $select_query .= strtolower($col);
            if (($cnt_col - 1) == $x)
            {
                fwrite($handle, "\r\n");
            }
            $x++;
        }

        //build query select column
        $user_id = Yii::app()->user->id;
        $select_query .= " from $model->tablename  where user_id= '$user_id' and update_seq=$model->vo_random_value ";
        $exec_data = DAO::queryAllSql($select_query);
        if (count($exec_data) > 0)
        {
        //get data detail
        foreach ($exec_data as $row)
        {
            //get list column name
            $x = 0;
            foreach ($model->col_name as $col=>$alias)
            {
                if ($x > 0)
                {
                    fwrite($handle, ",");
                }
                $value = $row[strtolower($col)];
                fwrite($handle, $value);
                if (($cnt_col - 1) == $x)
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
            header('Content-Disposition: attachment; filename="' . $model->module . ' ' . $model->im_code . '.' . $ext . '"');
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
