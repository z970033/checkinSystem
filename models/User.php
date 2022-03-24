<?php

namespace app\models;
use Yii;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {

        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;

        $link = Yii::$app->db;
        $sql = "SELECT * FROM employee WHERE id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":id", $id);
        $sth->execute();
        $roy = $sth->queryAll();
        $num = $roy['0'];

        if ($roy != null) {
            $test = [
                'id' => $num['id'],
                'username' => $num['username'],
                'password' => $num['password'],                
                // 'authKey' => 'test101key',
                // 'accessToken' => '101-token',
            ];
            return new static($test);
        } else {
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
        //原本的
    
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;

        // roy test
        $link = Yii::$app->db;
        $sql = "SELECT * FROM employee WHERE username = :username";
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

        // $link = Yii::$app->db;
        // $sql = "select password from roy where username = :id";
        // $sth = $link->createCommand($sql);
        // $sth->bindParam(":id",$password);
        // $sth->execute();
        // $roy = $sth->queryAll();
        // $num = $roy['0'];

        // if($roy == null){
        //     return null;
        // }else{
        //     return $num['password'];
        // }
        // 
        return $this->password === $password;
    }

    public function royPassword($username,$password)
    {

        $link = Yii::$app->db;
        $sql = "SELECT password FROM employee WHERE username = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":id", $username);
        $sth->execute();
        $roy = $sth->queryAll();
        $num = $roy['0'];
        // return $roy;

        return Yii::$app->getSecurity()->validatePassword($password, $num['password']);
        // return $num['password'] ;
        // return $password.",".$username;
    }
}

