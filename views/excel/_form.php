<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Excel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="excel-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],'id'=>'formSubmit']); ?>

    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'nama_tabel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'excel')->fileinput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
 
    <?php ActiveForm::end(); ?>

</div>

<script>
$('#formSubmit').on("submit",function(e){
      
        var formData = new FormData(this);
        var formURL = $("#formSubmit").attr("action");
        $.ajax(
        {
            url : formURL,
            type: "POST",
            data : formData,
            contentType: false,
            processData: false,
            success:function(data, textStatus, jqXHR) 
            {
                window.location = "{$urlIndex}";
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                alert("gagal");      
            }
        });
        e.preventDefault();
        e.unbind(); untuk mencegah berkali kali submit
    });
</script>