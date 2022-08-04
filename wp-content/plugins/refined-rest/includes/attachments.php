<?php

class RefinedRESTAttachments {
  private int $id;
  private $attachments;

  private $re = '/<!--\swp:image\s(?<meta>{.+?})?.+?\/>(?:<figcaption>(?<caption>.+?)<\/figcaption>)?.+?-->/ms';

  private function map_attachment() {
    $attachment_id = $this->attachments->id();
    $attached_file = get_post( $attachment_id );
    $alt_text = get_post_meta($attachment_id , '_wp_attachment_image_alt', true);
    $metadata = wp_get_attachment_metadata( $attachment_id );
    $caption = wp_strip_all_tags( $this->attachments->field('caption') );

    return array(
      'alt' => $alt_text,
      'caption' => $caption,
      'height' => (int) $metadata['height'],
      'id' => (int) $attachment_id,
      'meta' => $metadata['image_meta'],
      'mimetype' => $attached_file->post_mime_type,
      'post_date' => $attached_file->post_date,
      'post_modified' => $attached_file->post_modified,
      'source' => $this->attachments->src( 'full' ),
      'title' => $this->attachments->field( 'title' ),
      'width' => (int) $metadata['width'],
    );
  }

  private function map_inline_attachment( $attachment ) {
    $meta = json_decode( $attachment['meta'] );

    $attached_file = get_post( $meta->id );
    $alt_text = get_post_meta($meta->id , '_wp_attachment_image_alt', true);
    $metadata = wp_get_attachment_metadata( $meta->id );

    return array(
      'alt' => $alt_text,
      'caption' => $attachment['caption'] ? $attachment['caption'] : $attached_file->post_excerpt,
      'height' => (int) $metadata['height'],
      'id' => (int) $meta->id,
      'meta' => $metadata['image_meta'],
      'mimetype' => $attached_file->post_mime_type,
      'post_date' => $attached_file->post_date,
      'post_modified' => $attached_file->post_modified,
      'source' => $attached_file->guid,
      'title' => $attached_file->post_title,
      'width' => (int) $metadata['width'],
    );
  }

  private function match_regex() {
    $post = get_post( $this->id );

    $amount = preg_match_all( $this->re, $post->post_content, $matches, PREG_SET_ORDER, 0 );

    if ( !$amount ) {
      return [];
    } 

    return $matches;
  }

  private function get_inline_attachments() {
    $matched = $this->match_regex();

    return array_map( array( $this, 'map_inline_attachment' ), $matched );
  }
  
  public function __construct( int $id ) {
    $this->id = $id;

    if ( class_exists('Attachments') ) {
      $this->attachments = new Attachments( 'attachments', $this->id );
    }
  }

  public function get_featured_media() {
    $post_thumbnail_id = get_post_thumbnail_id( $this->id );

    if ($post_thumbnail_id) {
      $attachment = array(
        "meta" => "{ \"id\": {$post_thumbnail_id} }"
      );

      return array( $this->map_inline_attachment( $attachment ) );
    }

    return [];
  }

  public function get_attachments() {
    $attached = array_merge( $this->get_featured_media(), $this->get_inline_attachments() );

    if ( !isset( $this->attachments ) || !$this->attachments->exist() ) {
      return $attached;
    }

    while ( $this->attachments->get() ) {
      $mapped = $this->map_attachment();

      array_push( $attached, $mapped );
    }

    return $attached;
  }
}

function get_attachments($id) {
  $att = new RefinedRESTAttachments( $id );
  return $att->get_attachments();
}