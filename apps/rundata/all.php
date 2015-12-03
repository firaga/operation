<?php
Ko_Web_Config::VLoadConfig(array (
  'global' => 
  array (
    'operation.imfw.cn:8080' => 'operation',
    'operation.main.imfw.cn' => 'operation',
  ),
  'app_default' => 
  array (
    'documentroot' => '/usr/share/php/apps/www/default',
    'rewriteconf' => '/usr/share/php/apps/conf/rewrite/default.txt',
    'rewritecache' => '/usr/share/php/apps/rundata/rewrite/default.php',
  ),
  'app_passport' => 
  array (
    'documentroot' => '/usr/share/php/apps/www/passport',
    'rewriteconf' => '/usr/share/php/apps/conf/rewrite/passport.txt',
    'rewritecache' => '/usr/share/php/apps/rundata/rewrite/passport.php',
  ),
  'app_xhprof' => 
  array (
    'documentroot' => '/usr/share/php/xhprof/',
  ),
  'app_operation' => 
  array (
    'documentroot' => '/srv/htdocs/apps/www/operation',
    'rewriteconf' => '/srv/htdocs/apps/conf/rewrite/operation.txt',
    'rewritecache' => '/srv/htdocs/apps/rundata/rewrite/operation.php',
  ),
));
