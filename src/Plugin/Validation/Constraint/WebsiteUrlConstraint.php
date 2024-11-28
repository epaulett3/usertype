<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "WebsiteUrl",
 *   label = @Translation("Valid website url"),
 * )
 */
class WebsiteUrlConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The website url: %url is invalid.';

}
