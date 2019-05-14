<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Chart */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chart-form">

    <?php $form = ActiveForm::begin(); ?>

 
    <?= $form->field($model, 'judul')->textInput(['rows' => 6]) ?>

    <?php 
    $data_field = explode(",",$modelExcel->field_data);
    $dropdown_data = [];
    foreach($data_field as $field){
        $dropdown_data = array_merge($dropdown_data,[$field=>$field]);
    }
    $sa = [];
	for ($i = 1;$i <= 10;$i++) {
        $sa = array_merge($sa,[$i=>$i]);
    }
    ?>
    <?= $form->field($model, 'field_y')->dropDownList($dropdown_data) ?>
    <?= $form->field($model, 'field_x')->dropDownList($dropdown_data) ?>
    <?= $form->field($model, 'field_x_tipe')->dropDownList(['COUNT'=>'COUNT','SUM'=>'SUM']) ?>
    <?= $form->field($model, 'tipe_chart')->dropDownList(['Pie'=>'Pie','Bar'=>'Bar']) ?>
    <?php //$form->field($model, 'width')->textInput() ?>
    <?= $form->field($model, 'height')->textInput() ?>
    <?= $form->field($model, 'urutan')->dropDownList($sa) ?>

    <?= $form->field($model, 'kolom')->dropDownList(['col-md-4'=>'1/3 Halaman','col-md-6'=>'1/2 Halaman','col-md-12'=>'1 Halaman']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['excel/view'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
