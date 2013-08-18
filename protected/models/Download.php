<?php

/**
 * This is the model class for table "xyz_download".
 *
 * The followings are the available columns in table 'xyz_download':
 * @property string $ID
 * @property string $title
 * @property string $filename
 * @property integer $created
 * @property integer $appoint
 * @property string $hits
 * @property integer $user
 * @property string $group
 */
class Download extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Download the static model class
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
		return 'xyz_download';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, filename', 'required'),
			array('created, appoint, user', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('hits', 'length', 'max'=>11),
			array('group', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, title, filename, created, appoint, hits, user, group', 'safe', 'on'=>'search'),
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
			'uploader' => array(self::BELONGS_TO, 'User', 'user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'title' => 'Title',
			'filename' => 'Filename',
			'created' => 'Created',
			'appoint' => 'Appoint',
			'hits' => 'Hits',
			'user' => 'User',
			'group' => 'Group',
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

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('appoint',$this->appoint);
		$criteria->compare('hits',$this->hits,true);
		$criteria->compare('user',$this->user);
		$criteria->compare('group',$this->group,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}