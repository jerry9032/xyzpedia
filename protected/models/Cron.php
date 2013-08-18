<?php

/**
 * This is the model class for table "xyz_cron".
 *
 * The followings are the available columns in table 'xyz_cron':
 * @property integer $id
 * @property string $time
 * @property string $type
 * @property string $subtype
 * @property string $day
 * @property string $action
 * @property string $error
 */
class Cron extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cron the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xyz_cron';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time', 'length', 'max'=>4),
			array('type, subtype, day, action', 'length', 'max'=>8),
			array('error', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, type, subtype, day, action, error', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'time' => 'Time',
			'type' => 'Type',
			'subtype' => 'Subtype',
			'day' => 'Day',
			'action' => 'Action',
			'error' => 'Error',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('subtype',$this->subtype,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('error',$this->error,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}