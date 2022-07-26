<?php
/**
 * @package Refined REST
 */
/*
Plugin Name: Refined REST
Description: An extension to the Wordpress REST API v2. This plugin adds two fields to the "posts" endpoint: <code>attachments</code> and <code>content_sanitized</code>. <strong>attachments</strong> contains a list of included media/attachments in each post, <strong>content_sanitized</strong> contains a limited HTML-, as well as a plaintext representation of the current blog post.
Version: 0.0.1
Author: Sascha Zarhuber
Author URI: https://sascha.work
License: MIT
*/

/*
The MIT License (MIT)

Copyright © 2022 Sascha Zarhuber <sascha.zarhuber@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

define( 'REFINED_REST_ROOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'REFINED_REST_INCLUDES_DIR', REFINED_REST_ROOT_DIR . 'includes/' );

if ( !function_exists( 'add_action' ) ) {
  echo 'This is a Wordpress plugin, exiting now...';
  exit;
}

require_once( REFINED_REST_INCLUDES_DIR . 'api.php' );