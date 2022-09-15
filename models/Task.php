<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property int|null $budget
 * @property int|null $city_id
 * @property float $latitude
 * @property float $longitude
 * @property string|null $date_of_publication
 * @property string $description
 * @property string|null $date_of_execution
 * @property int $category_id
 * @property string|null $files
 * @property string $details
 *
 * @property Category $category
 * @property City $city
 * @property Response[] $responses
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    //доступные статусы заданий
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_AT_WORK = 'work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'status', 'latitude', 'longitude', 'description', 'category_id', 'details'], 'required'],
            [['user_id', 'budget', 'city_id', 'category_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['date_of_publication', 'date_of_execution'], 'safe'],
            [['status'], 'string', 'max' => 64],
            [['description', 'files', 'details'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'budget' => 'Budget',
            'city_id' => 'City ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'date_of_publication' => 'Date Of Publication',
            'description' => 'Description',
            'date_of_execution' => 'Date Of Execution',
            'category_id' => 'Category ID',
            'files' => 'Files',
            'details' => 'Details'
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
