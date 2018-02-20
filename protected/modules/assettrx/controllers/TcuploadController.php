<?php

class TcuploadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Tcupload;
        $success = true;
        if (isset($_POST['Tcupload']))
        {
            $model->attributes = $_POST['Tcupload'];
            $model->scenario = 'upload';
            if ($model->validate())
            {
                //buat ambil file yang di upload tanpa $_FILES
                $model->file_upload = CUploadedFile::getInstance($model, 'file_upload');
                $path = FileUpload::getFilePath(FileUpload::TD_UPLOAD, 'TC_Upload.csv');
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

                        if (strpos($line, '|') === false)
                        {
                            $pieces = explode(',', $line);
                        }
                        else
                        {
                            $pieces = explode('|', $line);
                        }

                        //Assign Value
                        $model->transaction_status = $pieces[0];
                        $model->ta_reference_no = $pieces[1];
                        $model->trade_date = DateTime::createFromFormat('Ymd', $pieces[2])->format('Y-m-d');
                        $model->settlement_date = DateTime::createFromFormat('Ymd', $pieces[3])->format('Y-m-d');
                        $model->im_code = $pieces[4];
                        $model->im_name = $pieces[5];
                        $model->br_code = $pieces[6];
                        $model->br_name = $pieces[7];
                        $model->fund_code = $pieces[8];
                        $model->fund_name = $pieces[9];
                        $model->security_code = $pieces[10];
                        $model->security_name = str_replace('$', ',', $pieces[11]);
                        $model->buy_sell = $pieces[12];
                        $model->ccy = $pieces[13];
                        $model->price = $pieces[14];
                        $model->quantity = $pieces[15];
                        $model->trade_amount = $pieces[16];
                        $model->commission = $pieces[17];
                        $model->sales_tax = $pieces[18];
                        $model->levy = $pieces[19];
                        $model->vat = $pieces[20];
                        $model->other_charges = $pieces[21];
                        $model->gross_settlement_amount = $pieces[22];
                        $model->wht_on_commission = $pieces[23];
                        $model->net_settlement_amount = $pieces[24];
                        $model->tc_reference_no = $pieces[25];
                        $model->tc_reference_id = $pieces[26];
                        $model->match_status = trim($pieces[27]);
                        $model->cre_dt = date('Y-m-d H:i:s');
                        $model->user_id = Yii::app()->user->id;

                        $check = Tcupload::model()->find("tc_reference_id='$model->tc_reference_id' ");
                        if (!$check)
                        {
                            try
                            {
                                $model->save(false);
                                //UPDATE tC_reference_id PADA TC_DOWNLOAD
                                $sql = "UPDATE TC_DOWNLOAD SET tc_reference_id='$model->tc_reference_id' WHERE tc_reference_no='$model->tc_reference_no'";
                                $exec = DAO::executeSql($sql);
                            }
                            catch(Exception $ex)
                            {
                                $success = false;
                                $model->addError('file_upload', $ex->getMessage());
                                break;
                            }
                            $model = new Tcupload();
                        }
                        else
                        {
                            $sql = "UPDATE TC_UPLOAD SET MATCH_STATUS='$model->match_status' where tc_reference_id='$model->tc_reference_id' ";
                            $exec = DAO::executeSql($sql);

                        }

                    }
                }
                unlink(FileUpload::getFilePath(FileUpload::TC_UPLOAD, 'TC_Upload.csv'));
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
