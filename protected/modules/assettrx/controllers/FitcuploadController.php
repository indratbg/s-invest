<?php

class FitcuploadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Fitcupload;
        $modelTaxData = new Fitctaxdataupload;
        $success = true;
        if (isset($_POST['Fitcupload']))
        {
            $model->attributes = $_POST['Fitcupload'];
            $model->scenario = 'upload';
            if ($model->validate())
            {
                //buat ambil file yang di upload tanpa $_FILES
                $model->file_upload = CUploadedFile::getInstance($model, 'file_upload');
                $path = FileUpload::getFilePath(FileUpload::FI_TC_UPLOAD, $model->file_upload);
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

                        if ($cnt_piece == 36)
                        {

                            if (DateTime::createFromFormat('Ymd', $pieces[4]))
                                $pieces[4] = DateTime::createFromFormat('Ymd', $pieces[4])->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $pieces[5]))
                                $pieces[5] = DateTime::createFromFormat('Ymd', $pieces[5])->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $pieces[23]))
                                $pieces[23] = DateTime::createFromFormat('Ymd', $pieces[23])->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $pieces[24]))
                                $pieces[24] = DateTime::createFromFormat('Ymd', $pieces[24])->format('Y-m-d');

                            $model->transaction_status = $pieces[0];
                            $model->data_type = $pieces[1];
                            $model->tc_reference_no = $pieces[2];
                            $model->ta_reference_no = $pieces[3];
                            $model->trade_date = $pieces[4];
                            $model->settlement_date = $pieces[5];
                            $model->im_code = $pieces[6];
                            $model->im_name = $pieces[7];
                            $model->br_code = $pieces[8];
                            $model->br_name = $pieces[9];
                            $model->counterparty_code = $pieces[10];
                            $model->counterparty_name = $pieces[11];
                            $model->fund_code = $pieces[12];
                            $model->fund_name = $pieces[13];
                            $model->security_code = $pieces[14];
                            $model->security_name = $pieces[15];
                            $model->buy_sell = $pieces[16];
                            $model->ccy = $pieces[17];
                            $model->price = $pieces[18];
                            $model->face_value = $pieces[19];
                            $model->proceeds = $pieces[20];
                            $model->interest_rate = $pieces[21];
                            $model->maturity_date = $pieces[22];
                            $model->last_coupon_date = $pieces[23];
                            $model->next_coupon_date = $pieces[24];
                            $model->accrued_days = $pieces[25];
                            $model->accrued_interest_amount = $pieces[26];
                            $model->other_fee = $pieces[27];
                            $model->capital_gain_tax = $pieces[28];
                            $model->interest_income_tax = $pieces[29];
                            $model->withholding_tax = $pieces[30];
                            $model->net_proceeds = $pieces[31];
                            $model->seller_tax_id = $pieces[32];
                            $model->purpose_of_transaction = $pieces[33];
                            $model->tc_reference_id = $pieces[34];
                            $model->match_status = trim($pieces[35]);
                            $model->cre_dt = date('Y-m-d H:i:s');
                            $model->user_id = Yii::app()->user->id;

                            $check = Fitcupload::model()->find("tc_reference_id='$model->tc_reference_id' ");
                            if (!$check)
                            {
                                try
                                { 
                                    $model->save(false);
                                    //UPDATE td_reference_id PADA TD_DOWNLOAD
                                    $sql = "UPDATE FI_TC_DOWNLOAD SET tc_reference_id='$model->tc_reference_id' WHERE tc_reference_no='$model->tc_reference_no'";
                                    $exec = DAO::executeSql($sql);
                                }
                                catch(Exception $ex)
                                { 
                                    $success = false;
                                    $model->addError('file_upload', $ex->getMessage());
                                    break;
                                }
                                $model = new Fitcupload();
                            }
                            else
                            {
                                $sql = "UPDATE FI_TC_UPLOAD SET CHECK_STATUS='$model->check_status' where tc_reference_id='$model->tc_reference_id' ";
                                $exec = DAO::executeSql($sql);

                            }

                        }
                        //if sell tax data

                        if ($cnt_piece == 21)
                        {
                            if (DateTime::createFromFormat('Ymd', $pieces[3]))
                                $pieces[3] = DateTime::createFromFormat('Ymd', $pieces[3])->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $pieces[4]))
                                $pieces[4] = DateTime::createFromFormat('Ymd', $pieces[4])->format('Y-m-d');
                            if (DateTime::createFromFormat('Ymd', $pieces[12]))
                                $pieces[12] = DateTime::createFromFormat('Ymd', $pieces[12])->format('Y-m-d');

                            $modelTaxData->transaction_status = $pieces[0];
                            $modelTaxData->data_type = $pieces[1];
                            $modelTaxData->tc_reference_no = $pieces[2];
                            $modelTaxData->trade_date = $pieces[3];
                            $modelTaxData->settlement_date = $pieces[4];
                            $modelTaxData->im_code = $pieces[5];
                            $modelTaxData->im_name = $pieces[6];
                            $modelTaxData->br_code = $pieces[7];
                            $modelTaxData->br_name = $pieces[8];
                            $modelTaxData->security_code = $pieces[9];
                            $modelTaxData->security_name = $pieces[10];
                            $modelTaxData->face_value = $pieces[11];
                            $modelTaxData->acquisition_date = $pieces[12];
                            $modelTaxData->acquisition_price = $pieces[13];
                            $modelTaxData->acquisition_amount = $pieces[14];
                            $modelTaxData->capital_gain = $pieces[15];
                            $modelTaxData->days_of_holding_interest = trim($pieces[16]);
                            $modelTaxData->holding_interest_amount = trim($pieces[17]);
                            $modelTaxData->total_taxable_income = trim($pieces[18]);
                            $modelTaxData->tax_rate_in_perc = trim($pieces[19]);
                            $modelTaxData->tax_amount = trim($pieces[20]);
                            $modelTaxData->cre_dt = date('Y-m-d H:i:s');
                            $modelTaxData->user_id = Yii::app()->user->id;

                            $check = Fitctaxdataupload::model()->find("tc_reference_no='$modelTaxData->tc_reference_no' ");
                            if (!$check)
                            {
                                try
                                {
                                    $modelTaxData->save();
                                    $modelTaxData = new Fitctaxdataupload;
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
