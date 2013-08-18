<?php

/**
 * This is the model class for table "xyz_user2tag".
 *
 * The followings are the available columns in table 'xyz_user2tag':
 * @property string $user_id
 * @property string $tag_id
 * @property integer $tag_order
 */
class User2Tag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User2Tag the static model class
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
		return 'xyz_user2tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag_order', 'numerical', 'integerOnly'=>true),
			array('user_id, tag_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, tag_id, tag_order', 'safe', 'on'=>'search'),
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
			"user" => array(self::BELONGS_TO, "User", "user_id",
							"order" => "tag_order"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'tag_id' => 'Tag',
			'tag_order' => 'Tag Order',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('tag_id',$this->tag_id,true);
		$criteria->compare('tag_order',$this->tag_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}