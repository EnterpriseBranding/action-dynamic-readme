<?php

/**
 * Class Markdown_Handler
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 */
class Markdown_Handler {
	public static function get( $key, $value = false ) {
		if ( is_array( $key ) ) {
			$value = ( isset( $key['markdown_value'] ) ) ? $key['markdown_value'] : false;
			$key   = ( isset( $key['markdown'] ) ) ? $key['markdown'] : false;
		}

		$return = array();
		switch ( $key ) {
			case 'code':
				$return = array(
					'before' => ( ! empty( $value ) ) ? '```' . $value : '```',
					'after'  => '```',
				);
				break;
			case 'blockquote':
				$return = array(
					'before' => '<blockquote>',
					'after'  => '</blockquote>',
				);
				break;
			default:
				return array(
					'before' => '',
					'after'  => '',
				);
		}

		$return['before'] = $return['before'] . PHP_EOL;
		$return['after']  = $return['after'] . PHP_EOL;
		return $return;
	}
}