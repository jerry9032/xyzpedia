<?php

/**
 * This is the model class for table "xyz_post".
 *
 * The followings are the available columns in table 'xyz_post':
 * @property string $id
 * @property string $submit_id
 * @property integer $created
 * @property integer $changed
 * @property string $content
 * @property string $title
 * @property string $excerpt
 * @property string $status
 * @property string $thumbnail
 * @property integer $category_id
 * @property string $comment
 * @property string $slug
 * @property string $serial
 * @property string $type
 * @property string $comments
 * @property integer $show
 * @property integer $light
 */
class Post extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return 'xyz_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, title, excerpt, thumbnail, category_id', 'required'),
			array('created, changed, category_id, show, light', 'numerical', 'integerOnly'=>true),
			array('submit_id', 'length', 'max'=>10),
			array('status, comment, type, comments', 'length', 'max'=>20),
			array('thumbnail', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>200),
			array('serial', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, submit_id, created, changed, content, title, excerpt, status, thumbnail, category_id, comment, slug, serial, type, comments, show, light', 'safe', 'on'=>'search'),
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
			//'author'		=> array(self::BELONGS_TO, 'User', 'author_id'),
			//'author_info'	=> array(self::BELONGS_TO, 'UserInfo', 'author_id'),
			'author'		=> array(self::MANY_MANY, 'User', 'xyz_post2author(post_id, author_id)'),
			'author_info'	=> array(self::MANY_MANY, 'UserInfo', 'xyz_post2author(post_id, author_id)'),
			'author_no_reg'	=> array(self::HAS_MANY, 'Post2Author', 'post_id'),
			'submit'		=> array(self::BELONGS_TO, 'User', 'submit_id'),
			'category'		=> array(self::BELONGS_TO, 'Category', 'category_id'),
			'comment'		=> array(self::HAS_MANY, 'Comment', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'submit_id' => 'Submit',
			'created' => 'Created',
			'changed' => 'Changed',
			'content' => 'Content',
			'title' => 'Title',
			'excerpt' => 'Excerpt',
			'status' => 'Status',
			'thumbnail' => 'Thumbnail',
			'category_id' => 'Category',
			'comment' => 'Comment',
			'slug' => 'Slug',
			'serial' => 'Serial',
			'type' => 'Type',
			'comments' => 'Comments',
			'show' => 'Show',
			'light' => 'Light',
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
		$criteria->compare('submit_id',$this->submit_id,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('changed',$this->changed);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('excerpt',$this->excerpt,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('comments',$this->comments,true);
		$criteria->compare('show',$this->show);
		$criteria->compare('light',$this->light);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}