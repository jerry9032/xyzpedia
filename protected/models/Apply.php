<?php

/**
 * This is the model class for table "xyz_apply".
 *
 * The followings are the available columns in table 'xyz_apply':
 * @property string $id
 * @property string $req_user_id
 * @property string $type
 * @property integer $status
 * @property string $pass_user_id
 * @property string $info
 */
class Apply extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Apply the static model class
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
		return 'xyz_apply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pass_user_id, info', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('req_user_id, pass_user_id', 'length', 'max'=>20),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, req_user_id, type, status, pass_user_id, info', 'safe', 'on'=>'search'),
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
			"req_user" => array(self::BELONGS_TO, "User", "req_user_id"),
			"pass_user" => array(self::BELONGS_TO, "User", "pass_user_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'req_user_id' => 'Req User',
			'type' => 'Type',
			'status' => 'Status',
			'pass_user_id' => 'Pass User',
			'info' => 'Info',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('req_user_id',$this->req_user_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('pass_user_id',$this->pass_user_id,true);
		$criteria->compare('info',$this->info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}