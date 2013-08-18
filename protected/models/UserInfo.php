<?php

/**
 * This is the model class for table "xyz_user_info".
 *
 * The followings are the available columns in table 'xyz_user_info':
 * @property string $id
 * @property string $email
 * @property string $url
 * @property integer $qq
 * @property string $hometown
 * @property string $resident
 * @property integer $regdate
 * @property string $upline
 * @property integer $downline
 * @property string $descript
 * @property string $ip
 * @property string $career
 * @property string $school
 * @property double $score
 * @property string $activation
 * @property integer $last_login
 */
class UserInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserInfo the static model class
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
		return 'xyz_user_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, hometown, resident, regdate, upline, ip, career, activation', 'required'),
			array('qq, regdate, downline, last_login', 'numerical', 'integerOnly'=>true),
			array('score', 'numerical'),
			array('email, url', 'length', 'max'=>100),
			array('hometown, resident, school', 'length', 'max'=>64),
			array('upline, ip, career', 'length', 'max'=>16),
			array('activation', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, url, qq, hometown, resident, regdate, upline, downline, descript, ip, career, school, score, activation, last_login', 'safe', 'on'=>'search'),
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
			 'user' => array( self::HAS_ONE, 'User', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'url' => 'Url',
			'qq' => 'Qq',
			'hometown' => 'Hometown',
			'resident' => 'Resident',
			'regdate' => 'Regdate',
			'upline' => 'Upline',
			'downline' => 'Downline',
			'descript' => 'Descript',
			'ip' => 'Ip',
			'career' => 'Career',
			'school' => 'School',
			'score' => 'Score',
			'activation' => 'Activation',
			'last_login' => 'Last Login',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('qq',$this->qq);
		$criteria->compare('hometown',$this->hometown,true);
		$criteria->compare('resident',$this->resident,true);
		$criteria->compare('regdate',$this->regdate);
		$criteria->compare('upline',$this->upline,true);
		$criteria->compare('downline',$this->downline);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('career',$this->career,true);
		$criteria->compare('school',$this->school,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('activation',$this->activation,true);
		$criteria->compare('last_login',$this->last_login);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}