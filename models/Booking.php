<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{booking}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $date_arrival
 * @property string $date_departure
 * @property integer $category_id
 *
 */
class Booking extends ActiveRecord
{
    public static function tableName()
    {
        return '{{booking}}';
    }

    public function rules()
    {
        return [
            [ ['name', 'email', 'date_arrival', 'date_departure', 'category_id'], 'required' ],
            [ ['name'], 'string', 'min' => 1, 'max' => 100 ],
            [ 'email', 'email' ],
            [ ['email'], 'string', 'min' => 5, 'max' => 100 ],
            [ ['date_arrival'], 'date', 'format' => 'yyyy-MM-dd' ],
            [ ['date_departure'], 'date', 'format' => 'yyyy-MM-dd' ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'Номер'),
            'name' => \Yii::t('app', 'Имя', 'ru'),
            'email' => \Yii::t('app', 'Email'),
            'date_arrival' => \Yii::t('app', 'Дата заезда', 'ru'),
            'date_departure' => \Yii::t('app', 'Дата выезда', 'ru'),
            'category_id' => \Yii::t('app', 'Категория', 'ru'),
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

}