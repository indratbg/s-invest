<?php

/**
 * This is the model class for table "tdpusergroupmenu".
 *
 * The followings are the available columns in table 'tdpusergroupmenu':
 * @property integer $usergroupmenu_id
 * @property integer $usergroup_id
 * @property integer $menu_id
 */
class Usergroupmenu extends AActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'MST_USERGROUPMENU';
	}

	public function rules()
	{
		return array(
			
			array('usergroup_id, menu_id', 'required'),
			array('usergroup_id, menu_id', 'numerical', 'integerOnly'=>true),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroupmenu_id, usergroup_id, menu_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'menu' => array(self::BELONGS_TO, 'Menu',array('menu_id'=>'menu_id'),'joinType'=>'INNER JOIN'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'usergroupmenu_id' => 'Usergroupmenu',
			'usergroup_id' => 'Usergroup',
			'menu_id' => 'Menu',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('usergroupmenu_id',$this->usergroupmenu_id);
		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('menu_id',$this->menu_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}