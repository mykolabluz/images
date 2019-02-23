<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;

class PostForm extends Model {

    const MAX_DESCRIPTION_LENGHT = 1000;

    public $picture;
    public $description;
    private $user;

   
    public function rules() {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize()],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    public function __construct(User $user) {
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
    }

    /**
     * @return boolean
     */
    public function save() {

        if ($this->validate()) {

            $post = new Post();
            $post->description = $this->description;
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(FALSE);
        }
    }

    /**
     * Maxime size of the uploaded file
     * @return integer
     */
    private function getMaxFileSize() {
        return Yii::$app->params['maxFileSize'];
    }

    /**
     * Resize image if needed
     */
    public function resizePicture() {
        if ($this->picture->error) {
            /* В обьекте UploadedFile усте свойство error. Если в нем "1", значит
             * произошла ошибка и работать с изображением не нужно, прерываем
             * выполнение метода
             */
            return;
        }

        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);

        // 3-й аргумент - ограничение - специальные настройки при изменении размера
        $image->resize($width, $height, function($constraint) {

            // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц
            $constraint->aspectRatio();

            // Изображения, размером меньше заданых $width, $height не будут изменены:
            $constraint->upsize();
        })->save();
    }

}
