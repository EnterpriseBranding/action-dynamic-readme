<?php
require_once '/gh-toolkit/php.php';

$delimeter   = gh_input( 'DELIMITER', '${{ }}' );
$delimeter   = ( ! empty( $delimeter ) ) ? $delimeter : '${{ }}';
$global_path = false;
if ( file_exists( APP_PATH . 'global-repo' ) ) {
	$global_path = file_get_contents( APP_PATH . 'global-repo' );
}

define( 'APP_PATH', __DIR__ . '/' );
define( 'WORK_DIR', $GITHUB_WORKSPACE );
define( 'TEMPLATE_REPO_PATH', '/dynamic-readme-tmp/repos/' );
define( 'TEMPLATE_ENGINE', gh_input( 'TEMPLATE_ENGINE', '${{ }}' ) );
define( 'TEMPLATE_DELIMITER', $delimeter );
define( 'GLOBAL_REPO_PATH', $global_path );

require_once APP_PATH . 'functions.php';
