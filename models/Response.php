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
 * @property int|null $price
 * @property string|null $comment
 * @property int|null $score
 * @property string|null $feedback
 * @property boolean $status
 *
 * @property Task $task
 * @property User $user
 */
class Response extends ActiveRecord
{
    const STATUS_ACTIVE_RESPONSE = 1;

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
            [['user_id', 'task_id'], 'required'],
            [['user_id', 'task_id', 'price', 'score'], 'integer'],
            [['comment', 'feedback'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['status'], 'boolean'],
            ['status', 'default', 'value' => null],
            [['comment'], 'default', 'value' => null],
            [['feedback', 'score'], 'default', 'value' => null],
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
            'task_id' => 'TaskAction ID',
            'price' => 'Price',
            'comment' => 'Comment',
            'score' => 'Score',
            'feedback' => 'Feedback',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[TaskAction]].
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

    public static function isActionResponseVisiable($taskId, $taskStatus, $responseStatus) {
        if ((Yii::$app->user->id === $taskId) &&
            ($taskStatus === Task::STATUS_NEW ) && ($responseStatus === null)){
            return true;
        } else {
            return false;
        }
    }

    public static function getResponses($user_id): array
    {
        return Response::find()->where([
            'user_id' => $user_id,
            'feedback' => !null
        ])->all();
    }


}
