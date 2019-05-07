<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "excel".
 *
 * @property int $id
 * @property string $keterangan
 * @property string $judul
 * @property string $nama_tabel
 * @property string $create_at
 * @property string $update_at
 * @property string $create_by
 * @property string $update_by
 * @property string $field_data
 * @property string $excel
 */
class Excel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'excel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_at', 'update_at'], 'safe'],
            [['field_data', 'excel'], 'string'],
            [['judul','excel'],'required'],
            [['excel'],'file','extensions'=>'csv','maxSize'=>1024 * 1024 * 5],
            [['keterangan', 'judul', 'nama_tabel', 'create_by', 'update_by'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keterangan' => 'Keterangan',
            'judul' => 'Judul',
            'nama_tabel' => 'Nama Tabel',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'create_by' => 'Create By',
            'update_by' => 'Update By',
            'field_data' => 'Field Data',
            'excel' => 'Excel',
        ];
    }
}
