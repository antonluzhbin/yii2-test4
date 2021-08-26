<?php

namespace app\controllers;

use app\models\Exam;
use app\models\Task;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use Exception;

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
            'query' => Task::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $linkInsert = Url::to([ 'site/update' ]);
        $linkCalculate = Url::to([ 'site/calculate' ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'linkInsert' => $linkInsert,
            'linkCalculate' => $linkCalculate
        ]);
    }

    public function actionUpdate($id = null)
    {
        if (empty($id)) {
            $model = new Task();
        } else {
            $model = Task::findOne($id);
            if ($model == null) {
                throw new HttpException(404);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if (!$model->save()) {
                    Yii::$app->session->setFlash('SAVE_ERROR');
                } else {
                    Yii::$app->session->setFlash('SAVE_OK');
                }
            } else {
                 Yii::$app->session->setFlash('VALIDATE_ERROR');
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = Task::findOne($id);
        if ($model == null) {
            throw new HttpException(404);
        }

        $model->delete();
        Yii::$app->session->setFlash('DELETE_OK');
        return $this->redirect(['index']);
    }

    public function actionCalculate()
    {
        try {
            $items = Task::calculate();
        } catch (Exception $e) {
            Yii::$app->session->setFlash($e->getMessage());
            $items = [];
        }

        $dataProvider = Exam::getDataProvider($items);

        return $this->render('calculate', [
            'dataProvider' => $dataProvider
        ]);
    }
}
