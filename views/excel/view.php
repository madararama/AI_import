<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\data\SqlDataProvider;
use yii\widgets\ListView;


$db =  \Yii::$app->db;
$count = $db->createCommand('SELECT COUNT(*) FROM '.$model->nama_tabel)->queryScalar();
// die($count);
$attr = explode(",",$model->field_data);
$provider = new SqlDataProvider([
    'sql' => 'SELECT * FROM '.$model->nama_tabel,
    'totalCount' => $count,
    
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => $attr
    ],
]);

// returns an array of data rows
$dataProvider = $provider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\excelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Excels', 'url' => ['index']];
$this->params['breadcrumbs'][] =  $model->judul;?>
<center>
<h1><u><?php echo $model->judul;?></u></h1>
</center>
<div class="excel-index">
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>


    <hr>
    <p>
        <?= Html::a('Create Chart', ['/chart/create','id_excel'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <div class='row'>
  

        <?php  
                // $attr = ["metode_pemilihan"];
        $data_chart = \app\models\Chart::find()->where(["id_excel"=>$model->id])->orderBy("urutan ASC")->all();
        foreach($data_chart as $field){ ?>
            <div class="<?php echo $field->kolom;?>">


             <div id="chart_<?php  echo $field->id;?>" style="height:<?php echo $field->kolom;?>px;width:<?php echo $field->width;?>%;"> 
            </div>
                                <?= Html::a('Update', ['/chart/update', 'id' => $field->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['/chart/delete', 'id' => $field->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
            </div>
            <?php
            $count_sum = $field->field_x_tipe;
            $field_x = $field->field_x;
            $field_y = $field->field_y;

            $sql = "SELECT $count_sum($field_x) as value_data,$field_y as keyword FROM ".$model->nama_tabel." GROUP BY $field_y";
            $data = $db->createCommand($sql)->queryAll();
            
            ?>
       
       <script>

        var dataProvider = [];
        <?php foreach($data as $dt){ ?>
             var resp = {
                "name":"<?php echo $dt['keyword'];?>",
                "y":parseInt(<?php echo $dt['value_data'];?>),
            }
            dataProvider.push(resp);
        <?php } ?>
  
        console.log(dataProvider)
            Highcharts.chart('chart_<?php echo $field->id;?>', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: '<?php echo strtolower($field->tipe_chart) ?>',
                },
                title: {
                    text: '<?php echo $field->judul;?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },exporting: { enabled: false },
                series: [ {
                    name: '',
                    colorByPoint: true,
                    data: dataProvider
                    }
                ],
            });
</script>


 <?php  } ?>
 </div>

     <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php 
    $actionButton = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}{delete}',
        'buttons' => [
            'update' => function($id, $data) use ($model) {
                return Html::a('<span class="btn btn-sm btn-default">UPDATE</span>', ['update-result', 'id' => $data['id_primarykey'],'excel_id'=>$model->id], ['title' => 'Update', 'id' => 'modal-btn-view']);
            },
            'delete' => function($url, $data) use($model){
                return Html::a('<span class="btn btn-sm btn-danger">DELETE</span>', ['delete-result', 'id' => $data['id_primarykey'],'excel_id'=>$model->id], ['title' => 'Delete', 'class' => '', 'data' => ['confirm' => 'Are you absolutely sure ? You will lose all the information about this user with this action.', 'method' => 'post', 'data-pjax' => false],]);
            }
        ]
    ];
    $column = array_merge([$actionButton],$attr);
    $column = array_merge([['class' => 'yii\grid\SerialColumn']],$column);
    ?>
    <?= GridView::widget([
        'filterModel' => false,
        'dataProvider' => $dataProvider,
        'columns' => $column,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
