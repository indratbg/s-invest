<?php

/**
 * This is the model class for table "tdpmenu".
 *
 * The followings are the available columns in table 'tdpmenu':
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string $menu_name
 * @property string $default_url
 * @property integer $menu_order
 * @property integer $is_active
 */
class Menu extends AActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	public function tableName()
	{
		return 'MST_MENU_SSS';
	}

	public function rules()
	{
		return array(
			array('parent_id, menu_name, menu_order, is_active', 'required'),
			array('parent_id, menu_order, is_active', 'numerical', 'integerOnly'=>true),
			array('menu_name', 'length', 'max'=>100),
			array('default_url', 'length', 'max'=>255),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('menu_id, parent_id, menu_name, default_url, menu_order, is_active', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'menuactions' => array(self::HAS_MANY, 'Menuaction',array('menu_id'=>'menu_id'),'order'=>'group_id ASC'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'menu_id' => 'Menu',
			'parent_id' => 'Parent',
			'menu_name' => 'Menu Name',
			'default_url' => 'Default Url',
			'menu_order' => 'Menu Order',
			'is_active' => 'Is Active',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('menu_name',$this->menu_name,true);
		$criteria->compare('default_url',$this->default_url,true);
		$criteria->compare('menu_order',$this->menu_order);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// Remove all submenu child
	public function removeSubmenuChild($parent_id)
	{	 
		$listMenuSub 	= Menu::model()->with('menuactions')->findAll('parent_id=:parent_id',array(':parent_id'=>$parent_id));
		if($listMenuSub !== NULL){
			foreach($listMenuSub as $modelMenuSub):		
				$this->removeSubmenuChild($modelMenuSub->menu_id);
				$modelMenuSub->delete();
			endforeach;
		}
	}


			
}