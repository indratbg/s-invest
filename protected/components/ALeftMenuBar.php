<?php
class ALeftMenuBar 
{
	
	/**
	* @var array the HTML options for the link Search (A) tag.
	*/
	public $searchLinkHtmlOptions=array();
	
	/**
	* @var string URL for search button
	*/
	public $searchUrl;
	
	/**
	* @var array the HTML options for the link List (A) tag.
	*/
	public $listLinkHtmlOptions=array();
	
	/**
	* @var string URL for list button
	*/
	public $listUrl;
	
	/**
	* @var array the HTML options for the link View (A) tag.
	*/
	public $viewLinkHtmlOptions=array();
	
	/**
	* @var string URL for view button
	*/
	public $viewUrl;
	
	/**
	* @var array the HTML options for the link Create (A) tag.
	*/
	public $createLinkHtmlOptions=array();
	
	/**
	* @var string URL for create button
	*/
	public $createUrl;
	
	/**
	* @var array the HTML options for the link Update (A) tag.
	*/
	public $updateLinkHtmlOptions=array();
	
	/**
	* @var string URL for update button
	*/
	public $updateUrl;
	
	/**
	* @var array the HTML options for the link Delete (A) tag.
	*/
	public $deleteLinkHtmlOptions=array();
	
	/**
	* @var string URL for delete button
	*/
	public $deleteUrl;
	
	/**
	* @var array the HTML options for the link Print (A) tag.
	*/
	public $printUrl;
	public $printLinkHtmlOptions=array();
	
	/**
	* @var string the template that is used to render the content in each data cell.
	* These default tokens are recognized: {search}, {list}, {view}, {create}, {update}, {delete} and {print}. If the {@link buttons} property
	* defines additional buttons, their IDs are also recognized here. For example, if a button named 'preview'
	* is declared in {@link buttons}, we can use the token '{preview}' here to specify where to display the button.
	*/
	public $template 	= '{list}';
	public $templateArr = array(); 
	
	public $activeMenu  = 'list';
	public $leftMenu 	= array();
	
	
	public function __construct($model,$template = '{list}',$activeMenu = 'list')
	{
		$this->template 	= $template;
		
		$clntemplate        = substr($template, 1, strlen($template)-2);
		$this->templateArr  = explode('}{',$clntemplate);
		$this->activeMenu	= $activeMenu;
		
		$this->init($model);
	}
	
	private function init($model) 
	{
		$listTemplate   = array('label'=>'List','icon'=>'list','url'=>array('index'));
		$createTemplate = array('label'=>'Create','icon'=>'plus','url'=>array('create'));
		$updateTemplate = array('label'=>'Update','icon'=>'pencil','url'=>array('update','id'=>$model->primaryKey()));
		$deleteTemplate = array('label'=>'Delete','icon'=>'trash','confirm'=>'Are you sure you want to delete this item?','url'=>'#',
								'linkOptions'=>array('submit'=>array('delete','id'=>$model->primaryKey())));
		$printTemplate  = array('label'=>'Print','icon'=>'print');
		
		// AH: this is additional left menu in USERGROUP 
		//     PLEASE INCLUDE FULL URL (from module through action)
		$menuconfigTemplate  = array('label'=>'Menu Config','icon'=>'task','url'=>array('core/usergroup/menuconf','id'=>$model->primaryKey()));
		$userlistTemplate    = array('label'=>'User List','icon'=>'user','url'=>array('core/usergroup/userlist','id'=>$model->primaryKey()));
		
		
	
		foreach(array('list','view','create','update','delete','print') as $id)
		{
			if(in_array($id,$this->templateArr)):
				$tempTemplate  	= ${$id.'Template'};
				if($id == $this->activeMenu )
					$tempTemplate   = array_merge($tempTemplate,array('itemOptions'=>array('class'=>'active')));
				
				$this->leftMenu[] = $tempTemplate;
			endif;
		}
		
		/*
		foreach(array('user list','menu config') as $id)
		{
			$tempTemplate  = ${str_replace(' ','',$id).'Template'};
			if(in_array($id,$this->templateArr)){
				if($this->hasAccess($tempTemplate['url']))
					$this->leftMenu[] = $tempTemplate;
			}	
		}*/
	}


	public function getLeftMenu()
	{
		return $this->leftMenu;
	}
	
	public function hasAccess($url)
	{
		$arrUsergroupId  = Yii::app()->user->usergroup_id;
		$strUserGroupId  = implode(',', $arrUsergroupId);
			
		$query 			 = "SELECT COUNT(menuaction_id) AS cnt_menuaction FROM tdpusergroupakses
								WHERE usergroup_id IN ($strUserGroupId) 
								AND menuaction_id = '".$tempTemplate['url']."' ";	
		$cnt_menuaction	 = DAO::queryRowSql($query);
		
		return empty($cnt_menuaction);
	}
}