<?php

/**
 * This is the model class for table "xyz_download_log".
 *
 * The followings are the available columns in table 'xyz_download_log':
 * @property string $id
 * @property string $download_id
 * @property string $user_id
 * @property integer $created
 * @property string $ip
 */
class DownloadLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DownloadLog the static model class
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
		return 'xyz_download_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('download_id, user_id', 'required'),
			array('created', 'numerical', 'integerOnly'=>true),
			array('download_id, user_id', 'length', 'max'=>10),
			array('ip', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, download_id, user_id, created, ip', 'safe', 'on'=>'search'),
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
			"user"		=> array(self::BELONGS_TO, "User", "user_id"),
			"download"	=> array(self::BELONGS_TO, "Download", "download_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'download_id' => 'Download',
			'user_id' => 'User',
			'created' => 'Created',
			'ip' => 'Ip',
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
		$criteria->compare('download_id',$this->download_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}