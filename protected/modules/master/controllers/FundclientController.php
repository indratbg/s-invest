<?php

class FundclientController extends AAdminController
{
    /**
     * @var string the default layout for the views. Defaults to
     * '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/admin_column2';

    public function actionView($id)
    {
        $this->render('view', array('model'=>$this->loadModel($id), ));
    }

    public function actionCreate()
    {
        $model = new Fundclient;

        if (isset($_POST['Fundclient']))
        {
            $model->attributes = $_POST['Fundclient'];
            $model->cre_dt = Yii::app()->datetime->getDateTimeNow();
            $model->cre_by = Yii::app()->user->id;
            if ($model->validate() && $model->executeSp(AConstant::INBOX_STAT_INS, $model->fund_code) > 0)
            {
                Yii::app()->user->setFlash('success', 'Successfully create ' . $model->fund_code);
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array('model'=>$model, ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Fundclient']))
        {
            $model->attributes = $_POST['Fundclient'];

            if ($model->validate() && $model->executeSp(AConstant::INBOX_STAT_UPD, $id) > 0)
            {
                Yii::app()->user->setFlash('success', 'Successfully update ' . $model->fund_code);
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model'=>$model, ));
    }

   public function actionAjxPopDelete($id)
    {
        $this->layout   = '//layouts/main_popup';
        $is_successsave = false;
        
        $model           = new Ttempheader();
        $model->scenario = 'cancel';
        $model1 = null;
        
        if(isset($_POST['Ttempheader']))
        {
            $model->attributes = $_POST['Ttempheader']; 
                    
            if($model->validate())
            {
                $model1                 = $this->loadModel($id);
                $model1->cancel_reason  = $model->cancel_reason;
                if($model1->validate() && $model1->executeSp(AConstant::INBOX_STAT_CAN,$id) > 0){
                    Yii::app()->user->setFlash('success', 'Successfully cancel '.$model1->fund_code);
                    $is_successsave = true;
                }
            }
        }

        $this->render('_popcancel',array(
            'model'=>$model,
            'model1'=>$model1,
            'is_successsave'=>$is_successsave       
        ));
    }

    public function actionIndex()
    {
        $model = new Fundclient('search');
        $model->unsetAttributes();
        $model->approved_stat='A';
        // clear any default values

        if (isset($_GET['Fundclient']))
            $model->attributes = $_GET['Fundclient'];

        $this->render('index', array('model'=>$model, ));
    }

    public function loadModel($id)
    {
        $model = Fundclient::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
