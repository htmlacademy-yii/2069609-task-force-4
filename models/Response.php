<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int $user_id
 * @property int $task_id
 * @property int $price
 * @property string $comment
 * @property int|null $score
 * @property string|null $feedback
 *
 * @property Task $task
 * @property User $user
 */
class Response extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'task_id', 'price', 'comment'], 'required'],
            [['user_id', 'task_id', 'price', 'score'], 'integer'],
            [['comment', 'feedback'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
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
            'task_id' => 'Task ID',
            'price' => 'Price',
            'comment' => 'Comment',
            'score' => 'Score',
            'feedback' => 'Feedback',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
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

    static function getResponses($user_id){
        return Response::find()->where([
            'user_id' => $user_id,
            'feedback' => !null
            ])->all();
    }
}
