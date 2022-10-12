<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $role
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $telegram
 * @property int $city_id
 * @property string $password
 * @property string|null $dt_add
 * @property string|null $vk
 * @property int|null $rating
 * @property int|null $availability
 * @property int|null $permission
 * @property string $avatar
 * @property string $description
 *
 *
 * @property Category[] $categories
 * @property City $city
 * @property ExecutorCategory[] $executorCategories
 * @property Response[] $responses
 * @property Task[] $tasks
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['role', 'name', 'email', 'city_id', 'password'], 'required'],
            [['role'], 'string'],
            [['city_id', 'rating', 'availability', 'permission'], 'integer'],
            [['dt_add'], 'safe'],
            [['name', 'email', 'telegram', 'vk'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['vk'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'city_id' => 'City ID',
            'password' => 'Password',
            'dt_add' => 'Dt Add',
            'vk' => 'Vk',
            'rating' => 'Rating',
            'availability' => 'Availability',
            'permission' => 'Permission',
            'description' => 'Description',
            'avatar' => 'Avatar'
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('executor_category', ['user_id' => 'id']);
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
     * Gets query for [[ExecutorCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorCategories()
    {
        return $this->hasMany(ExecutorCategory::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['user_id' => 'id']);
    }


    public function getActiveResponses() {
        return $this->getResponses()->where(['feedback' => !null])->exists();
    }

    public function getStatusLabel(){
        if ($this->availability === true) {
            $status = 'Готов взять задание в работу';
        } else {
            $status = 'Занят';
        }
        return $status;
    }

    public function getCountTaskByStatus(string $status) {
        return $this->getTasks()->where(['status' => $status])->count();
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }
}
