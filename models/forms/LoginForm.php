<?php
namespace app\models\forms;

use app\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !\Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
}