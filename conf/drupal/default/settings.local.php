<?php

/**
* Trusted host configuration.
*/
$settings['trusted_host_patterns'] = array(
  '^tvshow\.lxc$',
  'localhost'
);

$databases['default']['default'] = array (
  'database' => tvshow,
  'username' => tvshow,
  'password' => tvshow,
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['install_profile'] = standard;



