<?php

/**
 * This is the model class for table "tdpusergroupdetail".
 *
 * The followings are the available columns in table 'tdpusergroupdetail':
 * @property integer $usergroupl_id
 * @property string $user_id
 * @property integer $usergroup_id
 */
class Usergroupdetail extends AActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    

	public function tableName()
	{
		return 'MST_USERGROUPDETAIL';
	}

	public function rules()
	{
		return array(
			
			array('user_id, usergroup_id', 'required'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroupl_id, user_id, usergroup_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
				'users' => array(self::BELONGS_TO, 'User',array('user_id'=>'user_id')),
		);
	}

	public function attributeLabels()
	{
		return array(
			'usergroupl_id' => 'Usergroupl',
			'user_id' => 'User',
			'usergroup_id' => 'Usergroup',
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('usergroupl_id',$this->usergroupl_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('usergroup_id',$this->usergroup_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}