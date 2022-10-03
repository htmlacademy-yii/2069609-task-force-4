<?php

namespace app\models\forms;

use app\models\Category;
use Yii;
use yii\base\Model;
use app\models\User;
use app\models\City;

class RegistrationForm extends Model
{
    public $name;
    public $email;
    public $city;
    public $password;
    public $password_repeat;
    public bool $isExecutor = false;

    const ROLE_EXECUTOR = 'executor';
    const ROLE_CUSTOMER = 'customer';
    public function rules(): array
    {
        return [
            [['name', 'email', 'city', 'password', 'password_repeat', 'isExecutor'], 'required'],
            ['name', 'string'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с данным Email уже существует'],
            ['city', 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city' => 'id']],
            ['password', 'string', 'min' => 8, 'max' => 64],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['isExecutor', 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'isExecutor' => 'я собираюсь откликаться на заказы'
        ];
    }

    public function createUser(){
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->city_id = $this->city;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        if ($this->isExecutor === true){
            $user->role = RegistrationForm::ROLE_EXECUTOR;
        } else {
            $user->role = RegistrationForm::ROLE_CUSTOMER;
        }
        $user->save();
    }

}