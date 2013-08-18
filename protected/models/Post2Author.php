<?php

/**
 * This is the model class for table "xyz_post2author".
 *
 * The followings are the available columns in table 'xyz_post2author':
 * @property string $ID
 * @property string $post_id
 * @property string $author_id
 * @property string $author_name
 */
class Post2Author extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post2Author the static model class
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
		return 'xyz_post2author';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_id, author_id', 'required'),
			array('post_id, author_id', 'length', 'max'=>20),
			array('author_name', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, post_id, author_id, author_name', 'safe', 'on'=>'search'),
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
			'post'		=> array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'post_id' => 'Post',
			'author_id' => 'Author',
			'author_name' => 'Author Name',
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
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('author_name',$this->author_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}