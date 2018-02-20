<?php

/**
 * This is the model class for table "tdpusergroup".
 *
 * The followings are the available columns in table 'tdpusergroup':
 * @property integer $usergroup_id
 * @property string $usergroup_name
 * @property integer $menu_id
 * @property integer $menuaction_id
 */
class Usergroup extends AActiveRecord
{
	public function __construct($scenario = 'insert')
	{
		parent::__construct($scenario);
		$this->logRecord=true;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'MST_USERGROUP';
	}

	public function rules()
	{
		return array(
			
			array('usergroup_name', 'required'),
			array('menu_id, menuaction_id', 'numerical', 'integerOnly'=>true),
			array('usergroup_name', 'length', 'max'=>100),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroup_id, usergroup_name, menu_id, menuaction_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'menuaction' => array(self::BELONGS_TO, 'Menuaction',array('menuaction_id'=>'menuaction_id'), 'joinType'=>'INNER JOIN'),
			'menu' => array(self::BELONGS_TO, 'Menu',array('menu_id'=>'menu_id'), 'joinType'=>'INNER JOIN'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'usergroup_id' => 'Usergroup',
			'usergroup_name' => 'Usergroup Name',
			'menu_id' => 'Menu',
			'menuaction_id' => 'Menu Action',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('usergroup_name',$this->usergroup_name,true);
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('menuaction_id',$this->menuaction_id);
		
		$sort = new CSort;
		
		$sort->defaultOrder='usergroup_name';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
		));
	}
}