<?php
/**
 * @file
 * Contains \Drupal\example\Controller\ExampleController.
 */

namespace Drupal\example\Controller;
use Drupal\Core\Controller\ControllerBase;

class ExampleController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Here, you will all the submissions from custom contact module.'),
    );

    $header = array(
      'id' => t('ID'),
      'name' => t('Name'),
      'subject' => t('Subject'),
      'actions' => t('Actions'),
      );

    $rows = array();

    $results = db_query('SELECT * FROM {custom_contact}');
    foreach ($results as $key => $value) {
      $rows[] = array(
        'data' => array($value->sid, $value->name, $value->subject, "Delete | View"),
        );
    }

    $table = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => array(
        'id' => 'custom-contact-messages-table',
        ),
      );

    $final = array($build, $table);

    return $final;
  }

}
