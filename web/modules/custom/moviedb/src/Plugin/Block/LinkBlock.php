<?php

namespace Drupal\moviedb\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Link' Block.
 *
 * @Block(
 *   id = "link_block",
 *   admin_label = @Translation("Link Block"),
 *   category = @Translation("Link Block"),
 * )
 */
 class LinkBlock extends BlockBase {
  public function build() {
    return array(
      '#theme' => 'link_block',
      '#loginlink' => '/user/login',
      '#registerlink' => '/user/register',
    );
  }
}
