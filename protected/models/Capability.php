<?php

/**
 * This is the model class for table "xyz_capability".
 *
 * The followings are the available columns in table 'xyz_capability':
 * @property integer $ID
 * @property string $role
 * @property string $group
 * @property string $list
 */
class Capability extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Capability the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function roleList($className=__CLASS__)
	{
		return array(
			"administrator",
			"committee",
			"editor",
			"author",
			"foward",
			"contributor",
			"subscriber",
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xyz_capability';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role, group, list', 'required'),
			array('role', 'length', 'max'=>16),
			array('group', 'length', 'max'=>10),
			array('list', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, role, group, list', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'role' => 'Role',
			'group' => 'Group',
			'list' => 'List',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('group',$this->group,true);
		$criteria->compare('list',$this->list,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}