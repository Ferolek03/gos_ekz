<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_category
 * @property string $gos_number
 * @property string $descriptions
 * @property string $descriptions_denied
 * @property int $status
 *
 * @property Category $category
 * @property User $user
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_category', 'gos_number', 'descriptions',], 'required'],
            [['id_user', 'id_category', 'status'], 'integer'],
            [['descriptions', 'descriptions_denied'], 'string'],
            [['gos_number'], 'string', 'max' => 12],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_category' => 'Id Category',
            'gos_number' => 'Gos Number',
            'descriptions' => 'Descriptions',
            'descriptions_denied' => 'Descriptions Denied',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'id_category']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['success'] = ['status'];
        $scenarios['cancel'] = ['status', 'descriptions_denied'];
        return $scenarios;
    }

    public function cancel()
    {
        $this->status = 2;
        if($this->save()){
            return true;
        }
        return false;
    }

    public function success(){
        $this->status = 1;
        $this->save();
    }


}
