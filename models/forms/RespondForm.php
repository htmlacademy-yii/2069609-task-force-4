<?php

namespace app\models\forms;

use app\models\Response;
use Yii;
use yii\base\Model;

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

    public function createRespond($id_task){
        $respond = new Response();
        $respond->price = $this->price;
        $respond->comment = $this->comment;
        $respond->user_id = Yii::$app->user->id;
        $respond->task_id = $id_task;
        $respond->save();
    }
}