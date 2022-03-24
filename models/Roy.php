<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "roy".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $birthday
 */
class Roy extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['username', 'password', 'name', 'birthday'], 'required'],
            [['username', 'password', 'name'], 'required'],
            [['birthday'], 'safe'],
            [['username'], 'string', 'max' => 30],
            [['password', 'name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'birthday' => 'Birthday',
        ];
    }

    public static function findIdentity($id)
    {
        
        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;

        $link = Yii::$app->db;
        $sql = "select * from roy where id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":id",$id);
        $sth->execute();
        $roy = $sth->queryAll();
        $num = $roy['0'];

        if($roy != null){
            $test = [
                'id' => $num['id'],
                'username' => $num['username'],
                'password' => $num['password'],
                // 'authKey' => 'test101key',
                // 'accessToken' => '101-token',
            ];
            return new static($test);
        }else{
            return null;
        }
         
        
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        

        $link = Yii::$app->db;
        $sql = "SELECT * FROM roy WHERE username = :username";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":username", $username);
        $sth->execute();
        $roy = $sth->queryAll();
        $num = $roy['0'];

        if ($roy != null) {
            
            $test = [
                'id' => $num['id'],
                'username' => $username,
                'password' => $num['password'],
            ];
            return new static($test);

        } else {

            return null;

        }
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    
        return $this->password === $password;
    }

    public function royPassword($username,$password)
    {

        $link = Yii::$app->db;
        $sql = "SELECT password FROM roy WHERE username = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":id", $username);
        $sth->execute();
        $roy = $sth->queryAll();
        $num = $roy['0'];

        if ($num['password'] == $password) {

            return true;

        } else {

            return false;

        }


        // return $password.",".$username;
    }
}
