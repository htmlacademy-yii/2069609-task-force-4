<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
 * @property string $details
 *
 * @property Category $category
 * @property City $city
 * @property Response[] $responses
 * @property User $user
 * @property File[] $files
 */
class Task extends ActiveRecord
{
    //доступные статусы заданий
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_AT_WORK = 'work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    CONST TASK_STATUS_LABELS = [
    self::STATUS_NEW => 'Новое',
    self::STATUS_CANCELLED => 'Отменено',
    self::STATUS_AT_WORK => 'В работе',
    self::STATUS_DONE => 'Выполнено',
    self::STATUS_FAILED => 'Провалено'
];

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
            [['user_id', 'status', 'description', 'category_id', 'details'], 'required'],
            [['user_id', 'budget', 'city_id', 'category_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['date_of_publication', 'date_of_execution'], 'safe'],
            [['status'], 'string', 'max' => 64],
            [['description', 'details'], 'string', 'max' => 255],
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
            'details' => 'Details'
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

}
