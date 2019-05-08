<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chart".
 *
 * @property int $id
 * @property int $id_excel
 * @property string $judul
 * @property string $field_y
 * @property int $field_x
 * @property string $tipe_chart
 * @property int $width
 * @property int $height
 * @property string $kolom
 */
class Chart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_excel', 'width', 'height' ,'urutan'], 'integer'],
            [['judul'], 'safe'],
            [['field_y','field_x','field_x_tipe','tipe_chart', 'kolom'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_excel' => 'Id Excel',
            'judul' => 'Judul',
            'field_y' => 'Field Y',
            'field_x' => 'Field X',
            'tipe_chart' => 'Tipe Chart',
            'width' => 'Width',
            'height' => 'Height',
            'kolom' => 'Kolom',
        ];
    }
}
