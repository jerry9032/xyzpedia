<?php

/**
 * This is the model class for table "xyz_notify".
 *
 * The followings are the available columns in table 'xyz_notify':
 * @property string $ID
 * @property string $author_id
 * @property string $target_id
 * @property string $post_id
 * @property string $type
 * @property integer $created
 */
class Notify extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Notify the static model class
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
		return 'xyz_notify';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created', 'numerical', 'integerOnly'=>true),
			array('author_id, target_id', 'length', 'max'=>11),
			array('post_id', 'length', 'max'=>20),
			array('type', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, author_id, target_id, post_id, type, created', 'safe', 'on'=>'search'),
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
			'author'		=> array(self::BELONGS_TO, 'User', 'author_id'),
			'contrib'		=> array(self::BELONGS_TO, 'Contrib', 'post_id'),
			'post'			=> array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'author_id' => 'Author',
			'target_id' => 'Target',
			'post_id' => 'Post',
			'type' => 'Type',
			'created' => 'Created',
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
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('target_id',$this->target_id,true);
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}