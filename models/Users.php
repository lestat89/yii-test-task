<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string $email
 * @property string $auth_key
 * @property string $password
 * @property int $created_at
 * @property int $updated_at
 * @property string $last_name
 * @property string $first_name
 * @property string|null $middle_name
 * @property string|null $phone
 * @property string $document
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'last_name', 'first_name', 'document'], 'required'],

            [['email', 'password'], 'string', 'max' => 64],
            [['last_name', 'first_name', 'middle_name', 'phone'], 'string', 'max' => 32],
            [['document'], 'string'],

            ['email', 'email'],
            ['email', 'unique'],
            ['email', 'match', 'pattern' => '/^[a-z0-9@\-_\.]+$/i', 'message' => 'Для поля "{attribute}" допустимые символы "@", ".", "-", "_"'],

            ['password', 'required'],
            [
                'password',
                'match',
                'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[`~!@#$%^&*()_\-+={}\[\]|:;"\'<>,\.?\/]).{8,64}$/',
                'message' => 'Пароль должен быть длинее 8 символов и иметь хотя бы одну букву верхнего регистра, 
                                     одну букву нижнего регистра, одну цифру и содержать спец. символы',
            ],
            ['password', 'comparePasswordWithLogin'],
        ];
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            $this->updated_at = time();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'password' => 'Password',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'phone' => 'Phone',
            'document' => 'Document',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): Users|IdentityInterface|null
    {
        return static::findOne((int)$id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): null
    {
        return null;
    }

    /**
     * @param string $email
     *
     * @return Users|null
     */
    public static function findByEmail(string $email): ?Users
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return void
     */
    public function comparePasswordWithLogin(): void
    {
        if (!empty($this->email) && str_contains($this->password, $this->email))
            $this->addError('password', 'Нельзя использовать пароли в которых есть email');
    }

    /**
     * @return void
     * @throws \yii\base\Exception
     */
    public function cryptPassword(): void
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);
    }
}
