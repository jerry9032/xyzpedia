<?php

/**
 * This is the model class for table "xyz_poll_log".
 *
 * The followings are the available columns in table 'xyz_poll_log':
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property integer $question_id
 * @property integer $answer_id
 * @property integer $created
 * @property integer $anonymous
 */
class PollLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PollLog the static model class
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
		return 'xyz_poll_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, ip, question_id, answer_id, created, anonymous', 'required'),
			array('user_id, question_id, answer_id, created, anonymous', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, ip, question_id, answer_id, created, anonymous', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'question' => array(self::BELONGS_TO, 'PollQuestion', 'question_id'),
			'answer' => array(self::BELONGS_TO, 'PollAnswer', 'answer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'ip' => 'Ip',
			'question_id' => 'Question',
			'answer_id' => 'Answer',
			'created' => 'Created',
			'anonymous' => 'Anonymous',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer_id',$this->answer_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('anonymous',$this->anonymous);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}