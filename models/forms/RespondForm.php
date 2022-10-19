<?php

namespace app\models\forms;

use app\models\Response;
use Yii;
use yii\base\InvalidConfigException;
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
     * @throws InvalidConfigException
     */
    public function createRespond($id_task){
        $respond = new Response();
        if (empty($this->price)){
            $respond->price = null;
        } else {
            $respond->price = $this->price;
        }
        if (empty($this->comment)){
            $respond->comment = null;
        } else {
            $respond->comment = $this->comment;
        }
        $respond->user_id = Yii::$app->user->id;
        $respond->task_id = $id_task;
        $respond->save();
    }
}