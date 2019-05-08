<?php

namespace app\controllers;

use Yii;
use app\models\Excel;
use app\models\excelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Chart;
use yii\data\ActiveDataProvider;

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

        $dataProvider = new ActiveDataProvider([
            'query' => Chart::find(),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'chart_dataProvider' => $dataProvider,
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
        $model->scenario = "upload_file";
        if ($model->load(Yii::$app->request->post())) {
            $model->excel = UploadedFile::getInstance($model, 'excel');


            if($model->validate()){   

                $model->scenario = "save_filename";
                $excel =  $model->excel;
                $filenm = $model->judul;
                $filename = 'uploads/'. "$filenm." . $excel->extension;
                $excel->saveAs($filename);
                $model->excel = $filename; 

                $filecsvtemp = file($filename);

                $db = \yii::$app->db;
                $first = true;
                $field_array = [];
                $nama_tbl = "tbl_".preg_replace("/[^_A-Za-z0-9?!]/",'',strtolower( preg_replace('/\s+/', '_',trim($model->judul))));
               
                # cari tipe data field
                $first_type = true;
                $tipe_data = [];
                foreach($filecsvtemp as $data){
                    if($first_type){
                        $first_type = false;
                    }else{
                        $hasil = explode(',',$data);
                        $no = 0;
                        foreach($hasil as $fd){
                            if(ctype_digit($fd)){
                                $tipe_data[$no] = " INTEGER(20)";
                            }elseif($this->validateDate($fd)){
                                $tipe_data[$no] = " DATE";
                            }else{
                                $tipe_data[$no] = " VARCHAR(255)";
                            }
                            $no++;
                        }
                        break;
                    }
                }

                # proses CSV
                foreach($filecsvtemp as $data){
                    if($first){
                        $hasil = explode(',',$data);
                        $first = false;
                        $create_tbl = " CREATE TABLE IF NOT EXISTS $nama_tbl (";
                        $create_tbl .= "id_primarykey INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
                        $no = 0;
                        foreach($hasil as $field){
                            $safe_field = preg_replace("/[^_A-Za-z0-9?!]/",'',strtolower(preg_replace('/\s+/', '_',trim($field))));
                            $create_tbl .= " $safe_field ".$tipe_data[$no].",";
                            array_push($field_array,$safe_field);
                            $no++;
                        }
                        $create_tbl .= "created_date DATETIME";
                        $create_tbl .= " ) ";
                        $db->createCommand($create_tbl)->execute();
                    }else{
                        $hasil = explode(',',$data);
                        $insert_array = [];
                        $no = 0;
                        if(count($hasil) != count($field_array)){
                            // die("TIDAK BOLEH KOMA");
                            continue;
                        }
                        foreach($hasil as $field){
                            $insert_array = array_merge($insert_array,[
                                $field_array[$no] => trim($field)
                            ]);
                            $no++;
                        }
                        $insert_array = array_merge($insert_array,[
                            "created_date" => date("Y-m-d H:i:s")
                        ]);
                        $db->createCommand()->insert($nama_tbl,$insert_array)->execute();
                    }                    
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

                if($model->save()){
                    return $this->redirect(['excel/index']);
                }
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
      
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
 
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

    public function actionUpdateResult($id,$excel_id)
    {
        $model = $this->findModel($excel_id);
        $field = explode(",",$model->field_data);
        $db = \yii::$app->db;
        $sql = "SELECT * FROM ".$model->nama_tabel." WHERE id_primarykey=:id";
        $data_detail = $db->createCommand($sql)->bindValues([":id"=>$id])->queryOne();
        $data = new \yii\base\DynamicModel($field);
        $data->addRule($field, 'safe');
        foreach($field as $fd){
            $data->$fd = $data_detail[$fd];
        }

        if ($data->load(Yii::$app->request->post())) {
            $save_array = [];
            foreach($data as $key=>$val){
                $save_array = array_merge($save_array,[$key=>$val]);
            }
            $db->createCommand()->update($model->nama_tabel,$save_array, 'id_primarykey=:id')
            ->bindValues([":id"=>$id])
            ->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update_result', [
            'model' => $model,
            'data' => $data,
        ]);
    }

 
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $nama_tbl = $model->nama_tabel;
        $file= $model->judul;
        unlink('uploads/'.$file .".csv");
        $sql = "DROP TABLE IF EXISTS $nama_tbl";
        \yii::$app->db->createCommand($sql)->execute();
        $model->delete();

        return $this->redirect(['index']);
    }

    
    public function actionDeleteResult($id,$excel_id)
    {
        $model = $this->findModel($excel_id);
        $nama_tbl = $model->nama_tabel;
        \Yii::$app->db->createCommand()->delete($nama_tbl, 'id_primarykey=:id')->bindValues([":id"=>$id])->execute();
        return $this->redirect(['view','id'=>$model->id]);
    }
     
    protected function findModel($id)
    {
        if (($model = Excel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
