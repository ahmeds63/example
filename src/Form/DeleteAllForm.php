<?php
/**
 * @file
 * Contains \Drupal\example\Form\DeleteAllForm.
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
class DeleteAllForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_all_messages_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['markup'] = array(
      '#type' => 'markup',
      '#markup' => t('Are you sure you want to delete all messages? They cannot be recovered.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
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
    // Deleting all messages from database.
    $table = 'custom_contact';
    $query = db_delete($table)->execute();
    if ($query) {
      $form_state->setRedirect('example.content');
      drupal_set_message(t('Your messages has been deleted.'));
    }
    else{
      drupal_set_message(t('Failed to complete the requested process.'), 'error');
    }
  }
}
