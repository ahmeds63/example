<?php
/**
 * @file
 * Contains \Drupal\example\Form\DeleteForm.
 */

namespace Drupal\example\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * My Form.
 */
class DeleteForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_message_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $path = \Drupal::service('path.current')->getPath();
    $sid = explode("/", $path);
    $form['markup'] = array(
      '#type' => 'markup',
      '#markup' => t('Are you sure you want to delete this message? It cannot be recovered.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      );
    $form['sid'] = array(
      '#type' => 'hidden',
      '#value' => $sid[4],
      );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
 public function submitForm(array &$form, FormStateInterface $form_state) {
    // Storing form data to database.
    $sid = $form_state->getValue('sid');
    $table = 'custom_contact';
    $query = db_delete($table)->condition('sid', $sid)->execute();
    if ($query) {
      $form_state->setRedirect('example.content');
      drupal_set_message(t('Your message has been deleted.'));
    }
    else{
      drupal_set_message(t('Message does not exist.'), 'error');
    }
  }
}
