<?php

class UsergroupController extends AAdminController
{
	public function actionAjxSearchUser() 
	{
        $search_key   = $_REQUEST['search'];
		$usergroup_id = $_REQUEST['Usergroup']['usergroup_id'];
		/*$unregistered_user = User::model()->active()->with('usergroupdetail')->findAll(
            							array('condition'=>"(usergroup_id <> $usergroup_id OR usergroup_id IS NULL ) AND LOWER(user_name) LIKE LOWER(:user_name)",
            									'params' => array(':user_name'=>'%'.$search_key.'%')));
		
		*/
		$query = "SELECT a.*,usergroupl_id,usergroup_id FROM MST_USER a, MST_USERGROUPDETAIL b
					WHERE a.user_id = b.user_id (+)
					AND expiry_date > TO_DATE('".date('Y-m-d')."','YYYY-MM-DD') AND sts_suspended = '".AConstant::IS_FLAG_N."' 
					AND (usergroup_id <> $usergroup_id OR usergroup_id IS NULL) AND LOWER(user_name) LIKE LOWER('%$search_key%')
					ORDER BY a.user_name";
							
		$unregistered_user = DAO::queryAllSql($query);
		
		$resp['status']  = 'error';
		
		if(!empty($unregistered_user)):
			$resp['status']  = 'success';
			foreach($unregistered_user as $item)
				$resp['users'][] = array('user_id' => $item['user_id'] ,'user_name'=> $item['user_name']);
		else:
			$resp['content']  = 'user not found';
		endif;
			
        echo CJSON::encode($resp);
    }
	
	
	/* AR 2013
	 * RETURN
	 *  @array ['resp']    = success / failure
	 *         ['content'] = jsonObject
	 */
	
	public function actionAjxCmbMenuAction()
	{
		$resp['status']  = 'error';
		$resp['content'] = 'failed to get data';
		 
		if(isset($_POST['Usergroup'])){
			$model = new Usergroup();
			$model->attributes = $_POST['Usergroup'];
			
			$sUsergroup_id 	   =  implode("','",Yii::app()->user->usergroup_id);
			
			$resp['status']  = 'success';
			
			/*
			$resp['content'] =  CHtml::listData( Usergroupakses::model()->with('menuaction')->findAll(
													array('order'=>'menuaction.action_url',
														  'condition'=>'usergroup_id IN (:usergroup_id) AND menuaction.menu_id = :menu_id',
														  'params'=>array(':usergroup_id'=>$sUsergroup_id,':menu_id'=>$model->menu_id))),
												  'menuaction_id', 'menuaction.action_url');
			*/
			
			$query = "SELECT a.menuaction_id, action_url
						FROM MST_USERGROUPAKSES a, MST_MENUACTION b
						WHERE a.menuaction_id = b.menuaction_id(+)
						AND usergroup_id IN (".$sUsergroup_id.")
						AND b.menu_id = '$model->menu_id'
						ORDER BY action_url";
						
			$resp['content'] = CHtml::listData(DAO::queryAllSql($query),'a.menuaction_id','action_url'); 
			
												  
		}
		echo CJSON::encode($resp);
	} 
	
	
	
	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Usergroup;
		
		if(isset($_POST['Usergroup']))
		{
			$model->attributes=$_POST['Usergroup'];
			$row  	= DAO::queryRowSql("SELECT SEQ_USERGROUP_ID.NEXTVAL AS pk_id FROM dual");
			$pk_id  = $row['pk_id'];
			$model->usergroup_id  = $pk_id;
				
			if($model->save()) {
				Yii::app()->user->setFlash('success', "Create Successfully");
				$this->redirect(array('view','id'=>$model->usergroup_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Usergroup']))
		{
			$model->attributes = $_POST['Usergroup'];
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', "Update Successfully");
				$this->redirect(array('view','id'=>$model->usergroup_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Usergroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Usergroup']))
			$model->attributes=$_GET['Usergroup'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionUserlist()
	{
		$model =  Usergroup::model()->find("usergroup_id = ".$_REQUEST['id']);
		
		if(isset($_POST['registereduser']) || isset($_POST['flag']))
		{
			$registered = @$_POST['registereduser'];
			
			if(!is_array($registered))
            	$registered = array($registered);
        	
			Usergroupdetail::model()->deleteAll(array('condition'=>'usergroup_id=:usergroup_id','params'=>array(':usergroup_id'=>$model->usergroup_id)));
			foreach($registered as $user_id) 
        	{
        		if($user_id)
				{		
		            $newuser = new Usergroupdetail();
		            $newuser->user_id 	   = $user_id;
		            $newuser->usergroup_id = $model->usergroup_id;
		            $newuser->save(FALSE);
				}
        	}	
			
        	$this->redirect(array('view','id'=>$model->usergroup_id));	
		}
        

		$this->render('userlist',array(
			'model'=>$model,
		));
	}
	
	private function rekurInsertUsergroupMenu($usergroup_id,$menu_id,&$tempe)
	{
		$sccss 		= DAO::executeSql("INSERT INTO MST_USERGROUPMENU(usergroup_id,menu_id) VALUES (".$usergroup_id.",".$menu_id.")");
		$tahu 		= DAO::queryRowSql("SELECT parent_id FROM MST_MENU_SSS WHERE menu_id = ".$menu_id);
		$parent_id  = $tahu['parent_id'];
		if($parent_id != 0 && !in_array($parent_id,$tempe)){
			$tempe[] 	= $parent_id;
			$this->rekurInsertUsergroupMenu($usergroup_id,$parent_id,$tempe);
		}
	}
	
	public function actionMenuConf($id)
	{
		if(isset($_POST['Usergroupakses']))
		{
			$flag  = -1;
			$sccss = DAO::executeSql("DELETE FROM MST_USERGROUPMENU WHERE usergroup_id=".$id);
			$sccss = DAO::executeSql("DELETE FROM MST_USERGROUPAKSES WHERE usergroup_id=".$id);
			$tempe    = -1;
			$tempearr = array($tempe);
			
			foreach ($_POST['Usergroupakses'] as $val) 
			{
				$temp = explode('#', $val);
				
				if($flag != $temp[0]){	// insert usergroup menu
					$this->rekurInsertUsergroupMenu($id,$temp[0],$tempearr);
				}
				
				$sccss = DAO::executeSql("INSERT INTO MST_USERGROUPAKSES(usergroup_id,menuaction_id) VALUES(".$id.",".$temp[1].")");
				$flag  = $temp[0]; 
			}	
		}
		
		
		$model 	 = $this->loadModel($id);
		$group_id = $id;
		$criteria = new CDbCriteria();
		$criteria->condition = 'parent_id = 0';
		$criteria->order 	 = 't.menu_order ASC';
		$criteria->select 	= array('menu_name','menu_id');
		
		$dtTreeViewRoot;
		$menuHeader  	  	= Menu::model()->findAll($criteria);
		$this->menuCounter  = 0;
		
		$i = 0;
		foreach($menuHeader as $mHeader)
			$dtTreeView[$i++] = $this->genMenu($mHeader,$group_id);
		
		// inserting webroot to structure
		$dtTreeView = array(array('text'=>'webroot','children'=>$dtTreeView));
		$this->render('menuconf',array(
			'model'=>$model,
			'dtTreeView'=>$dtTreeView,
		));
	}
	
	private $menuCounter;
	
	private function genMenuAction($menu,$group_id)
	{
		$txtMenuAction = '';
		$j=1;
		
        $txtMenuAction .= '<div class="row-fluid"/>';
		foreach($menu->menuactions as $mAction)
		{
			$class = 'act-1 ';
			if($mAction->group_id > 1)
			{
				for($i = $mAction->group_id;$i <= (count($menu->menuactions)+1);$i++)
				$class .= 'act-'.$i.' ';
			}
			
			// if alerady given access before checked the action
			$condition		= array('condition'=>'usergroup_id=:usergroup_id AND menuaction_id=:menuaction_id',
									'params'=>array(':usergroup_id'=>$group_id,
													':menuaction_id'=>$mAction->menuaction_id));
                                                    
			$usergroupakses = Usergroupakses::model()->findAll($condition);
		    
			$txtMenuAction .= '<div class="span4">
			                    <input type="checkbox" class="'.$class.'" 
								name="Usergroupakses[]" '.((empty($usergroupakses))?'':'checked="checked"').'
								value="'.($mAction->menu_id.'#'.$mAction->menuaction_id).'" 
								title="'.$mAction->menuaction_desc.'"/>'.$mAction->action_url.' </div>';
			
            if($j%3 === 0)
                $txtMenuAction .= '<div class="clear"></div>
                					</div>
                					<div class="row-fluid">';
             
			$j++;
		}
        
        $txtMenuAction .= '<div class="clear"></div></div>';
		
		return $txtMenuAction;	
	}
	
	private function genMenuActionGroup($menu,$group_id)
	{
		$menuActionGroup = AConstant::$menuactiongroup;
		
		$query 			 = "SELECT MAX(group_id) AS mx_group_id FROM MST_MENUACTION 
					  		WHERE menu_id = ".$menu->menu_id." AND menuaction_id IN  
					  		( SELECT menuaction_id FROM MST_USERGROUPAKSES
					  		 	WHERE usergroup_id = ".$group_id." )";
		$mx_group_id	 = DAO::queryRowSql($query);
		
		// reduce from array to integer
		$mx_group_id 	= $mx_group_id['mx_group_id'];
		if(empty($mx_group_id))
			$mx_group_id = 1;
 
		$txtMenuActionGroup = '';
		$i = 1;
		foreach($menuActionGroup as $menuactiongroup_id => $menuactiongroup_name)
		{
			$txtMenuActionGroup .= ' <input type="radio" name="rad-act-group-'.$this->menuCounter.'" class="rad-act-group" class="rad-act-group" '.
										 (($i==$mx_group_id)?'checked="checked"':'').' value="act-'.$menuactiongroup_id.'" />'.
										 $menuactiongroup_name;
			$i++;
		}	
		
		
		$this->menuCounter++;
		return $txtMenuActionGroup;
	}
	
	private function genMenuDetail($menu,$group_id)
	{
		$txtMenuAction		= $this->genMenuAction($menu,$group_id);
		$txtMenuActionGroup = $this->genMenuActionGroup($menu,$group_id); 
		$textMenuDetail     = <<<EOF
			<div style='float:left;'>
				<div class='tree-menu-name'>$menu->menu_name</div>
			</div>
			<div style='float:right;' class="span9">
				<div class='tree-menu-act-group'>$txtMenuActionGroup</div>
				<div class='tree-menu-act'>$txtMenuAction</div>
			</div>
			<div style='clear:both'></div>
EOF;
		return $textMenuDetail;
	}
	
	private function genMenu($menu,$group_id)
	{
		$textMenuDetail 	= $menu->menu_name;
		
		// cheking have children or not
		//$menuChild 		= Menu::model()->with('menuactions')->findAll(array('order'=>'menu_order ASC', 'condition'=>'parent_id=:parent_id', 
		//															'params'=>array(':parent_id'=>$menu->menu_id)));
		
		$query = "SELECT * FROM MST_MENU_SSS
					WHERE parent_id = '$menu->menu_id'
					ORDER BY menu_order";
					
		
		
		$menuChild = array();		
		$menuChild2 = DAO::queryAllSql($query);
		
		$x = 0;
		foreach($menuChild2 as $row)
		{
			$menuChild[$x] = new menu;
			
			$menuChild[$x]->menu_id = $row['menu_id'];
			$menuChild[$x]->parent_id = $row['parent_id'];
			$menuChild[$x]->menu_name = $row['menu_name'];
			$menuChild[$x]->default_url = $row['default_url'];
			$menuChild[$x]->menu_order = $row['menu_order'];
			$menuChild[$x]->is_active = $row['is_active'];
			
			$menu_id = $row['menu_id'];
			
			$query = "SELECT a.*, b.menuaction_id,b.menuaction_desc,b.action_url,b.group_id FROM MST_MENU_SSS a, MST_MENUACTION b
					WHERE a.menu_id = b.menu_id(+)
					AND a.menu_id = '$menu_id'
					ORDER BY group_id";
					
			$menuActions = DAO::queryAllSql($query);
			
			$y = 0;
			foreach($menuActions as $row2)
			{
				$menuChild[$x]->menuactions[$y] = new Menuaction;
				
				$menuChild[$x]->menuactions[$y]->menu_id = $row2['menu_id'];	
				$menuChild[$x]->menuactions[$y]->menuaction_id = $row2['menuaction_id'];
				$menuChild[$x]->menuactions[$y]->menuaction_desc = $row2['menuaction_desc'];
				$menuChild[$x]->menuactions[$y]->action_url = $row2['action_url'];
				$menuChild[$x]->menuactions[$y]->group_id = $row2['group_id'];
				
				$y++;
			}
			
			$x++;
		}
			
		if($menuChild == NULL)
		{
			//checking have menu action or not
			if($menu->menuactions[0]->menuaction_id != NULL)
				$textMenuDetail = $this->genMenuDetail($menu,$group_id);
			
			return array('text'=>$textMenuDetail);
		}
		else
		{
			$arrTemp = array();
			foreach($menuChild as $mChild)		
				$arrTemp[] 		= $this->genMenu($mChild,$group_id);
			
			return array('text'=>$menu->menu_name,'children'=>$arrTemp);
		}
	}
	
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Usergroup::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usergroup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
