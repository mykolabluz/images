<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;

class ProfileController extends Controller {

    public function actionView($nickname) {
        if (!Yii::$app->user->isGuest) {
            $currentUser = Yii::$app->user->identity;

            $modelPicture = new PictureForm();

            $limit = Yii::$app->params['feedPostLimit'];
            $feedItems = $currentUser->getFeed($limit);


            return $this->render('view', [
                        'user' => $this->findUser($nickname),
                        'currentUser' => $currentUser,
                        'modelPicture' => $modelPicture,
                        'feedItems' => $feedItems,
            ]);
        }


        $this->redirect(['/user/default/login']);
    }

    /**
     * Handle profile image upload via ajax request
     */
    public function actionUploadPicture() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate()) {

            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);  // 15/27/30379e706840f951d22de02458a4788eb55f.jpg

            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUser($nickname) {
        if ($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    public function actionSubscribe($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;



        $user = $this->findUserById($id);

        $currentUser->followUser($user);

//        echo '<pre>';
//        print_r($currentUser);
//        print_r($user);
//        echo '<pre>';die;

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    public function actionUnsubscribe($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser */
        $currentUser = Yii::$app->user->identity;
        $user = $this->findUser($id);

        $currentUser->unfollowUser($user);

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * @param integer $nickname
     * @return User
     * $throw NotFoundHttpException
     */
    private function findUserById($id) {
        if ($user = User::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    public function actionDeletePhoto($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var  $currentUser User */
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->deletePicture()) {
            Yii::$app->session->setFlash('success', 'Picture deleted');
        } else {
            Yii::$app->session->setFlash('danger', 'Error occured');
        }

        return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
    }

//    /**
//     * faker, create 1000 user
//     */
//    
//    public function actionGenerate()
//    {
//        $faker = Factory::create();
//        
//        for ($i = 0; $i < 1000; $i++) {
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time,
//            ]);
//            $user->save(false);
//        }
//    }
}
