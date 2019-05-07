<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Excel */

$this->title = 'Create Excel';
$this->params['breadcrumbs'][] = ['label' => 'Excels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="excel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
    ]) ?>

</div>
