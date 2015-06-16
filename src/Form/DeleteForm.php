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
class DeleteForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'message_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['markup'] = array(
      '#type' => 'markup',
      '#markup' => t('Are you sure you want to delete this message? It cannot be recovered once deleted!'),
      '#prefix' => '<div class="markup"><p>',
      '#suffix' => '</p></div>',
      );
    $form['submit'] = array(
      '#type' => 'submit',
      '#prefix' => '<div class="submit-wrapper">',
      '#suffix' => '</div>',
      '#value' => t('Delete'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
 public function submitForm(array &$form, FormStateInterface $form_state) {
    return "hello";
  }
}
