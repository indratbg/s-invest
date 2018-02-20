<?php

/**
 * This is the model class for table "tdpusergroupakses".
 *
 * The followings are the available columns in table 'tdpusergroupakses':
 * @property integer $usergroupakses_id
 * @property integer $usergroup_id
 * @property integer $menuaction_id
 */
class Usergroupakses extends AActiveRecord
{	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'MST_USERGROUPAKSES';
	}

	public function rules()
	{
		return array(
			
			array('usergroup_id, menuaction_id', 'required'),
			array('usergroup_id, menuaction_id', 'numerical', 'integerOnly'=>true),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroupakses_id, usergroup_id, menuaction_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'usergroup' => array(self::BELONGS_TO, 'Usergroup',array('usergroup_id'=>'usergroup_id')),
			'menuaction' => array(self::BELONGS_TO, 'Menuaction',array('menuaction_id'=>'menuaction_id')),
		);
	}

	public function attributeLabels()
	{
		return array(
			'usergroupakses_id' => 'Usergroupakses',
			'usergroup_id' => 'Usergroup',
			'menuaction_id' => 'Menuaction',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('usergroupakses_id',$this->usergroupakses_id);
		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('menuaction_id',$this->menuaction_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}