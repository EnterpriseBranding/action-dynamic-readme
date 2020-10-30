<?php

/**
 * Class Template_File_Handler
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Template_File_Handler extends File_Handler {
	/**
	 * @var bool|string
	 */
	protected $parent_file = false;

	/**
	 * Template_File_Handler constructor.
	 *
	 * @param $src
	 */
	public function __construct( $src, $parent_file = false ) {
		$src = trim( $src, ' ' );
		parent::__construct( $src, false );
		$this->parent_file = ( ! empty( $parent_file ) ) ? dirname( $parent_file ) . '/' : false;
		$this->extract_src_details();
	}

	/**
	 * Retrives Contents.
	 *
	 * @return false|string
	 * @since {NEWVERSION}
	 */
	public function get_contents() {
		return ( ! empty( $this->src ) ) ? file_get_contents( $this->src ) : false;
	}

	/**
	 * Extracts Repository Info.
	 *
	 * @since {NEWVERSION}
	 */
	protected function extract_src_details() {
		$matches = extract_src_informaton( $this->src );
		$matches = ( isset( $matches[0] ) ) ? $matches[0] : array();

		if ( empty( $matches ) ) {
			#var_dump($this->parent_file . $this->src);
			if ( ! empty( $this->parent_file ) && file_exists( $this->parent_file . $this->src ) ) {
				$this->src = $this->parent_file . $this->src;
			} elseif ( file_exists( WORK_DIR . $this->src ) ) {
				$this->src = WORK_DIR . $this->src;
			}
		} elseif ( isset( $matches['branch'] ) ) {
			if ( empty( $matches['branch'] ) && ! empty( $this->parent_file ) && file_exists( $this->parent_file . $this->src ) ) {
				$this->src = $this->parent_file . $this->src;
			} elseif ( empty( $matches['branch'] ) && file_exists( WORK_DIR . $this->src ) ) {
				$this->src = WORK_DIR . $this->src;
			} else {
				$repo_instance = new Repository_Cloner( $matches['login'], $matches['repo'], $matches['branch'] );
				if ( file_exists( $repo_instance->get_path() . $matches['path'] ) ) {
					$this->src = $repo_instance->get_path() . $matches['path'];
				} else {
					gh_log_error( ' File Not Found ! class-template-file-handler.php#' . __LINE__ );
					$this->src = false;
				}
			}
		}
	}

	/**
	 * Returns SRC
	 *
	 * @return bool|string
	 */
	public function get_src() {
		return $this->src;
	}
}