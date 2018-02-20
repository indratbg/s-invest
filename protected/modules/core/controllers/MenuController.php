<?php

class MenuController extends AAdminController
{

		
	public function actionAjxCreateDetail($id)
	{
		$this->layout 	= '//layouts/main_popup';
		$is_successsave = false;
		
		$model = new Menuaction;
		$model->menu_id = $id;
		
		if(isset($_POST['Menuaction']))
		{
			$model->attributes = $_POST['Menuaction'];
			$model->action_url = strtolower($model->action_url);
			if($model->save()):
				/*
				$row  			 = DAO::queryRowSql('SELECT SEQ_MENUACTION_ID.NEXTVAL AS pk_id FROM dual');
				$pk_id  		 = $row['pk_id'];
				$model->menu_id  = $pk_id;
				 * 
				 */
				
				$is_successsave = true;
			endif;
		}

		$this->render('_popupmenu',array(
			'model'=>$model,
			'is_successsave'=>$is_successsave
		));
	}
	
	public function actionAjxUpdateDetail($id)
	{
		$this->layout 	= '//layouts/main_popup';
		$is_successsave = false;
		$model 			= Menuaction::model()->findByPk($id);
		
		if(isset($_POST['Menuaction']))
		{
			$model->attributes = $_POST['Menuaction'];		
			$model->action_url = strtolower($model->action_url);	
			if($model->save()):
				$is_successsave = true;
			endif;
		}

		$this->render('_popupmenu',array(
			'model'=>$model,
			'is_successsave'=>$is_successsave
		));
		
	}
	
	public function actionDeleteDetail($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = Menuaction::model()->findByPk($id);
			$model->delete();
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');	
	}

	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate()
	{
		$model = new Menu;
		$model->parent_id = $_GET['parent_id'];
		
		if(isset($_POST['Menu']))
		{
			$model->attributes = $_POST['Menu'];
			if($model->parent_id == '')
				$model->parent_id  = 0;
			
			if($model->validate()) 
			{		
				$row  	= DAO::queryRowSql("SELECT SEQ_MENU_ID.NEXTVAL AS pk_id FROM dual");
				$pk_id  = $row['pk_id'];
				$model->menu_id  = $pk_id;
				
				$model->save(FALSE); 
				Yii::app()->user->setFlash('success', "Create Successfully");
				$this->redirect(array('update','id'=>$model->menu_id));
			}//end if model save
		}//end if isset

		$this->render('create',array(
			'model'=>$model,
		));//end this->render
	}//end public function action create

	public function actionUpdate($id)
	{
		$modelMenuAction = new Menuaction();
		$modelMenuAction->unsetAttributes();
		$modelMenuAction->menu_id = $id;
		
		$model=$this->loadModel($id);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
            if($model->parent_id == '')
				$model->parent_id  = 0;
			
			if($model->save()){
                Yii::app()->user->setFlash('success', 'Successfully update '.$model->menu_id);
				$this->redirect(array('view','id'=>$model->menu_id));
            }
		}

		$this->render('update',array(
			'model'=>$model,
			'modelMenuAction'=>$modelMenuAction
		));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$transaction = Yii::app()->db->beginTransaction();
			try{					
				$modelMenu = $this->loadModel($id);
				$modelMenu->removeSubmenuChild($id);
				$modelMenu->delete();
				$transaction->commit();
			}catch(Exception $ex){
				$transaction->rollback();
                throw new CHttpException(400,'Database connection error');
			}
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionReorder($id)
	{
		$model	= Menu::model()->findAll(array('order'=>'menu_order ASC', 'condition'=>'parent_id=:parent_id', 
										'params'=>array(':parent_id'=>$id)));
										
		$modelParent = Menu::model()->findByPk($id);

		if(isset($_POST['Menu']))
		{
			for($i=0;$i<count($_POST['Menu']['menu_id']);$i++)
			{
				$menu_ordering_id = $_POST['Menu']['menu_id'][$i];
				$query 			  = 'UPDATE MST_MENU_SSS SET menu_order = '.($i+1).' 
									 WHERE parent_id = '.$id.' AND menu_id = '.$menu_ordering_id.'';
				DAO::executeSql($query);
			}

			$this->redirect(array('index'));
		}
		
		$this->render('reorder',array(
			'model'=>$model,
			'id'=>$id,
			'modelParent'=>$modelParent
		));
	}
	
	private function genMenuButton($menu,$position = "haschild")
	{
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/create', 'parent_id'=>$menu->menu_id));
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/update', 'id'=>$menu->menu_id));
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/reorder', 'id'=>$menu->menu_id));
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/delete', 'id'=>$menu->menu_id));
		
		if($position == "haschild"):
			$menu_button = <<<EOF
			<div style='float:left;min-width:200px'>
				<div class='tree-menu-name'>{$menu->menu_name}</div>
			</div>
			<div style='float:left;min-width:200px'>
				 <a class='dp-link' title='Create' href='{$menu_url[0]}'><span style="float:left" class="ui-icon ui-icon-plus"></span></a>
				 <a class='dp-link' title='Update' href='{$menu_url[1]}'><span style="float:left" class="ui-icon ui-icon-pencil"></span></a>
				 <a class='dp-link' title='Reorder' href='{$menu_url[2]}'><span style="float:left" class="ui-icon ui-icon-arrowthick-2-n-s"></span></a>
				 <a class='dp-link btn-del' title='Hapus#{$menu->menu_id}' href='{$menu_url[3]}'><span style="float:left" class="ui-icon ui-icon-trash"></span></a>
			</div>
			<div style='clear:both'></div>
EOF;
		else:
					$menu_button = <<<EOF
			<div style='float:left;min-width:200px'>
				<div class='tree-menu-name'>{$menu->menu_name}</div>
			</div>
			<div style='float:left;min-width:200px'>
				 <a class='dp-link' title='Create' href='{$menu_url[0]}'><span style="float:left" class="ui-icon ui-icon-plus"></span></a>
				 <a class='dp-link' title='Update' href='{$menu_url[1]}'><span style="float:left" class="ui-icon ui-icon-pencil"></span></a>
				 <a class='dp-link btn-del' title='Delete#{$menu->menu_id}' href='{$menu_url[3]}'><span style="float:left" class="ui-icon ui-icon-trash"></span></a>
			</div>
			<div style='clear:both'></div>
EOF;
		endif;
		return $menu_button;
	}

	private function genMenuButtonWebroot()
	{
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/create', 'parent_id'=>0));
		$menu_url[]    = CHtml::normalizeUrl(array('/core/menu/reorder', 'id'=>0));
		
		$menu_button = <<<EOF
		<div style='float:left;min-width:200px'>
			<div class='tree-menu-name'>Webroot</div>
		</div>
		<div style='float:left;min-width:200px'>
			 <a class='dp-link' title='Create' href='{$menu_url[0]}'><span style="float:left" class="ui-icon ui-icon-plus"></span></a>
			 <a class='dp-link' title='Reorder' href='{$menu_url[1]}'><span style="float:left" class="ui-icon ui-icon-arrowthick-2-n-s"></span></a>
		</div>
		<div style='clear:both'></div>
EOF;
		return $menu_button;
	}

	
	
	public function genMenuDetailWButton($menu)
	{
		$menuChild 		= Menu::model()->findAll(array('order'=>'menu_order ASC', 'condition'=>'parent_id=:parent_id', 
													'params'=>array(':parent_id'=>$menu->menu_id)));
		$menu_parent_id = 0;
		
		if($menuChild == NULL)														// RECURSIVE END WHERE NO CHILD
			return array('text'=>$this->genMenuButton($menu,"nochild"));
		else
		{
			$arrTemp = array();
			foreach($menuChild as $mChild)
				$arrTemp[] 	= $this->genMenuDetailWButton($mChild);					// RECURSIVE			
				
			return array('text'=>$this->genMenuButton($menu),'children'=>$arrTemp);
		}
	}
	
	public function actionIndex()
	{
		$dtTreeViewRoot;
		$menuHeader  = Menu::model()->findAll(array('order'=>'menu_order ASC', 'condition'=>'parent_id = 0'));
		$dtTreeView	 = NULL;
		 
		$i = 0;
		foreach($menuHeader as $mHeader)
			$dtTreeView[$i++] = $this->genMenuDetailWButton($mHeader);
		
		
		$dtTreeView = array(array('text'=>$this->genMenuButtonWebroot(),'children'=>$dtTreeView));
		$this->render('index',array(
			'dtTreeView'=>$dtTreeView,
		));
	}

	public function loadModel($id)
	{
		$model=Menu::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
