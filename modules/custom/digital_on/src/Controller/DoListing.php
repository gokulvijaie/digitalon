<?php
namespace Drupal\digital_on\Controller;

use Drupal\Core\Controller\ControllerBase;

class DoListing extends ControllerBase {
    public function view() {
        $content = [];
        $content['name'] = t('My name is Gokul');
        return [
            '#theme' => 'do-listing',
            '#content' => $content
            
        ];
    }
}