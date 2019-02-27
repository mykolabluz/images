<?php
return [
    'adminEmail' => 'admin@example.com',
    
    'maxFileSize' => 10485760, // 10 megabites
    'storagePath' => '@frontend/web/uploads/',
    'storageUri' => '/uploads/',  // https:/yii2.frontend.com/uploads/f1/d7/2342352hug5235gu.jpg
    
    'profilePicture' => [
        'maxWidth' => 800,
        'maxHeight' => 600,
    ],
    'postPicture' => [
        'maxWidth' => 1024,
        'maxHeight' => 768,
    ],
    
    'feedPostLimit' => 200,
];
