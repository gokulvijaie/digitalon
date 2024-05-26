<?php

namespace Drupal\digital_on\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DoAPI extends FormBase {
    const DO_API_CONFIG = 'do_api_config:values';
    public function getFormId(){
        return 'do_api_config';

    }
    public function buildForm(array $form, FormStateInterface $form_state){
        $values = \Drupal::state()->get(self::DO_API_CONFIG);
        $form = [];
        $form['api_base_url'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Base URL'),
            '#description' => $this->t('This is the API Base URL'),
            '#required' => TRUE,
            '#default_value' => $values['api_base_url']
        ];
        $form['api_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Key (v3 auth)'),
            '#description' => $this->t('This is the API key that will be used to access APIs.'),
            '#required' => TRUE,
            '#default_value' => $values['api_key']
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary'
        ];

        return $form;

    }
    public function submitForm(array &$form, FormStateInterface $form_state){

        $submitted_values = $form_state->cleanValues()->getValues();
        \Drupal::state()->set(self::DO_API_CONFIG, $submitted_values);
        $messenger = \Drupal::service('messenger');
        $messenger->addMessage($this->t('Your new Configuration has been saved.'));
        
    }
}