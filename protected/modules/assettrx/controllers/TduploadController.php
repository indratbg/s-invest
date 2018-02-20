<?php

class TduploadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Tdupload;
        $success = true;
        if (isset($_POST['Tdupload']))
        {
            $model->attributes = $_POST['Tdupload'];
            $model->scenario = 'upload';
            if ($model->validate())
            {
                //buat ambil file yang di upload tanpa $_FILES
                $model->file_upload = CUploadedFile::getInstance($model, 'file_upload');
                $path = FileUpload::getFilePath(FileUpload::TD_UPLOAD, 'TD_Upload.csv');
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

                        //Assign Value
                        $model->transaction_status = $pieces[0];
                        $model->trade_date = DateTime::createFromFormat('Ymd', $pieces[1])->format('Y-m-d');
                        $model->settlement_date = DateTime::createFromFormat('Ymd', $pieces[2])->format('Y-m-d');
                        $model->im_code = $pieces[3];
                        $model->im_name = $pieces[4];
                        $model->br_code = $pieces[5];
                        $model->br_name = $pieces[6];
                        $model->security_code = $pieces[7];
                        $model->security_name = str_replace('$',',',$pieces[8]);
                        $model->buy_sell = trim($pieces[9]);
                        $model->ccy = $pieces[10];
                        $model->price = $pieces[11];
                        $model->quantity = $pieces[12];
                        $model->td_reference_no = trim($pieces[13]);
                        $model->td_reference_id = $pieces[14];
                        $model->check_status = trim($pieces[15]);
                        $model->cre_dt = date('Y-m-d H:i:s');
                        $model->user_id = Yii::app()->user->id;

                        $check = Tdupload::model()->find("td_reference_id='$model->td_reference_id' ");
                        if (!$check)
                        {
                            try
                            {
                                $model->save(false);
                                //UPDATE td_reference_id PADA TD_DOWNLOAD
                                $sql = "UPDATE TD_DOWNLOAD SET td_reference_id='$model->td_reference_id' WHERE td_reference_no='$model->td_reference_no'";
                                $exec = DAO::executeSql($sql);
                            }
                            catch(Exception $ex)
                            {
                                $success = false;
                                $model->addError('file_upload', $ex->getMessage());
                                break;
                            }
                            $model = new Tdupload();
                        }
                        else
                        {
                            $sql = "UPDATE TD_UPLOAD SET CHECK_STATUS='$model->check_status' where td_reference_id='$model->td_reference_id' ";
                            $exec = DAO::executeSql($sql);

                        }

                    }
                }
                unlink(FileUpload::getFilePath(FileUpload::TD_UPLOAD, 'TD_Upload.csv'));
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
