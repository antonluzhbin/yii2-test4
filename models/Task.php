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
     * количество дней ti для подготовки к данному экзамену
     * не определено: можно ли подготовиться к экзамену в день его сдачи - предположим что нет?
     *
     * @return array
     */
    public static function calculate()
    {
        $items = Task::find()->orderBy([ 'date' => 'ASK', 'day' => 'ASK' ])->all();

        $tasks = [];
        foreach ($items as $item) {
            $day = $item->day;
            $begin = (new DateTime($item->date))->modify("-{$day} day")->format('Y-m-d');
            $end = (new DateTime($item->date))->format('Y-m-d');
            if (!empty($examDates[$end])) {
                throw new Exception('DOUBLE_DATE_ERROR');
            }
            $exam = new Exam();
            $exam->id = $item->id;
            $exam->day = $item->day;
            $exam->begin = $begin;
            $exam->end = $end;
            $exam->name = $item->name;
            $tasks[] = $exam;
        }

        usort($tasks, function ($a, $b) {
                return $a->begin == $b->begin ? $a->day <=> $b->day : $a->begin <=> $b->begin;
        });

        $items = [];
        $prev = null;
        foreach ($tasks as $task) {
            if (empty($prev) || ((new DateTime($task->begin)) > (new DateTime($prev->end)))) {
                $prev = $task;
                $items[] = $task;
            } else {
                throw new Exception('DATA_ERROR');
            }
        }

        return $items;
    }
}
