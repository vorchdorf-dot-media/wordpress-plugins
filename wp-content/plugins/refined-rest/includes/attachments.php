<?php

class RefinedRESTAttachments {
  private int $id;
  private $attachments;

  private $re = '/<!--\swp:image\s(?<meta>{.+?})?.+?\/>(?:<figcaption>(?<caption>.+?)<\/figcaption>)?.+?-->/ms';

  private function map_attachment() {
    $attachment_id = $this->attachments->id();
    $attached_file = get_post( $attachment_id );
    $metadata = wp_get_attachment_metadata( $attachment_id );
    $caption = wp_strip_all_tags( $this->attachments->field('caption') );

    return array(
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
    $metadata = wp_get_attachment_metadata( $meta->id );

    return array(
      'caption' => $attachment['caption'],
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

  public function get_attachments() {
    $attached = $this->get_inline_attachments();

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