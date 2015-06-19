<?php
/**
 * @file
 * Contains \Drupal\example\Form\MyForm.
 */

namespace Drupal\example\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;


/**
 * My Form.
 */
class MyForm extends FormBase {
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
      '#markup' => t('This is a custom contact form.'),
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
      '#attributes' => array('class' => array('ckeditor')),
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
    if (strlen($form_state->getValue('subject')) < 5) {
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
      drupal_set_message(t('Your message has been sent.'));
    }
    else{
      drupal_set_message(t('Internal Error! message sending failed.'), 'warning');
    }
  }
}
