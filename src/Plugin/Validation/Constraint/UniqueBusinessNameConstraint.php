<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "UniqueBusinessName",
 *   label = @Translation("Unique attendees"),
 * )
 */
class UniqueBusinessNameConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The business %name is already exists.';

}
