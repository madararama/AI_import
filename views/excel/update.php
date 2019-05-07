<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Excel */

$this->title = 'Edit Data: ' . $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Excels', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->judul]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="excel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
