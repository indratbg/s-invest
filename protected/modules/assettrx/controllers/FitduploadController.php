<?php

class FitduploadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Fitdupload;
        $modelTaxData = new Fitdtaxdataupload;
        $success = true;
        if (isset($_POST['Fitdupload']))
        {
            $model->attributes = $_POST['Fitdupload'];
            $model->scenario = 'upload';
            if ($model->validate())
            {
                //buat ambil file yang di upload tanpa $_FILES
                $model->file_upload = CUploadedFile::getInstance($model, 'file_upload');
                $path = FileUpload::getFilePath(FileUpload::FI_TD_UPLOAD, $model->file_upload);
                $model->file_upload->saveAs($path);
                $filename = $model->file_upload;

                //insert data ke TD_UPLOAD
                $lines = file($path);
                foreach ($lines as $line_num=>$line)
                {
                    if ($line_num != 0)
                    {
                        $data = explode('"', $line);
                        $temp = '';
                        $temp_new = '';
                        if (count($data) == 3)
                        {
                            $data = $this->get_string_between($line, '"', '"');
                            $temp = $data;
                            $temp_new = str_replace(',', '$', $temp);
                        }

                        $line = str_replace($temp, $temp_new, $line);
                        $line = str_replace('"', '', $line);

                        if (strpos($line, '|') === false)
                        {
                            $pieces = explode(',', $line);
                        }
                        else
                        {
                            $pieces = explode('|', $line);
                        }
                        $cnt_piece = count($pieces);

                        if ($cnt_piece == 16)
                        {
                            $trade_date = $pieces[1];
                            $settlement_date = $pieces[2];
                            if (DateTime::createFromFormat('Ymd', $trade_date))
                                $trade_date = DateTime::createFromFormat('Ymd', $trade_date)->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $settlement_date))
                                $settlement_date = DateTime::createFromFormat('Ymd', $settlement_date)->format('Y-m-d');
                            $model->transaction_status = $pieces[0];
                            $model->trade_date = $pieces[1];
                            $model->settlement_date = $pieces[2];
                            $model->im_code = $pieces[3];
                            $model->im_name = $pieces[4];
                            $model->br_code = $pieces[5];
                            $model->br_name = $pieces[6];
                            $model->security_code = $pieces[7];
                            $model->security_name = $pieces[8];
                            $model->buy_sell = $pieces[9];
                            $model->ccy = $pieces[10];
                            $model->price = $pieces[11];
                            $model->face_value = $pieces[12];
                            $model->td_reference_no = $pieces[13];
                            $model->td_reference_id = $pieces[14];
                            $model->check_status = trim($pieces[15]);
                            $model->cre_dt = date('Y-m-d H:i:s');
                            $model->user_id = Yii::app()->user->id;

                            $check = Fitdupload::model()->find("td_reference_id='$model->td_reference_id' ");
                            if (!$check)
                            {
                                try
                                {
                                    $model->save(false);
                                    //UPDATE td_reference_id PADA TD_DOWNLOAD
                                    $sql = "UPDATE FI_TD_DOWNLOAD SET td_reference_id='$model->td_reference_id' WHERE td_reference_no='$model->td_reference_no'";
                                    $exec = DAO::executeSql($sql);
                                }
                                catch(Exception $ex)
                                {
                                    $success = false;
                                    $model->addError('file_upload', $ex->getMessage());
                                    break;
                                }
                                $model = new Fitdupload();
                            }
                            else
                            {
                                $sql = "UPDATE FI_TD_UPLOAD SET CHECK_STATUS='$model->check_status' where td_reference_id='$model->td_reference_id' ";
                                $exec = DAO::executeSql($sql);

                            }

                        }
                        //if sell tax data

                        if ($cnt_piece == 14)
                        { 
                            
                            $modelTaxData->data_type = $pieces[0];
                            $modelTaxData->td_reference_no = $pieces[1];
                            $modelTaxData->trade_date = $pieces[2];
                            $modelTaxData->settlement_date = $pieces[3];
                            $modelTaxData->im_code = $pieces[4];
                            $modelTaxData->im_name = $pieces[5];
                            $modelTaxData->br_code = $pieces[6];
                            $modelTaxData->br_name = $pieces[7];
                            $modelTaxData->security_code = $pieces[8];
                            $modelTaxData->security_name = $pieces[9];
                            $modelTaxData->face_value = trim($pieces[10]);
                            $modelTaxData->acquisition_date = $pieces[11];
                            $modelTaxData->acquisition_price = trim($pieces[12]);
                            $modelTaxData->acquisition_amount = trim($pieces[13]);
                            $modelTaxData->cre_dt = date('Y-m-d H:i:s');
                            $modelTaxData->user_id = Yii::app()->user->id;
                            $check = Fitdtaxdataupload::model()->find("td_reference_no='$modelTaxData->td_reference_no' ");
                            if (!$check)
                            {
                                try
                                {
                                    $modelTaxData->save();
                                    $modelTaxData = new Fitdtaxdataupload;
                                }
                                catch(Exception $ex)
                                {
                                    $success = false;
                                    $model->addError('file_upload', $ex->getMessage());
                                    break;
                                }
                                
                            }

                        }

                    }
                }
                // unlink(FileUpload::getFilePath(FileUpload::FI_TD_UPLOAD,$model->file_upload));
                if ($success)
                {

                    Yii::app()->user->setFlash('success', 'Successfully upload ' . $filename);
                    $this->redirect(array('index'));
                }

            }
        }
        $this->render('index', array('model'=>$model));
    }

    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

}
