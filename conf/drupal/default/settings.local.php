<?php

/**
* Trusted host configuration.
*/
$settings['trusted_host_patterns'] = array(
  '^sgo\.lxc$',
  'localhost'
);

$databases['default']['default'] = array (
  'database' => sgo,
  'username' => sgo,
  'password' => sgo,
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['install_profile'] = standard;



