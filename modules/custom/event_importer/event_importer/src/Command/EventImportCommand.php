<?php

namespace Drupal\event_importer\Command;

use Drush\Commands\DrushCommands;
use Drupal\node\Entity\Node;
use GuzzleHttp\Client;

class EventImportCommand extends DrushCommands {
  protected $httpClient;

  public function __construct() {
    $this->httpClient = new Client();
  }

  /**
   * Import events from Eventbrite API.
   *
   * @command event_importer:import
   * @aliases ei
   * @usage event_importer:import
   */
  public function import() {
    $url = 'https://www.eventbriteapi.com/v3/organizations/2180249606643/events/?token=ETLXBBDKGHRTH5KV667T';
    $response = $this->httpClient->get($url);
    $data = json_decode($response->getBody()->getContents(), TRUE);

    foreach ($data['events'] as $event) {
      $existing_nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
        'title' => $event['name']['text'],
        'type' => 'event',
      ]);

      if (empty($existing_nodes)) {
        // Check if the venue key exists and handle it appropriately.
        $location = isset($event['venue']['name']) ? $event['venue']['name'] : 'Unknown Location';

        $node = Node::create([
          'type' => 'event',
          'title' => $event['name']['text'],
          'field_event_description' => $event['description']['text'],
          'field_event_start_date' => $event['start']['utc'],
          'field_event_end_date' => $event['end']['utc'],
          'field_event_location' => $location, //No Location in array
          'field_event_url' => ['uri' => $event['url'], 'title' => 'Event URL'],
        ]);
        $node->save();
        $this->output()->writeln("Event {$event['name']['text']} created.");
      } else {
        $this->output()->writeln("Event {$event['name']['text']} already exists.");
      }
    }
  }
}
