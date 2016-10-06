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
      '#title' => t('Custom Text'),
      '#default_value' => t($this->defaultValue()),
      '#required' => true
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
    // Storing form data to database.
    $data = $form_state->getValue('contact_text');
    $table = 'custom_contact_config';
    if ($this->exists()) {
      $query = db_update($table)
              ->fields(array('form_description' => $data))
              ->condition('cid', 1)
              ->execute();
      drupal_set_message(t('Description updated.'));
    }else{
      $query = db_insert($table)->fields($data)->execute();
      drupal_set_message(t('Description updated.'));
    }
  }

  private function defaultValue(){
    $result = db_select('custom_contact_config', 'form_description')
        ->fields('form_description')
        ->condition('cid', 1)
        ->execute()
        ->fetchAssoc();
    return $result['form_description'];
  }
  private function exists(){
    $result = db_select('custom_contact_config', 'form_description')
        ->fields('form_description')
        ->condition('cid', 1)
        ->execute()
        ->fetchAssoc();
    if (!empty($result['form_description'])) {
      return true;
    }else{
      return false;
    }
  }
}
