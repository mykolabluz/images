<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser Yii::$app->user->identity */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
/* @var $feedItems[] frontend\models\Feed */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = Html::encode($user->username);
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- profile -->
                    <article class="profile col-sm-12 col-xs-12">                                            
                        <div class="profile-title">
                            <img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
                            <div class="author-name"><?php echo Html::encode($user->username); ?></div>

                            <?php if ($currentUser): ?>
                                <?php if ($user->equals($currentUser)): ?>

                                    <?=
                                    FileUpload::widget([
                                        'model' => $modelPicture,
                                        'attribute' => 'picture',
                                        'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
                                        'options' => ['accept' => 'image/*'],
                                        'clientEvents' => [
                                            'fileuploaddone' => 'function(e, data) {
                                    if (data.result.success) {
                                        $("#profile-image-success").show();
                                        $("#profile-image-fall").hide();
                                        $("#profile-picture").attr("src", data.result.pictureUri);
                                    } else {
                                        $("#profile-image-fall").html(data.result.errors.picture).show();
                                        $("#profile-image-success"). hide();
                                    }
                                }',
                                        ],
                                    ]);
                                    ?>
                                    <!-- Button trigger modal -->
                                    <a href="#" class="btn btn-default">Edit profile</a>
                                    <a href="<?php echo Url::to(['/user/profile/delete-photo', 'id' => $user->getId()]); ?>" class="btn btn-danger">Delete photo</a>

                                <?php endif; ?>
                            <?php endif; ?>

                            <!--<a href="#" class="btn btn-default">Upload profile image</a>-->

                            <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
                            <div class="alert alert-danger display-none" id="profile-image-fall"></div>

                        </div>
                        <br>

                        <?php if ($currentUser && !$user->equals($currentUser)): ?>
                            <?php if (!$currentUser->isFollowing($user)): ?>
                                <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Subscribe</a>
                                <br>
                            <?php else: ?>
                                <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Unsubscribe</a>
                                <br>
                            <?php endif; ?>
                                

                            <!-- Mutual subscription -->

                            <?php if (!$currentUser->isFollowing($user) && $mutualSubscriptions = $currentUser->getMutualSubscriptionsTo($user)): ?>
                                <h5>Friends, who are also following <?php echo Html::encode($user->username); ?></h5>
                                <div class="row">
                                    <?php foreach ($mutualSubscriptions as $item): ?>
                                        <div class="col-md-12">
                                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                                                <?php echo Html::encode($item['username']); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <!-- Mutual subscription -->
                        <?php endif; ?>


                     <?php if ($user->about): ?>
                        <div class="profile-description">
                            <p><?php echo HtmlPurifier::process($user->about); ?></p>
                        </div>
                     <?php endif; ?>
                        <div class="profile-bottom">
                            <div class="profile-post-count">
                                <span><?php echo $user->getPostCount(); ?> posts</span>
                            </div>
                            <div class="profile-followers" data-toggle="modal" data-target="#myModal2">
                                <a href="#"><?php echo $user->countFollowers(); ?> followers</a>
                            </div>
                            <div class="profile-following" data-toggle="modal" data-target="#myModal1">
                                <a href="#"><?php echo $user->countSubscriptions(); ?> following</a>    
                            </div>
                        </div>

                    </article>
                   
                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <?php foreach ($user->getPosts() as $post): ?>
                            <div class="col-md-4 profile-post">
                                <a href="<?php echo Url::to(['/post/default/view', 'id' => $post->getId()]); ?>">
                                    <img src="<?php echo Yii::$app->storage->getFile($post->filename); ?>" class="author-image" />
                                </a>
                            </div>
                            <?php endforeach; ?>                                                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List post -->
<?php if ($user->equals($currentUser)): ?>
    <?php foreach ($feedItems as $feedItem): ?>
        <a href="<?php echo Url::to(['/post/default/view', 'id' => $feedItem->post_id]); ?>"><?php echo $feedItem->author_name; ?>
            <img src="<?php echo Yii::$app->storage->getFile($feedItem->post_filename); ?>" width="100" height="150" />
        </a>
        <?php echo '<br>'; ?>
    <?php endforeach; ?>
<?php endif; ?>

<!-- List post -->

<!-- Modal subscriptions -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $subscription): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                                <?php echo Html::encode($subscription['username']); ?>
                            </a>
                        </div>                
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal subscriptions -->

<!-- Modal followers -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $follower): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
                                <?php echo Html::encode($follower['username']); ?>
                            </a>
                        </div>                
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal followers -->