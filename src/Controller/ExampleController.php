<?php
/**
 * @file
 * Contains \Drupal\example\Controller\ExampleController.
 */

namespace Drupal\example\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Url;

class ExampleController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Here, you will see all the submissions from custom contact module.'),
    );

    $header = array(
      'id' => t('ID'),
      'name' => t('Email'),
      'subject' => t('Subject'),
      'view' => t('View'),
      'delete' => t('Delete'),
      );

    $rows = array();

    $results = db_query('SELECT * FROM {custom_contact}');
    foreach ($results as $key => $value) {
      $rows[] = array(
        'data' => array(
          $key,
          $value->email,
          $value->subject,
          \Drupal::l('View Message', new Url('example.view', array('sid'=>$value->sid))),
          \Drupal::l('Delete Message', new Url('example.delete_form', array('sid'=>$value->sid))),
          ),
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
        $date = strtotime($value->created);
        $output = '';
        $output .= '<p><strong>Name: </strong>' . t($value->name) . '</p>';
        $output .= '<p><strong>Email: </strong>' . t($value->email) . '</p>';
        $output .= '<p><strong>Subject: </strong>' . t($value->subject) . '</p>';
        $output .= '<p><strong>Sent On: </strong>' . date('d F, Y | h:i:s A', $date) . '</p>';
        $output .= '<p><strong>Message: </strong>' . t($value->message) . '</p>';
        $output .= '<p class="delete button">' . \Drupal::l('Delete', new Url('example.delete_form', array('sid'=>$value->sid))) . '</p>';
      return array(
        '#type' => 'markup',
        '#markup' => $output,
        '#attached' => array(
            'library' => array(
              'example/example.lib',
            ),
          ),
        );
      }
    }
    else{
      return new RedirectResponse(\Drupal::url('example.content'));
    }
  }

}
