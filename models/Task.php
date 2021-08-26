<?php

namespace app\models;

use DateTime;
use yii\db\ActiveRecord;
use Exception;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $date
 * @property integer $day
 */
class Task extends ActiveRecord
{
    public static function tableName()
    {
        return '{{task}}';
    }

    public function rules()
    {
        return [
            [ ['name', 'date', 'day'], 'required' ],
            [ ['name'], 'match', 'pattern' => '/^[a-zA-Z]{1,10}$/' ],
            [ ['date'], 'date', 'format' => 'yyyy-MM-dd' ],
            [ ['day'], 'integer', 'min' => 1, 'max' => 30 ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Название экзамена', 'ru'),
            'date' => \Yii::t('app', 'Дата экзамена', 'ru'),
            'day' => \Yii::t('app', 'Количество дней для подготовки', 'ru'),
        ];
    }

    /**
     * в день проведения экзамена готовиться к другому невозможно
     * не может начать готовиться к i-му экзамену раньше чем ti дней до него
     * не может готовиться к двум экзаменам в один и тот же день
     * не определено: сколько готовиться - берем 1 день?
     * не определено: можно ли подготовиться к экзамену в день его сдачи - предположим что нет?
     *
     * @return array|ActiveRecord[]
     */
    public static function calculate()
    {
        $items = Task::find()->orderBy([ 'date' => 'ASK', 'day' => 'ASK' ])->all();

        $examDates = [];
        $tasks = [];
        foreach ($items as $item) {
            $day = $item->day + 1;
            $begin = (new DateTime($item->date))->modify("-{$day} day")->format('Y-m-d');
            $end = (new DateTime($item->date))->format('Y-m-d');
            if (!empty($examDates[$end])) {
                throw new Exception('DOUBLE_DATE_ERROR');
            }
            $examDates[$end] = $item->id;
            $tasks[] = [
                'id' => $item->id,
                'day' => $item->day,
                'begin' => $begin,
                'end' => $end,
                'name' => $item->name,
            ];
        }

        usort($tasks, function ($a, $b) {
                return $a['begin'] == $b['begin'] ? $a['day'] <=> $b['day'] : $a['begin'] <=> $b['begin'];
        });

        $items = [];
        foreach ($tasks as $task) {
            $ins = false;
            for ($i = 0; $i < $task['day']; $i++) {
                $dt = (new DateTime($task['begin']))->modify("+{$i} day")->format('Y-m-d');
                if (empty($examDates[$dt])) {
                    $task['begin'] = $dt;
                    $items[] = $task;
                    $examDates[$dt] = $task['id'];
                    $ins = true;
                    break;
                }
            }
            if (!$ins) {
                throw new Exception('DATA_ERROR');
            }
        }

        return $items;
    }
}
