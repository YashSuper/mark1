<?php
/**
 * @file
 *  Contains Drupal\moviedb\Plugin\Block\HomeBlock
 */
namespace Drupal\moviedb\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
/**
 * Provides a 'Home' Block.
 *
 * @Block(
 *   id = "home_block",
 *   admin_label = @Translation("Home page block"),
 *   category = @Translation("Display data from cofig form"),
 * )
 */
class HomeBlock extends BlockBase
{
  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $config = \Drupal::config('moviedb.settings');
    // kint($config);
    $image = $config->get('image');
    if (!is_null($image)) {
      $image_entity = \Drupal\file\Entity\File::load($image[0]);
      $image_entity_url = $image_entity->url();
    }
    else {
      $image_entity_url = "/sites/default/files/default_images/def_actor.jpeg";
    }
    $items=array();
    $items = [
      'title' => $config->get('title'),
      'url' =>   $image_entity_url,
      'desc' => $config->get('description'),
    ];
    // kint($items['title']);
    return [
      '#theme' => 'home_block',
      '#items' => $items,
      '#title' => "MOVIE OF THE MONTH",
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }
}
?>
