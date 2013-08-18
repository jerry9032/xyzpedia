<?php

/**
 * This is the model class for table "xyz_category".
 *
 * The followings are the available columns in table 'xyz_category':
 * @property string $ID
 * @property string $name
 * @property string $short
 * @property string $tag
 * @property string $slug
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getCatList($className=__CLASS__)
	{
		$objs = parent::model($className)->findAll();

		$cats = array();
		foreach ($objs as $obj) {
			$cats[] = array(
				"ID" => $obj["ID"],
				"name" => $obj["name"],
				"short" => $obj["short"]
			);
		}
		return $cats;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xyz_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('short, tag', 'required'),
			array('name, slug', 'length', 'max'=>200),
			array('short', 'length', 'max'=>20),
			array('tag', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, name, short, tag, slug', 'safe', 'on'=>'search'),
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
			'posts'		=> array(self::HAS_MANY, 'Post', 'category_id'),
			'contribs'	=> array(self::HAS_MANY, 'Contrib', 'category_id'),
			'tags'		=> array(self::MANY_MANY, 'User', 'xyz_user2tag(user_id, tag_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'name' => 'Name',
			'short' => 'Short',
			'tag' => 'Tag',
			'slug' => 'Slug',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short',$this->short,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('slug',$this->slug,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}