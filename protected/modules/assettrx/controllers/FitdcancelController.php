<?php

class FitdcancelController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $modelDetail = array();
        $model = new Fitdcancel;
        $model->scenario = 'retrieve';
        $model->trx_date = date('d/m/Y');
        $success = true;
        if (isset($_POST['scenario']))
        {
            $model->attributes = $_POST['Fitdcancel'];
            $scenario = $_POST['scenario'];
            $im_code = $model->im_code ? $model->im_code : '%';
            if ($scenario == 'retrieve')
            {
                $bond_cd = $model->bond_cd ? $model->bond_cd : '%';
                $modelDetail = Fitdcancel::model()->findAllBySql($model->getListData($model->trx_date, $bond_cd, $im_code));
            }
            else if ($scenario == 'cancel')
            {
                $rowCount = $_POST['rowCount'];

                for ($x = 1; $x <= $rowCount; $x++)
                {
                    $modelDetail[$x] = new Fitdcancel;
                    $modelDetail[$x]->attributes = $_POST['Fitdcancel'][$x];

                    if ($modelDetail[$x]->save_flg == 'Y')
                    {

                        $modelDetail[$x]->trx_date = $model->trx_date;
                        if (DateTime::createFromFormat('d/m/Y', $modelDetail[$x]->trx_date))
                            $modelDetail[$x]->trx_date = DateTime::createFromFormat('d/m/Y', $modelDetail[$x]->trx_date)->format('Y-m-d');
                        if ($modelDetail[$x]->executeSpCancel() > 0)
                            $success = TRUE;
                        else
                        {
                            $success = FALSE;
                            break;
                        }
                    }

                }
                if ($success)
                {
                    Yii::app()->user->setFlash('success', 'Trade detail berhasil dicancel');
                    $this->redirect(array('index'));
                }
            }

        }

        $this->render('index', array(
            'model'=>$model,
            'modelDetail'=>$modelDetail
        ));
    }

}
