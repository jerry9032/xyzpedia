<?php

/**
 * This is the model class for table "xyz_contrib".
 *
 * The followings are the available columns in table 'xyz_contrib':
 * @property string $id
 * @property string $author_id
 * @property string $organization
 * @property integer $mode
 * @property integer $created
 * @property integer $changed
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property string $category_id
 * @property string $status
 * @property integer $editor_id
 * @property integer $councilor_id
 * @property integer $comments
 * @property integer $appoint
 * @property integer $from
 * @property integer $edit_lock
 * @property integer $edit_locker
 */
class Contrib extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contrib the static model class
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
		return 'xyz_contrib';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required'),
			array('mode, created, changed, editor_id, councilor_id, comments, appoint, from, edit_lock, edit_locker', 'numerical', 'integerOnly'=>true),
			array('author_id, category_id', 'length', 'max'=>10),
			array('organization', 'length', 'max'=>64),
			array('status', 'length', 'max'=>20),
			array('excerpt', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, author_id, organization, mode, created, changed, title, excerpt, content, category_id, status, editor_id, councilor_id, comments, appoint, from, edit_lock, edit_locker', 'safe', 'on'=>'search'),
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
			'category'	=> array(self::BELONGS_TO, 'Category', 'category_id'),
			'editor'	=> array(self::BELONGS_TO, 'User', 'editor_id'),
			'councilor'	=> array(self::BELONGS_TO, 'User', 'councilor_id'),
			'locker'	=> array(self::BELONGS_TO, 'User', 'edit_locker'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'author_id' => 'Author',
			'organization' => 'Organization',
			'mode' => 'Mode',
			'created' => 'Created',
			'changed' => 'Changed',
			'title' => 'Title',
			'excerpt' => 'Excerpt',
			'content' => 'Content',
			'category_id' => 'Category',
			'status' => 'Status',
			'editor_id' => 'Editor',
			'councilor_id' => 'Councilor',
			'comments' => 'Comments',
			'appoint' => 'Appoint',
			'from' => 'From',
			'edit_lock' => 'Edit Lock',
			'edit_locker' => 'Edit Locker',
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
		$criteria->compare('author_id',$this->author_id,true);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('mode',$this->mode);
		$criteria->compare('created',$this->created);
		$criteria->compare('changed',$this->changed);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('excerpt',$this->excerpt,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('editor_id',$this->editor_id);
		$criteria->compare('councilor_id',$this->councilor_id);
		$criteria->compare('comments',$this->comments);
		$criteria->compare('appoint',$this->appoint);
		$criteria->compare('from',$this->from);
		$criteria->compare('edit_lock',$this->edit_lock);
		$criteria->compare('edit_locker',$this->edit_locker);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}