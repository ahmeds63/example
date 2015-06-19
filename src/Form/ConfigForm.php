<?php
/**
 * @file
 * Contains \Drupal\example\Form\ConfigForm.
 */

namespace Drupal\example\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;


/**
 * My Form.
 */
class ConfigForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['markup'] = array(
      '#type' => 'markup',
      '#markup' => t('Enter the text to be shown above the form.'),
      );
    $form['contact_text'] = array(
      '#type' => 'textarea',
      '#title' => t('Custom Text')
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save Configuration')
    );
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validating message length
    if (strlen($form_state->getValue('contact_text')) < 5) {
      $form_state->setErrorByName('contact_text', $this->t('The text is too short.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    debug($form_state);
    // Storing form data to database.
    $data = array(
      'contact_text' => $form_state->getValue('contact_text'),
      );
    $table = 'contact_config';
    $query = db_insert($table)->fields($data)->execute();
    if ($query) {
      drupal_set_message(t('Configuration saved.'));
    }
    else{
      drupal_set_message(t('Internal Error!'), 'warning');
    }
  }
}
