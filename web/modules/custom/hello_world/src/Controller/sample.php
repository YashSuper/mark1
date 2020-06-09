<?php
namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

class sample extends ControllerBase {
  public function content () {
    return [
      '#type' => 'markup',
      '#markup' => $this -> t('This is the sample module created by Yash Bharadwaj'),
    ];
  }
  public function defaultConfiguration() {
    $default_config = \Drupal::config('hello_world.settings');
    return [
      'hello_block_name' => $default_config->get('hello.name'),
    ];
  }
}
?>
