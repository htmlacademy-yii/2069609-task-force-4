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
    public bool $role = false;

    const ROLE_EXECUTOR = 'executor';
    const ROLE_CUSTOMER = 'customer';
    public function rules(): array
    {
        return [
            [['name', 'email', 'city', 'password', 'password_repeat', 'role'], 'safe'],
            [['name', 'email', 'city', 'password', 'password_repeat', 'role'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с данным Email уже существует'],
            ['city', 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city' => 'id']],
            ['password', 'string', 'min' => 8, 'max' => 64],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['role', 'boolean'],
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
            'role' => 'я собираюсь откликаться на заказы'
        ];
    }

    public function createUser(){
        $user = New User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->city_id = $this->city;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        if ($this->role === false){
            $user->role = RegistrationForm::ROLE_CUSTOMER;
        } else {
            $user->role = RegistrationForm::ROLE_EXECUTOR;
        }
        $user->save();
    }

}