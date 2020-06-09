<?php

namespace Drupal\moviedb\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\NodeInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello block"),
 *   category = @Translation("Hello World"),
 * )
 */
class HelloBlock extends BlockBase {
  public function actorMovie ($temp) {
    $node  = Node::load($temp);
    // Get Name of the actor whose id is passed.
    $title = $node->title->value;
    // Get nid of the current node.
    $nid = $node->nid->value;
    // Query for fetching out all movies in which actor has worked.
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type','movie')
        ->condition('field_actor_role.entity:paragraph.field_actor.target_id',$nid);
        $nids = $query->execute();
    // check if query returned something or not.
    if (empty($nids)){
      $data = array("#markup" => "No Results Found");
      }
    else{
      $movie_nodes = entity_load_multiple('node', $nids);
      $items = array();
      foreach ($movie_nodes as $node) {
        $ratings = $node->field_rating->getValue ();
        $rating = $ratings[0]['rating'];
        $rating = $rating/20;
        $rating = $rating."/"."5";
        $node_title = $node->title->value;
        $node_id = $node->nid->value;
        $node_des = $node->field_s->value;
        $node_image_fid = $node->field_poster->target_id;
        if ( !is_null($node_image_fid) ) {
          $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
          $node_poster = $image_entity->url();
        }
        else {
               $image_entity_url = "/sites/default/files/default_images/obama.jpg";
        }

        $target_id = array();
        $target_id = $node ->field_actor_role->getValue();
        $actors = array();
        $j = 0;
        $coactors = array ();
        foreach ($target_id as $value) {
          $paragraph = Paragraph::load($value['target_id']);
          $actor_id = $paragraph->field_actor->target_id;

          $actor = Node::load($actor_id);
          $actors[$j]['name'] = $actor->title->value;
          $actors[$j]['nid'] = $actor->nid->value;
          if ($actor_id != $nid ) {
            $coactors[$j]['name'] = $actor->title->value;
            $coactors[$j]['nid'] = $actor->nid->value;
          }
          $j++;
        }
        // kint ($coactors);

        $items[] = [
          'name' => $node_title,
          'nid' => $node_id,
          'des' => $node_des,
          'poster' => $node_poster,
          'actors' =>$actors,
          'ratings' =>$rating,
          'costars' => $coactors
        ];
      }
      $mTitle = 'List of '.$title.' movies';
      return array (
        'theme' => 'actormovie_list',
        'items' => $items,
        'title' => $mTitle,
      );
    }
  }

  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid  = $node -> id();
    $data = array();
    $data = $this->actorMovie($nid);
  return  array(
    '#theme' => $data['theme'],
    '#items' => $data['items'],
    '#title' => $data['title'],
    '#cache' => [
      'max-age' => 0,
      ]
  );

  }

}
