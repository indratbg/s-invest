<?php
class RptuseraccesslistController extends AAdminController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin_column3';
	
	public function actionIndex()
	{
		
		$model= new Rptuseraccesslist('LIST_OF_USER_ACCESS_LIST','R_USER_ACCESS_LIST','User_Access_List.rptdesign');
		
		$url 	= "";
		
		if(isset($_POST['Rptuseraccesslist'])){
			
			
			$model->attributes=$_POST['Rptuseraccesslist'];
			if($model->user_cek==NULL){
				$model->user='%';
			}else{
				$model->user=$model->user_cek;
			}
			
			if($model->validate() && $model->executeRpt()>0  ){
				$url = $model->showReport().'&__showtitle=false&__format=pdf';
			}
	
		}
		
		$this->render('index',array(
			'model'=>$model,
			'url'=>$url
		));
	}
	
	public function actionGetUser()
	{
		
		 $i=0;
	      $src=array();
	      $term = strtoupper($_REQUEST['term']);
	      $qSearch = DAO::queryAllSql("
					Select user_id FROM mst_user 
					Where (user_id like '".$term."%')
	      			AND rownum <= 11
	      			ORDER BY user
	      			");
	      
	      foreach($qSearch as $search)
	      {
	      	$src[$i++] = array('label'=>$search['user_id']
	      			, 'labelhtml'=>$search['user_id'] //WT: Display di auto completenya
	      			, 'value'=>$search['user_id']);
	      }
	      
	      echo CJSON::encode($src);
	      Yii::app()->end();
	}
	
}
?>