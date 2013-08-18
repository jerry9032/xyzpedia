<?php

/**
 * This is the model class for table "xyz_poll_answer".
 *
 * The followings are the available columns in table 'xyz_poll_answer':
 * @property integer $id
 * @property integer $question_id
 * @property string $answer
 * @property string $link
 * @property integer $order
 * @property integer $votes
 */
class PollAnswer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PollAnswer the static model class
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
		return 'xyz_poll_answer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_id, order, votes', 'numerical', 'integerOnly'=>true),
			array('answer', 'length', 'max'=>200),
			array('link', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, question_id, answer, link, order, votes', 'safe', 'on'=>'search'),
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
			'poll' => array(self::BELONGS_TO, 'PollQuestion', 'question_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'question_id' => 'Question',
			'answer' => 'Answer',
			'link' => 'Link',
			'order' => 'Order',
			'votes' => 'Votes',
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
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('votes',$this->votes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}