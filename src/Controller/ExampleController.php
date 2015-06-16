<?php
/**
 * @file
 * Contains \Drupal\example\Controller\ExampleController.
 */

namespace Drupal\example\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

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
        'data' => array($key, $value->name, $value->subject, \Drupal::l('View Message', new Url('example.view', array('sid'=>$value->sid)))),
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

  public function view($sid) {
    if (isset($sid)) {
      $results = db_select('custom_contact', 'm')
        ->fields('m')
        ->condition('sid', $sid)
        ->execute();
      foreach ($results as $key => $value) {
        $output = '';
        $output .= '<p><strong>Name: </strong>' . t($value->name) . '</p>';
        $output .= '<p><strong>Email: </strong>' . t($value->email) . '</p>';
        $output .= '<p><strong>Subject: </strong>' . t($value->subject) . '</p>';
        $output .= '<p><strong>Message: </strong>' . t($value->message) . '</p>';
        $output .= '<p>'. \Drupal::l('Delete', new Url('example.delete', array('sid'=>$value->sid))) .'</p>';
        return $output;
      }
    }
  }

}
