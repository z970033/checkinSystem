<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Employee;
use app\models\TimeCard;
use yii\web\Session;
use yii\web\HttpException;

class RoyController extends \yii\web\Controller
{

  // public function behaviors()
  // {
  //     return [
  //         'access' => [
  //             'class' => AccessControl::className(),
  //             'rules' => [
  //                 [
  //                     'allow' => true,
  //                     'actions' => ['index'],
  //                     'roles' => ['@'],
  //                 ],

  //                 [
  //                     'allow' => true,
  //                     'actions' => ['test'],
  //                     'roles' => ['admin'],
  //                 ],
  //             ],
  //         ],
  //     ];
  // }

  public function actionIndex()
  {

    // $session = new Session;
    // $session->open();

    // $session['name'] ="zxc";
    // $session->setFlash('signupSuccess');

    // $session->close();
    Yii::$app->session->setFlash('signupSuccess');
    $this->redirect(['site/login']);

    // var_dump($checkUsername);
    $auth = Yii::$app->authManager; //获取我们的 authManager 组件

    //創建權限
    // $createPost = $auth->createPermission('lookArtDepartment');    
    // $createPost->description = '可以觀看美術部的權限';    
    // $auth->add($createPost);


    //創建角色
    // $obj = $auth->createRole('美術部管理員');//添加角色名称
    // $obj->description = '可以看到美術部員工的出勤和工作狀況';//添加角色描述
    // $auth -> add($obj);
    // $auth->addChild($obj, $createPost); 

    // $obj = $auth->createRole('工作日誌管理員');//添加角色名称
    // $obj->description = '可以從後台看到每個人的工作日誌';//添加角色描述
    // $auth -> add($obj);
    // $auth->addChild($obj, $createPost); 

    //添加權限或角色
    // $obj = $auth->getRole('美術部管理員');
    // $obj2 = $auth->getRole('工作日誌管理員');
    // $obj_per = $auth->getPermission('leaveEmployee');
    // $auth->assign($obj,"27");
    // $auth->addChild($obj, $obj2);
    
    // $auth->assign($obj,"zxc");//把我们$obj 角色分配给$uid 用户
    // $obj = $auth->createPermission('category/del');//创建分类删除功能(權限)
    // $auth->addChild($auth->getRole('普通管理員'),$obj);
    // var_dump(AccessControl::className());
    
     
    // return $this->render('index');
    
    // date_default_timezone_set("Asia/Taipei");
    // $late = date('H:i:s');
    // $time = "09:15:00";
    // if ($late > $time) {
    //     $cle = strtotime($late) - strtotime($time);
    //     $h = floor(($cle%(3600*24))/3600);  //%取余
    //     $m = floor(($cle%(3600*24))%3600/60);
    //     $s = floor(($cle%(3600*24))%60);


    //     echo $h."小時".$m."分鐘".$s."秒<br/>";
    //     self::test();
    // }
  }

  public function actionTest()
  {
    // Yii::$app->language = "zh_ch";
    echo Yii::$app->language;
    echo Yii::t('app', "test");
    // customLog("roytest");
    Yii::$app->end();    
    // var_dump(Yii::$app->params['adminEmail']);
    return $this->render('test',[
    ]);
  }
  public function createPermission($name)
  {    
      $auth = Yii::$app->authManager;    
      $createPost = $auth->createPermission($name);    
      $createPost->description = '创建了 ' . $name. ' 权限';    
      $auth->add($createPost);
  }

  public function createRole($name)
  {    
      $auth = Yii::$app->authManager;    
      $role = $auth->createRole($name);    
      $role->description = '创建了 ' . $name. ' 角色';    
      $auth->add($role);
  }


 	public function addChild($role, $permission)
 	{
 		  $auth = Yii::$app->authManager;    
 	    $parent = $auth->createRole($role);                //創建角色對象
 	    $child = $auth->createPermission($permission);     //創建權限對象
 	    $auth->addChild($parent, $child);                  //添加對應關係
 	}

 	public function assign($items)
 	{    
 	    $auth = Yii::$app->authManager;    
 	    $role = $auth->createRole($items);                //創建角色對象 
 	    $user_id = 1;                                     //獲取用戶id，此處假設用戶id = 1
 	    $auth->assign($role, $user_id);                   //添加對應關係
 	}

 	// public function beforeAction()
 	// {    
 	//     $action = Yii::$app->controller->action->id;
 	//     if(\Yii::$app->user->can('updatePost')){
 	//         // return true;
 	//         var_dump(123);
 	//     }else{
 	//         throw new \yii\web\UnauthorizedHttpException('對不起，您現在還沒獲此操作的權限');
 	//     }
 	// }
 	public function up()
  {
      $auth = Yii::$app->authManager;

      // 添加 "createPost" 权限
      $createPost = $auth->createPermission('createPost');
      $createPost->description = 'Create a post';
      $auth->add($createPost);

      // 添加 "updatePost" 权限
      $updatePost = $auth->createPermission('updatePost');
      $updatePost->description = 'Update post';
      $auth->add($updatePost);

      // 添加 "author" 角色并赋予 "createPost" 权限
      $author = $auth->createRole('author');
      $auth->add($author);
      $auth->addChild($author, $createPost);

      // 添加 "admin" 角色并赋予 "updatePost" 
	// 和 "author" 权限
      $admin = $auth->createRole('admin');
      $auth->add($admin);
      $auth->addChild($admin, $updatePost);
      $auth->addChild($admin, $author);

      // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id
      // 通常在你的 User 模型中实现这个函数。
      $auth->assign($author, 2);
      $auth->assign($admin, 1);
  }

  public function test()
  {
    echo "1234567";
  }
}






// 梅蘭竹菊
// 春夏秋冬
// 東西南北

// 10等+首儲
// 可玩聽別 一 二 廳
// 梅(72)、蘭(73)、春(68)、夏(69)、東(78)、西(79)

// 100等可玩
// 梅(72)、蘭(73)、竹(74)、春(68)、夏(69)、秋(70)、東(78)、西(79)、南(80)

// 200等可以完
// 全開
