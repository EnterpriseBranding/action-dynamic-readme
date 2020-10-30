<?php

/**
 * Class Update_Template
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Update_Template {
	/**
	 * @var bool|string
	 */
	protected $content = false;

	/**
	 * @var bool|\Template_File_Handler
	 */
	protected $parent_template = false;

	/**
	 * Update_Template constructor.
	 *
	 * @param string                     $content
	 * @param Template_File_Handler|bool $parent_template
	 */
	public function __construct( $content, $parent_template = false ) {
		$this->content         = $content;
		$this->parent_template = $parent_template;
	}

	/**
	 * @return array
	 * @example
	 * <!-- mypath/filename.md -->
	 *
	 * <!-- mypath/filename.md -->
	 * @since {NEWVERSION}
	 */
	public function extract_included_templates() {
		$matches = array();

		/**
		 * @example
		 * <!-- sponsor.md -->
		 *
		 * <!-- sponsor.md -->
		 */ #preg_match_all( '/<!--(?P<templatefile>[\w\W]+)-->(?P<content>[\w\W]*)<!--\s?\1-->/m', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * @example
		 * <!-- START sponsor.md -->
		 * <!-- END sponsor.md -->
		 */ #preg_match_all( '/<!-- START (?P<templatefile>[\w\W]+) -->(?P<content>[\w\W]*)<!-- END \1 -->/m', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * <!-- START sponsor.md -->
		 * <!-- END sponsor.md -->
		 *
		 * OR
		 *
		 * <!-- start sponsor.md -->
		 * <!-- end sponsor.md -->
		 */ #preg_match_all( '/<!-- (?:START|start) (?P<templatefile>[\w\W]+) -->(?P<content>[\w\W]*)<!-- (?:END|end) \1 -->/m', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * <!-- START sponsor.md -->
		 * <!-- END sponsor.md -->
		 *
		 * OR
		 *
		 * <!-- start sponsor.md -->
		 * <!-- end sponsor.md -->
		 *
		 * also provides start & end keys
		 */ #preg_match_all( '/(?P<sec_start><!-- (?:START|start) (?P<file>[\w\W]+) -->)(?P<sec_content>[\w\W]*)(?P<sec_end><!-- (?:END|end) \2 -->)/mi', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * <!-- START sponsor.md -->
		 * <!-- END sponsor.md -->
		 *
		 * OR
		 *
		 * <!-- start sponsor.md -->
		 * <!-- end sponsor.md -->
		 *
		 * OR
		 * <!-- include sponsor.md -->
		 * also provides start & end keys
		 */
		preg_match_all( '/(?:(?P<inline><!-- (?:include|INCLUDE) (?P<file>[\w\W].+) -->))|((?P<sec_start><!-- (?:START|start) (?P<file>[\w\W]+) -->)(?P<sec_content>[\w\W]*)(?P<sec_end><!-- (?:END|end) \5 -->))/miJ', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * [0] -- > Full Content
		 * [1] -- > Comment Key
		 * [2] -- > Content Inside Comment
		 */
		return $matches;
	}

	public function update() {
		$this->include_templates();

		$function = 'dynamic_readme_' . TEMPLATE_ENGINE . '_engine';
		$default  = 'dynamic_readme_mustache_engine';

		if ( function_exists( $function ) ) {
			gh_log( 'Template Engine ' . TEMPLATE_ENGINE . ' Found' );
			$this->content = call_user_func( $function, $this->content );
		} elseif ( function_exists( $default ) ) {
			gh_log_error( 'Template Engine ' . TEMPLATE_ENGINE . ' Not Found' );
			gh_log_error( 'Using Default Template Engine {mustache}' );
			$this->content = call_user_func( $function, $this->content );
		} else {
			gh_log_error( 'No Template Engine Found' );
		}

		return $this->content;
	}


	/**
	 * Process Template Files.
	 *
	 * @since {NEWVERSION}
	 */
	protected function include_templates() {
		$templates       = $this->extract_included_templates();
		$parent_template = ( method_exists( $this->parent_template, 'get_src' ) ) ? $this->parent_template->get_src() : false;

		foreach ( $templates as $template ) {
			gh_log( print_r( array(
				$template,
				'parent_template : ' . $parent_template,
			), true ) );
			$template_file    = new Template_File_Handler( $template['file'], $parent_template );
			$template_content = new Update_Template( $template_file->get_contents(), $template_file );
			$template_content = $template_content->update();
			if ( false !== $template_content ) {
				if ( isset( $template['inline'] ) && ! empty( $template['inline'] ) ) {
					$str_find    = $template['inline'];
					$str_replace = $template_content;
					$content     = $this->content;
				} else {
					$regex       = '/(' . preg_quote( $template['sec_start'], '/' ) . ')([\w\W]*)(' . preg_quote( $template['sec_end'], '/' ) . ')/';
					$str_find    = 'PLACEHOLDER_REPLACE:' . rand( 1, 1000 );
					$str_replace = <<<CONTENT
{$template['sec_start']}
$template_content
{$template['sec_end']}
CONTENT;
					$content     = preg_replace( $regex, $str_find, $this->content );
				}

				if ( ! empty( $content ) ) {
					$this->content = str_replace( $str_find, $str_replace, $content );
				}
			}
		}
	}
}
