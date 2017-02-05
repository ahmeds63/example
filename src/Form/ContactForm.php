<?php
/**
 * @file
 * Contains \Drupal\example\Form\ContactForm.
 */

namespace Drupal\example\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;


/**
 * My Form.
 */
class ContactForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['markup'] = array(
      '#type' => 'markup',
      '#markup' => t($this->getDescription()),
      );
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#required' => TRUE,
    );
    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#required' => TRUE
    );
    $form['subject'] = array(
      '#type' => 'textfield',
      '#title' => t('Subject'),
      '#required' => TRUE
    );
    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => t('Message'),
      '#required' => TRUE,
      // '#attributes' => array('class' => array('ckeditor')),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send'),
    );
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validating email address
    if (!valid_email_address($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('The email is not valid.'));
    }
    // Validating subject length
    if (strlen($form_state->getValue('subject')) < 3) {
      $form_state->setErrorByName('subject', $this->t('The subject is too short.'));
    }
    // Validating message length
    if (strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('message', $this->t('The message is too short.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Storing form data to database.
    $data = array(
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'subject' => $form_state->getValue('subject'),
      'message' => $form_state->getValue('message'),
      );
    $table = 'custom_contact';
    $query = db_insert($table)->fields($data)->execute();
    if ($query) {
      $this->sendMail($data);
      drupal_set_message(t('Your message has been sent.'));
    }
    else{
      drupal_set_message(t('Internal Error! message sending failed.'), 'warning');
    }
  }

  private function getDescription(){
    $result = db_select('custom_contact_config', 'form_description')
        ->fields('form_description')
        ->condition('cid', 1)
        ->execute()
        ->fetchAssoc();
    return $result['form_description'];
  }

  private function sendMail($data){
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'example';
    $key = 'contact_submit';
    $to = 'ahmed.raza@square63.com';
    $params['message'] = t($data['message']);
    $params['subject'] = t($data['subject']);
    $params['name'] = t($data['name']);
    $params['email'] = t($data['email']);
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }
  }
}
