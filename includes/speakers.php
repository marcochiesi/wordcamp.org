<?php

namespace WordCamp\Blocks\Speakers;
defined( 'WPINC' ) || die();

use WordCamp_Post_Types_Plugin;

/**
 * Register block types and enqueue scripts.
 *
 * @return void
 */
function init() {
	register_block_type( 'wordcamp/speakers', array(
		'render_callback' => __NAMESPACE__ . '\render',
	) );
}

add_action( 'init', __NAMESPACE__ . '\init' );

/**
 *
 *
 * @param array $data
 *
 * @return array
 */
function add_script_data( array $data ) {
	$data['speakers'] = [
		'options' => array(
			'sort' => get_sort_options(),
		),
	];

	return $data;
}

add_filter( 'wordcamp_blocks_script_data', __NAMESPACE__ . '\add_script_data' );

/**
 * Run the shortcode callback after normalizing attribute values.
 *
 * @return string
 */
function render( $attributes ) {
	/** @var WordCamp_Post_Types_Plugin $wcpt_plugin */
	global $wcpt_plugin;

	if ( true === $attributes['show_all_posts'] ) {
		$attributes['posts_per_page'] = -1;
	}

	$sort = explode( '_', $attributes['sort'] );

	if ( 2 === count( $sort ) ) {
		$attributes['orderby'] = $sort[0];
		$attributes['order']   = $sort[1];
	}

	if ( true === $attributes['speaker_link'] ) {
		$attributes['speaker_link'] = 'permalink';
	}

	if ( is_array( $attributes['track'] ) ) {
		$attributes['track'] = implode( ',', $attributes['track'] );
	}

	if ( is_array( $attributes['groups'] ) ) {
		$attributes['groups'] = implode( ',', $attributes['groups'] );
	}

	return $wcpt_plugin->shortcode_speakers( $attributes, '' );
}

/**
 * Get the labels and values of the Sort By options.
 *
 * @return array
 */
function get_sort_options() {
	return array(
		array(
			'label' => _x( 'A → Z', 'sort option', 'wordcamporg' ),
			'value' => 'title_asc',
		),
		array(
			'label' => _x( 'Z → A', 'sort option', 'wordcamporg' ),
			'value' => 'title_desc',
		),
		array(
			'label' => _x( 'Newest to Oldest', 'sort option', 'wordcamporg' ),
			'value' => 'date_desc',
		),
		array(
			'label' => _x( 'Oldest to Newest', 'sort option', 'wordcamporg' ),
			'value' => 'date_asc',
		),
	);
}
