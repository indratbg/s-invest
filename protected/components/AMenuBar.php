<?php

class AMenuBar
{
	private function getDetailQuery($parent_id)
	{
		$arrUsergroupId  = Yii::app()->user->usergroup_id;
		$sUserGroupId 	 = implode(',', $arrUsergroupId);
		
		$query 			= "SELECT distinct menu_name,default_url,parent_id,a.menu_id, menu_order
							FROM MST_MENU_SSS a,
							( SELECT menu_id FROM MST_USERGROUPMENU 
							  WHERE usergroup_id IN ($sUserGroupId)
							) b
							WHERE a.menu_id = b.menu_id(+) AND
							b.menu_id IS NOT NULL AND
							UPPER(menu_name) not like ('%INBOX%') AND 
							parent_id = $parent_id AND LOWER(menu_name) like ('%')
							ORDER BY menu_order";
		
		$arrMenu 		= DAO::queryAllSql($query);
		return $arrMenu;
	}
		
	public function generateMenu($parent_id)
	{
		$arrMenu = $this->getDetailQuery($parent_id);
		$arrResult = null;
		
		for($i=0;$i<count($arrMenu);$i++)
		{
			$menu_id = $arrMenu[$i]['menu_id'];
			$menu_name = $arrMenu[$i]['menu_name'];
			$default_url = ($arrMenu[$i]['default_url']=='')?'#':Yii::app()->createUrl($arrMenu[$i]['default_url']);
			
			$query = "SELECT NVL((SELECT COUNT(menu_id) FROM MST_MENU_SSS WHERE parent_id = $menu_id),0) AS has_child FROM DUAL";
			$res = DAO::queryRowSql($query);
			
			$has_child = $res['has_child'];
			
			if($has_child > 0)
			{
				$arrResult[] = array('label'=>$menu_name,'url'=>$default_url,'items'=>$this->generateMenuDetail($menu_id));
			}
			else 
			{
				$arrResult[] = array('label'=>$menu_name,'url'=>$default_url);
			}//end else
		}
		
		return $arrResult;
	}
	
	public function generateMenuDetail($menu_id)
	{
		$arrMenu = $this->getDetailQuery($menu_id);
		$arrResult = null;
		for($i=0;$i<count($arrMenu);$i++)
		{
			$menu_id = $arrMenu[$i]['menu_id'];
			$menu_name = $arrMenu[$i]['menu_name'];
			$default_url = ($arrMenu[$i]['default_url']=='')?'#':Yii::app()->createUrl($arrMenu[$i]['default_url']);
			
			$query = "SELECT NVL((SELECT COUNT(menu_id) FROM MST_MENU_SSS WHERE parent_id = $menu_id),0) AS has_child FROM DUAL";
			$res = DAO::queryRowSql($query);
			
			$has_child = $res['has_child'];
			
			if($has_child)
			{
				$arrResult[] = array('label'=>$menu_name,'url'=>$default_url,'items'=>$this->generateMenuDetail($menu_id));
			}
			else 
			{
				$arrResult[] = array('label'=>$menu_name,'url'=>$default_url);
			}	
		}
		return $arrResult;
	}
}











