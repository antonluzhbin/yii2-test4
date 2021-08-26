<?php


namespace app\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;

class Exam extends Model
{
    public $id;
    public $day;
    public $begin;
    public $end;
    public $name;

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Название экзамена', 'ru'),
            'begin' => \Yii::t('app', 'Дата подготовки', 'ru'),
            'end' => \Yii::t('app', 'Дата экзамена', 'ru'),
            'day' => \Yii::t('app', 'Количество дней для подготовки', 'ru'),
        ];
    }

    public static function getDataProvider($items)
    {
        $provider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => false,
        ]);

        return  $provider;
    }
}