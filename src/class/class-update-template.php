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
		 */
		preg_match_all( '/<!-- (?:START|start) (?P<templatefile>[\w\W]+) -->(?P<content>[\w\W]*)<!-- (?:END|end) \1 -->/m', $this->content, $matches, PREG_SET_ORDER, 0 );

		/**
		 * [0] -- > Full Content
		 * [1] -- > Comment Key
		 * [2] -- > Content Inside Comment
		 */
		return $matches;
	}

	public function update() {
		$this->include_templates();

		if ( 'mustache' === TEMPLATE_ENGINE ) {
			$this->run_mustache();
		}

		return $this->content;
	}

	/**
	 * Runs Generated Content With Mustache Template Engine
	 *
	 * @since {NEWVERSION}
	 */
	protected function run_mustache() {
		global $vars;
		$m             = new Mustache_Engine( array(
			'delimiters' => '${{ }}',
		) );
		$this->content = $m->render( $this->content, get_template_vars() );
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
			$template_file    = new Template_File_Handler( $template['templatefile'], $parent_template );
			$template_content = new Update_Template( $template_file->get_contents(), $template_file );
			$template_content = $template_content->update();
			if ( false !== $template_content ) {
				$_file   = preg_quote( $template['templatefile'], '/' );
				$regex   = "/(<!-- (?:START|start) $_file -->)([\w\W]*)(<!-- (?:END|end) $_file -->)/mi";
				$content = <<<CONTENT
\$1
$template_content
\$3
CONTENT;
				$content = preg_replace( $regex, $content, $this->content );
				if ( ! empty( $content ) ) {
					$this->content = $content;
				}
			}
		}
	}
}