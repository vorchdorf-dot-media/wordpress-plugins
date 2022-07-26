<?php

/* Code below only works on PHP 7.4+ */
$allowed_tags = ['p', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'strong', 'em', 'i', 'blockquote', 'cite', 'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tfoot', 'th', 'td', 'pre', 'code'];

add_action( 'rest_api_init', 'extend_api' );

function extend_api() {
  register_rest_field( 'post', 'attachments', array(
    'get_callback' => function ( $post_arr ) {

      require_once( REFINED_REST_INCLUDES_DIR . 'attachments.php' );

      $post_id = $post_arr['id'];
      return get_attachments($post_id);
    },
    'schema' => array(
      'type' => 'array',
      'items' => array(
        'type' => 'object',
        'properties' => array(
          'caption' => 'string',
          'height' => 'int',
          'id' => 'int',
          'meta' => array(
            'type' => 'object',
            'properties' => array(
              'aperture' => 'string',
              'credit' => 'string',
              'camera' => 'string',
              'caption' => 'string',
              'created_timestamp' => 'string',
              'copyright' => 'string',
              'focal_length' => 'string',
              'iso' => 'string',
              'shutter_speed' => 'string',
              'title' => 'string',
              'orientation' => 'string',
              'keywords' => array(
                'type' => 'array',
                'items' => array(
                  'type' => 'string',
                ),
              ),
            ),
          ),
          'mimetype' => 'string',
          'post_date' => 'string',
          'post_modified' => 'string',
          'source' => 'string',
          'title' => 'string',
          'width' => 'int',
        ),
      ),
    ),
  ) );

  register_rest_field( 'post', 'content_sanitized', array(
    'get_callback' => function ( $post_arr ) {
      global $allowed_tags;
      $re = '/^<!--\swp:(gallery|image).*?<!--\s\/wp:(gallery|image)\s-->$/ms';

      $post = get_post( $post_arr['id'] );
      $filtered_content = preg_replace( $re, '', $post->post_content );
      $html = apply_filters( 'the_content', $filtered_content );

      return array(
        'rendered' => preg_replace( '/\\n/m', '', strip_tags( $html, $allowed_tags ) ),
        'plaintext' => preg_replace( '/(?<=\\n\\n)\\n/m', '', wp_strip_all_tags( $html ) ),
      );
    },
    'schema' => array(
      'type' => 'object',
      'properties' => array(
        'rendered' => 'string',
        'plaintext' => 'string'
      ),
    ),
  ) );
}