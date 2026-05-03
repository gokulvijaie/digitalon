<?php

namespace Drupal\event_importer\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

class EventController {
  public function getEvents() {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'event')
      ->accessCheck(TRUE)  // Perform access check
      ->execute();
    $nodes = Node::loadMultiple($query);

    $events = [];
    foreach ($nodes as $node) {
      $events[] = [
        'title' => $node->getTitle(),
        'description' => $node->get('field_event_description')->value,
        'start_date' => $node->get('field_event_start_date')->value,
        'end_date' => $node->get('field_event_end_date')->value,
        'location' => $node->get('field_event_location')->value,
        'url' => $node->get('field_event_url')->uri,
      ];
    }
    shuffle($events);

    return new JsonResponse($events);
  }
}
