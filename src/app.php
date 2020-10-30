<?php
require_once '/gh-toolkit/php.php';

define( 'APP_PATH', __DIR__ . '/' );
define( 'WORK_DIR', $GITHUB_WORKSPACE );
define( 'TEMPLATE_REPO_PATH', '/dynamic-readme-tmp/repos/' );
define( 'TEMPLATE_ENGINE', gh_input( 'TEMPLATE_ENGINE', '${{ }}' ) );
define( 'TEMPLATE_DELIMITER', gh_input( 'DELIMITER', '${{ }}' ) );

require_once APP_PATH . 'vendor/autoload.php';
require APP_PATH . 'vars.php';
require APP_PATH . 'engine/mustache.php';
require APP_PATH . 'class/class-file-handler.php';
require APP_PATH . 'class/class-repo-cloner.php';
require APP_PATH . 'class/class-template-file-handler.php';
require APP_PATH . 'class/class-update-template.php';

$src  = ( isset( $argv[1] ) ) ? $argv[1] : false;
$dest = ( isset( $argv[2] ) ) ? $argv[2] : false;

$instance = new File_Handler( $src, $dest );
$template = new Update_Template( $instance->get_contents() );
$instance->save( $template->update() );
