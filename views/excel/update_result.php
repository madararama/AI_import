<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="excel-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],'id'=>'formSubmit']); ?>

    <?php 
    $field = explode(",",$model->field_data);
    foreach($field as $fd){
        echo $form->field($data, $fd)->textInput(['maxlength' => true]);
    }
    ?>
 
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' =>   'btn btn-primary']) ?>
    </div>
 
    <?php ActiveForm::end(); ?>

</div>
 