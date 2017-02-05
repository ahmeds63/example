<?php
/**
 * @file
 * Contains \Drupal\example\Plugin\Block\CheckoutBlock.
 */
namespace Drupal\example\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'checkout' block.
 *
 * @Block(
 *   id = "example_contact_block",
 *   admin_label = @Translation("Contact block"),
 *   category = @Translation("Example contact form as a block")
 * )
 */

class ContactBlock extends BlockBase {
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\example\Form\ContactForm');
  }
}
