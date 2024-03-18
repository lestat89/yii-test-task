<?php

namespace app\forms;


use app\models\Users;
use Yii;
use yii\base\Model;

/**
 * Login form
 *
 * @property-read Users|null $user
 */
class LoginForm extends Model
{
    /** @var string $email */
    public $email;
    /** @var string $password */
    public $password;

    /** @var bool $rememberMe */
    public $rememberMe = true;
    /** @var bool $_user */
    private Users|bool|null $_user = false;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'email'    => 'Email',
            'rememberMe'  => 'Remember me',
            'password'    => 'Password',
        ];
    }

    /**
     * @return void
     */
    public function validatePassword(): void
    {
        if ($this->hasErrors())
            return;

        $userModel = $this->getUser();
        if (!$userModel || $userModel->id <= 0 || !$userModel->validatePassword($this->password))
            $this->addError('password', 'Invalid email or password.');
    }

    /**
     * @return bool
     */
    public function login(): bool
    {
        return $this->validate() && Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * @return Users|bool|null
     */
    public function getUser(): Users|bool|null
    {
        if ($this->_user === false)
            $this->_user = Users::findByEmail($this->email);

        return $this->_user;
    }
}
