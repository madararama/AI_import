<?php

namespace app\controllers;

use Yii;
use app\models\Chart;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChartController implements the CRUD actions for Chart model.
 */
class ChartController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Chart models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Chart::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chart model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Chart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_excel)
    {
        $model = new Chart();
        $model->id_excel = $id_excel;
        $model->width = 100;
        $modelExcel = $this->findModelExcel($id_excel);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/excel/view', 'id' => $id_excel]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelExcel'=>$modelExcel
        ]);
    }

    /**
     * Updates an existing Chart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modelExcel = $this->findModelExcel($model->id_excel);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/excel/view', 'id' => $modelExcel->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelExcel'=>$modelExcel
        ]);
    }

    /**
     * Deletes an existing Chart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelExcel = $this->findModelExcel($model->id_excel);
        $model->delete();

        return $this->redirect(['/excel/view', 'id' => $modelExcel->id]);
    }

    /**
     * Finds the Chart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chart::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

         
    protected function findModelExcel($id)
    {
        if (($model = \app\models\Excel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
