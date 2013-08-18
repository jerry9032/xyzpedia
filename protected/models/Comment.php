<?php

/**
 * This is the model class for table "xyz_comment".
 *
 * The followings are the available columns in table 'xyz_comment':
 * @property string $id
 * @property string $post_id
 * @property string $author_name
 * @property string $author_ip
 * @property integer $created
 * @property string $content
 * @property string $approved
 * @property string $agent
 * @property string $parent_id
 * @property string $user_id
 */
class Comment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'xyz_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author_name, content', 'required'),
			array('created', 'numerical', 'integerOnly'=>true),
			array('post_id, approved, parent_id, user_id', 'length', 'max'=>20),
			array('author_ip', 'length', 'max'=>100),
			array('agent', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, post_id, author_name, author_ip, created, content, approved, agent, parent_id, user_id', 'safe', 'on'=>'search'),
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
			'author'	=> array(self::BELONGS_TO, 'User', 'user_id'),
			'post'		=> array(self::BELONGS_TO, 'Post', 'post_id'),
			'parent'	=> array(self::BELONGS_TO, 'Comment', 'parent_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Post',
			'author_name' => 'Author Name',
			'author_ip' => 'Author Ip',
			'created' => 'Created',
			'content' => 'Content',
			'approved' => 'Approved',
			'agent' => 'Agent',
			'parent_id' => 'Parent',
			'user_id' => 'User',
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
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('author_name',$this->author_name,true);
		$criteria->compare('author_ip',$this->author_ip,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('approved',$this->approved,true);
		$criteria->compare('agent',$this->agent,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}