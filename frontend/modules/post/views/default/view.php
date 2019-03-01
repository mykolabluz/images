<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $currentUser User */
/* @var $comments frontend\modules\post\models */

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Html::encode($post->user->username);
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- feed item -->
                    <article class="post col-sm-12 col-xs-12">                                            
                        <div class="post-meta">
                            <div class="post-title">
                                <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                                <div class="author-name">
                                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]); ?>">
                                        <?php if ($post->user): ?>
                                            <?php echo Html::encode($post->user->username); ?>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="post-type-image">
                            <a href="#">
                                <img src="<?php echo $post->getImage(); ?>" alt="">
                            </a>
                        </div>
                        <div class="post-description">
                            <p><?php echo Html::encode($post->description); ?></p>
                        </div>
                        <div class="post-bottom">
                            <div class="post-likes">
                                <i class="fa fa-lg fa-heart-o"></i>                                
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                &nbsp;&nbsp;&nbsp;

                                <a href="#" class="btn btn-default button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                    UnLike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                </a>
                                <a href="#" class="btn btn-default button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                </a>
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <div class="post-date">
                                <span><?php echo Html::encode($post->geViewDate()); ?></span>    
                            </div>
                            <div class="post-report">
                                <a href="#">Report post</a>    
                            </div>
                        </div>
                    </article>
                    <!-- feed item -->
                    
                    <!-- Comments widgets -->
                    <?php
                    echo \yii2mod\comments\widgets\Comment::widget([
                        'model' => $model,
                        'relatedTo' => 'User ' . \Yii::$app->user->identity->username . ' commented on the page ' . \yii\helpers\Url::current(),
                        'maxLevel' => 2,
                        'dataProviderConfig' => [
                            'pagination' => [
                                'pageSize' => 10
                            ],
                        ],
                        'listViewConfig' => [
                            'emptyText' => Yii::t('app', 'No comments found.'),
                        ],
                    ]);
                    ?>
                    <!-- Comments widgets -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::class,
]);
