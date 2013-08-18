<?php

/**
 * This is the model class for table "xyz_user".
 *
 * The followings are the available columns in table 'xyz_user':
 * @property string $id
 * @property string $login
 * @property string $pass
 * @property string $role
 * @property string $real_name
 * @property string $nick_name
 * @property integer $status
 * @property string $avatar
 * @property string $mobile
 * @property double $score
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getAuthorList($className=__CLASS__)
	{
		$criteria = new CDbCriteria();
		$criteria->condition =
			"`role` = 'administrator' OR ".
			"`role` = 'council' OR ".
			"`role` = 'author'";
		return parent::model($className)->findAll($criteria);
	}
	public static function getUserList($className=__CLASS__)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "`status` = 0";
		return parent::model($className)->findAll($criteria);
	}
	public static function getUserListString() {

		$users = User::getUserList();

		$str = '[';
		$first_user = true;
		foreach ($users as $user) {
			if ( !$first_user ) {
				$str.= ',';
			}
			$str.= '"'.$user->nick_name.'"';
			$first_user = false;
		}
		$str.= ']';
		return $str;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xyz_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role, nick_name, mobile', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('score', 'numerical'),
			array('login, nick_name', 'length', 'max'=>60),
			array('pass, avatar', 'length', 'max'=>64),
			array('role', 'length', 'max'=>256),
			array('real_name', 'length', 'max'=>50),
			array('mobile', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, pass, role, real_name, nick_name, status, avatar, mobile, score', 'safe', 'on'=>'search'),
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
			//'submit' => array(self::HAS_MANY, 'Post', 'submit_id'),
			// single author ↓
			//'author' => array(self::HAS_MANY, 'Post', 'author_id'),
			// multiple author ↓
			'posts' => array(self::MANY_MANY, 'Post', 'xyz_post2author(author_id, post_id)',
							'order' => 'posts.ID desc'),
			'submits' => array(self::HAS_MANY, 'Post', 'submit_id',
							'order' => 'submits.ID desc'),
			'info' => array(self::HAS_ONE, 'UserInfo', 'id'),
			'setting' => array(self::HAS_ONE, 'UserSetting', 'ID'),
			'tags' => array(self::MANY_MANY, 'Category', 'xyz_user2tag(user_id, tag_id)',
							'order' => 'tag_order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'pass' => 'Pass',
			'role' => 'Role',
			'real_name' => 'Real Name',
			'nick_name' => 'Nick Name',
			'status' => 'Status',
			'avatar' => 'Avatar',
			'mobile' => 'Mobile',
			'score' => 'Score',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('pass',$this->pass,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('real_name',$this->real_name,true);
		$criteria->compare('nick_name',$this->nick_name,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('score',$this->score);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}