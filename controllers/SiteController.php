<?php

namespace app\controllers;

use app\models\Booking;
use app\models\Category;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use DateTime;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Booking::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $linkSearch = Url::to([ 'site/search' ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'linkSearch' => $linkSearch
        ]);
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;
        $startDate = (new DateTime())->format('Y-m-d');

        $from_date = $request->post('from_date', $startDate);
        $to_date = $request->post('to_date', $startDate);
        $date_range = $request->post('date_range', $from_date . ' - ' . $to_date);

        $dataProvider = new ArrayDataProvider([
            'allModels' => Category::search($from_date, $to_date),
            'pagination' => false,
        ]);

        $linkSearch = Url::to([ 'site/search' ]);
        $linkInsert = Url::to([ 'site/insert' ]);

        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'linkSearch' => $linkSearch,
            'linkInsert' => $linkInsert,
            'date_range' => $date_range
        ]);
    }

    public function actionInsert()
    {
        $request = Yii::$app->request;
        $model = new Booking();
        $model->name = $request->post('name');
        $model->email = $request->post('email');
        $model->date_arrival = $request->post('from_date');
        $model->date_departure = $request->post('to_date');
        $model->category_id = $request->post('category');

        if ($model->validate() && ((new DateTime($model->date_arrival)) <= (new DateTime($model->date_departure)))) {
            if (!$model->save()) {
                Yii::$app->session->setFlash('SAVE_ERROR');
            } else {
                Yii::$app->session->setFlash('SAVE_OK');
            }
        } else {
             Yii::$app->session->setFlash('VALIDATE_ERROR');
        }

        return $this->redirect(['search']);
    }

    public function actionDelete($id)
    {
        $model = Booking::findOne($id);
        if ($model == null) {
            throw new HttpException(404);
        }

        $model->delete();
        Yii::$app->session->setFlash('DELETE_OK');
        return $this->redirect(['index']);
    }
}
