<?php

/**
 * This is the model class for table "karl_menu_action".
 *
 * The followings are the available columns in table 'karl_menu_action':
 * @property integer $menuaction_id
 * @property integer $menu_id
 * @property string $menuaction_desc
 * @property string $action_url
 * @property integer $group_id
 * @property integer $is_popup_window
 */
class Menuaction extends AActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	public function tableName()
	{
		return 'MST_MENUACTION';
	}

	public function rules()
	{
		return array(
			array('menu_id, group_id, action_url', 'required'),
			array('menu_id, group_id, is_popup_window', 'numerical', 'integerOnly'=>true),
			array('menuaction_desc', 'length', 'max'=>100),
			array('action_url', 'length', 'max'=>255),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('menuaction_id, menu_id, menuaction_desc, action_url, group_id, is_popup_window', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'menu' => array(self::BELONGS_TO, 'Menu', 'menu_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'menuaction_id' => 'Menuaction',
			'menu_id' => 'Menu',
			'menuaction_desc' => 'Menuaction Desc',
			'action_url' => 'Action Url',
			'group_id' => 'Group',
			'is_popup_window' => 'Is Popup Window'
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('menuaction_id',$this->menuaction_id);
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('menuaction_desc',$this->menuaction_desc,true);
		$criteria->compare('action_url',$this->action_url,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('is_popup_window',$this->is_popup_window);

		$sort = new CSort();
		$sort->defaultOrder = 'group_id ASC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	
}