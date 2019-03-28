<?php

namespace Drupal\user_activity_log\Controller;
use Drupal\Core\Controller\ControllerBase;

class UserActivityController extends ControllerBase {
	/**
   * Generates an user bulk upload page.
   */
  public function helloworld() {
    
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello vishal'),
    );
  }
}
