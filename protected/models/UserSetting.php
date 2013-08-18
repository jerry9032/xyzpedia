<?php

/**
 * This is the model class for table "xyz_user_setting".
 *
 * The followings are the available columns in table 'xyz_user_setting':
 * @property integer $id
 * @property string $lang
 * @property integer $contrib_num
 * @property integer $illustrate_num
 * @property integer $show_home
 * @property integer $show_resident
 * @property integer $show_tags
 */
class UserSetting extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSetting the static model class
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
		return 'xyz_user_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, contrib_num, illustrate_num, show_home, show_resident, show_tags', 'numerical', 'integerOnly'=>true),
			array('lang', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lang, contrib_num, illustrate_num, show_home, show_resident, show_tags', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lang' => 'Lang',
			'contrib_num' => 'Contrib Num',
			'illustrate_num' => 'Illustrate Num',
			'show_home' => 'Show Home',
			'show_resident' => 'Show Resident',
			'show_tags' => 'Show Tags',
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
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('contrib_num',$this->contrib_num);
		$criteria->compare('illustrate_num',$this->illustrate_num);
		$criteria->compare('show_home',$this->show_home);
		$criteria->compare('show_resident',$this->show_resident);
		$criteria->compare('show_tags',$this->show_tags);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}