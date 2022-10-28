<?php

namespace app\models\forms;

use app\models\Response;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;

class RespondForm extends Model
{
    public $price;
    public $comment;

    public function rules(): array
    {
        return [
            ['price', 'integer', 'min' => 1],
            ['comment', 'string'],
            ['comment', 'default', 'value' => null],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'price' => 'Стоимость',
            'comment' => 'Ваш комментарий',
        ];
    }

    /**
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function createRespond($id_task){
        $respond = new Response();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $respond->price = $this->price;
            $respond->comment = $this->comment;
            $respond->user_id = Yii::$app->user->id;
            $respond->task_id = $id_task;
            if (!$respond->save()){
                throw new ForbiddenHttpException('Ошибка сохранения');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('Loading error');
        }
    }

}