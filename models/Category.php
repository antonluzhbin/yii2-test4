<?php


namespace app\models;


use yii\db\ActiveRecord;
use DateTime;

/**
 * This is the model class for table "{{category}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $count
 */
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{category}}';
    }

    public function rules()
    {
        return [
            [ ['name', 'count'], 'required' ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Название', 'ru'),
            'count' => \Yii::t('app', 'Количество доступных комнат', 'ru'),
        ];
    }

    public static function search($from_date, $to_date)
    {
        $categories = self::find()->all();

        $max = [];
        $items = [];
        foreach ($categories as $category) {
            $items[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
                'count' => $category->count,
            ];
            $max[$category->id] = $category->count;
        }

        $aDates = [];
        for ($i = (new DateTime($from_date)); $i <= (new DateTime($to_date)); $i = $i->modify("+1 day")) {
            $aDates[$i->format('Y-m-d')] = $max;
        }

        $rows = (new \yii\db\Query())
            ->select([])
            ->from('booking')
            ->where([ 'between', 'date_arrival', $from_date, $to_date ])
            ->orWhere([ 'between', 'date_departure', $from_date, $to_date ])
            ->all();

        foreach ($rows as $row) {
            $start =  (new DateTime($row['date_arrival'])) <= (new DateTime($from_date)) ? (new DateTime($from_date)) : (new DateTime($row['date_arrival']));
            $end =  (new DateTime($row['date_departure'])) >= (new DateTime($to_date)) ? (new DateTime($to_date)) : (new DateTime($row['date_departure']));
            for ($i = $start; $i <= $end; $i = $i->modify("+1 day")) {
                $aDates[$i->format('Y-m-d')][$row['category_id']] -= 1;
            }
        }

        $min = $max;
        foreach ($aDates as $aDate) {
            foreach($aDate as $cat => $val) {
                if ($min[$cat] > $val) {
                    $min[$cat] = $val;
                }
            }
        }

        $res = [];
        foreach ($min as $cat => $val) {
            if ($val > 0) {
                $items[$cat]['count'] = $val;
                $res[] = $items[$cat];
            }
        }

        return $res;
    }
}