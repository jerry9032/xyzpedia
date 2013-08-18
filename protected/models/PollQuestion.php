<?php

/**
 * This is the model class for table "xyz_poll_question".
 *
 * The followings are the available columns in table 'xyz_poll_question':
 * @property integer $id
 * @property string $title
 * @property integer $created
 * @property integer $votes
 * @property integer $active
 * @property integer $expire
 * @property integer $multiple
 * @property integer $hidden_type
 * @property integer $force_login
 * @property integer $voters
 * @property integer $order
 * @property string $succ_prompt
 */
class PollQuestion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PollQuestion the static model class
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
		return 'xyz_poll_question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, votes, active, expire, multiple, hidden_type, force_login, voters, order', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('succ_prompt', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, created, votes, active, expire, multiple, hidden_type, force_login, voters, order, succ_prompt', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'PollAnswer', 'question_id',
							"order" => "`order`"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'created' => 'Created',
			'votes' => 'Votes',
			'active' => 'Active',
			'expire' => 'Expire',
			'multiple' => 'Multiple',
			'hidden_type' => 'Hidden Type',
			'force_login' => 'Force Login',
			'voters' => 'Voters',
			'order' => 'Order',
			'succ_prompt' => 'Succ Prompt',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('votes',$this->votes);
		$criteria->compare('active',$this->active);
		$criteria->compare('expire',$this->expire);
		$criteria->compare('multiple',$this->multiple);
		$criteria->compare('hidden_type',$this->hidden_type);
		$criteria->compare('force_login',$this->force_login);
		$criteria->compare('voters',$this->voters);
		$criteria->compare('order',$this->order);
		$criteria->compare('succ_prompt',$this->succ_prompt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}