<?php

class ClientwofundController extends AAdminController
{
    public $layout = '//layouts/admin_column3';

    public function actionIndex()
    {
        $modeldummy = new Clientwofund;
        $modeldummy->trx_date = date('d/m/Y');
         $model = Clientwofund::model()->findAllBySql(Clientwofund::getListData($modeldummy->trx_date));
       
        if (isset($_POST['Clientwofund']))
        {
            $modeldummy->attributes = $_POST['Clientwofund'];         
            $model = Clientwofund::model()->findAllBySql(Clientwofund::getListData($modeldummy->trx_date));
            if(!$model)
            {
                $modeldummy->addError('trx_date','Data not found');
            }
        }
        
            foreach($model as $row)
            {
                if(DateTime::createFromFormat('Y-m-d H:i:s',$row->trx_date))$row->trx_date= DateTime::createFromFormat('Y-m-d H:i:s',$row->trx_date)->format('d-M-Y');
            }
        $this->render('index', array(
            'model'=>$model,
            'modeldummy'=>$modeldummy
        ));
    }


}
