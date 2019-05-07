<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\data\SqlDataProvider;

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

<div class='row'>
        <?php  
                // $attr = ["metode_pemilihan"];

        foreach($attr as $field){ ?>
            <div class="col-md-4">
            <!-- <div id="chart_<?php // echo $field;?>" style="height:300px;width:300px;"> -->
            <!-- </div> -->
            <?php
            $sql = "SELECT COUNT(*) as count_data,$field as keyword FROM ".$model->nama_tabel." GROUP BY $field";
            $data = $db->createCommand($sql)->queryAll();
            // print_r($data);
            // die();
            ?>
       
       <script>

        var dataProvider = [];
        <?php foreach($data as $dt){ ?>
             var resp = {
                "name":"<?php echo $dt['keyword'];?>",
                "y":parseInt(<?php echo $dt['count_data'];?>),
            }
            dataProvider.push(resp);
        <?php } ?>
  
        console.log(dataProvider)
            Highcharts.chart('chart_<?php echo $field;?>', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    // events: {
                    //     drilldown: function(){

                    //     }
                    // }
                },
                title: {
                    text: '<?php echo $field;?>'
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
                },
                series: [ {
                    name: '',
                    colorByPoint: true,
                    data: dataProvider
                    }
                ],
            });

            
</script>

</div>

 <?php  } ?>
     <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php 
    $column = array_merge([['class' => 'yii\grid\SerialColumn']],$attr);
    // $column = array_merge([['class' => 'yii\grid\ActionColumn']],$column);
    ?>
    <?= GridView::widget([
        'filterModel' => false,
        'dataProvider' => $dataProvider,
        'columns' => $column,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
