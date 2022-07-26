# Refined REST plugin for Wordpress

> A Wordpress plugin to extend the existing Wordpress REST API v2 with additional fields.

## Installation

Download this folder and store it in the `/wp-content/plugins` directory. Then activate it from within the admin panel.

## Fields

This is a Wordpress plugin which extends the `/posts` endpoint of the Wordpress REST API v2 with the following fields:

### `attachments`

The `attachments` field contains the images (incl. their meta data) included in the blog post.

#### Schema

```json
[
  {
    "caption": "string",
    "height": "int",
    "id": "int",
    "meta": {
      "aperture": "string",
      "credit": "string",
      "camera": "string",
      "caption": "string",
      "created_timestamp": "string",
      "copyright": "string",
      "focal_length": "string",
      "iso": "string",
      "shutter_speed": "string",
      "title": "string",
      "orientation": "string",
      "keywords": ["string"]
    },
    "mimetype": "string",
    "post_date": "string",
    "post_modified": "string",
    "source": "string",
    "title": "string",
    "width": "int"
  }
]
```

#### Attachments plugin

This plugin also supports the [Attachments](https://github.com/jchristopher/attachments) plugin by Jonathan Christopher.

### `content_sanitized`

The `content_sanitized` field extends the existing `content` field with a limited HTML- & plaintext version of the post content.

#### Schema

```json
{
  "rendered": "string",
  "plaintext": "string"
}
```
