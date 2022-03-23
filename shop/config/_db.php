<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=lt',
    'username' => 'lt',
    'password' => '1XfY1u9*2W-VSxHE',
    'charset' => 'utf8',
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 3600,
    'attributes' => [
      PDO::ATTR_TIMEOUT => 10,
      PDO::ATTR_PERSISTENT => FALSE, //if true => mariaDB can drop long connection. None of the master DB servers is available.
    ],
];
