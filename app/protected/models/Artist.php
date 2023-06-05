<?php

/**
 * This is the model class for table "artist".
 *
 * The followings are the available columns in table 'artist':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $country
 * @property string $year_formed
 * @property integer $genre_id
 */
class Artist extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Artist the static model class
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
		return 'artist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('genre_id', 'numerical', 'integerOnly'=>true),
			array('name, country', 'length', 'max'=>45),
			array('year_formed', 'length', 'max'=>4),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, country, year_formed, genre_id', 'safe', 'on'=>'search'),
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
			'id' => '主键ID',
			'name' => '专辑名',
			'description' => '专辑详情',
			'country' => '国家',
			'year_formed' => '年代',
			'genre_id' => '流派',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('year_formed',$this->year_formed,true);
		$criteria->compare('genre_id',$this->genre_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}