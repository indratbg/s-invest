<?php

class TcdownloadController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $model = new Tcdownload('TC_DOWNLOAD', 'TC_DOWNLOAD', '');
        $model->trx_date = date('d/m/Y');
        $model->trx_status = 'A';
        if (isset($_POST['Tcdownload']))
        {
            $model->attributes = $_POST['Tcdownload'];
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
                $model->vo_random_value = $model->vo_random_value?$model->vo_random_value:'0';
                $this->getCSV_SI($model, $condition);
            }
        }

        $this->render('index', array('model'=>$model));
    }

}
