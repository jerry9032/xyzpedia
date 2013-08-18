<?php

/**
 * This is the model class for table "xyz_contrib_comment".
 *
 * The followings are the available columns in table 'xyz_contrib_comment':
 * @property string $ID
 * @property string $contrib_id
 * @property string $author_id
 * @property string $comment
 * @property integer $created
 * @property integer $from
 */
class ContribComment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContribComment the static model class
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
		return 'xyz_contrib_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment, created', 'required'),
			array('created, from', 'numerical', 'integerOnly'=>true),
			array('contrib_id, author_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, contrib_id, author_id, comment, created, from', 'safe', 'on'=>'search'),
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
			'author'	=> array(self::BELONGS_TO, 'User', 'author_id'),
			'contrib'	=> array(self::BELONGS_TO, 'Contrib', 'contrib_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'contrib_id' => 'Contrib',
			'author_id' => 'Author',
			'comment' => 'Comment',
			'created' => 'Created',
			'from' => 'From',
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
		$criteria->compare('contrib_id',$this->contrib_id,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('from',$this->from);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}