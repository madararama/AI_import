<?php

namespace app\controllers;

use Yii;
use app\models\Excel;
use app\models\excelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ExcelController implements the CRUD actions for Excel model.
 */
class ExcelController extends Controller
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
     * Lists all Excel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new excelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Excel model.
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
     * Creates a new Excel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Excel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $excel = UploadedFile::getInstance($model, 'excel');


            if($model->validate()){
                //$model->save();
                if (!empty($excel)) {

                    $filename = 'uploads/'. 'excel.' . $excel->extension;
                    $excel->saveAs($filename);
                    $model->excel = $filename;
                   

                    $filecsvtemp = file($filename);

                    echo "<pre>";
                    // print_r($filecsvtemp);
                    // die();

                    $db = \yii::$app->db;
                    $first = true;
                    $field_array = [];
                    $nama_tbl = "tbl_".preg_replace("/[^_A-Za-z0-9?!]/",'',strtolower( preg_replace('/\s+/', '_',trim($model->judul))));
           
                    foreach($filecsvtemp as $data){
                        if($first){
                            $hasil = explode(',',$data);
                            $first = false;
                            $create_tbl = " CREATE TABLE IF NOT EXISTS $nama_tbl (";
                            $create_tbl .= "id_primarykey INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
                            foreach($hasil as $field){
                                $safe_field = preg_replace("/[^_A-Za-z0-9?!]/",'',strtolower(preg_replace('/\s+/', '_',trim($field))));
                                $create_tbl .= " $safe_field VARCHAR(255),";
                                array_push($field_array,$safe_field);
                            }
                            $create_tbl .= "created_date DATETIME";
                            $create_tbl .= " ) ";
                            $db->createCommand($create_tbl)->execute();
                        }else{
                            $hasil = explode(',',$data);
                            $insert_array = [];
                            $no = 0;
                            // print_r($hasil);
                            // die();
                            if(count($hasil) != count($field_array)){
                                // die("TIDAK BOLEH KOMA");
                                continue;
                            }
                            foreach($hasil as $field){
                                // if(count($field_array) < $no){
                                $insert_array = array_merge($insert_array,[
                                    $field_array[$no] => trim($field)
                                ]);
                                $no++;
                                // }
                            }
                            $insert_array = array_merge($insert_array,[
                                "created_date" => date("Y-m-d H:i:s")
                            ]);
                            // print_r($insert_array);
                            // die();
                            $db->createCommand()->insert($nama_tbl,$insert_array)->execute();
                        }
                        // $modelnew = new DistribusiPaket;
                        

                    }

                    $field_data = "";
                    $first = true;
                    foreach($field_array as $field){
                        if($first){
                            $field_data .= "$field";
                            $first = false;
                        }else{
                            $field_data .= ",$field";
                        }
                    }
                    $model->nama_tabel = $nama_tbl;
                    $model->field_data = $field_data;
                    $model->create_at = date("Y-m-d H:i:s"); 
                    $model->save();
                }
            }

        } else {
        return $this->render('create', [
            'model' => $model,
        ]);
        }
    }

    /**
     * Updates an existing Excel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Excel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $nama_tbl = $model->nama_tabel;
        $sql = "DROP TABLE IF EXISTS $nama_tbl";
        \yii::$app->db->createCommand($sql)->execute();
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Excel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Excel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Excel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
