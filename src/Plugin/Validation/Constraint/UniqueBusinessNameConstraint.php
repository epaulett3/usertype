<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "UniqueBusinessName",
 *   label = @Translation("Unique business name"),
 * )
 */
class UniqueBusinessNameConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The business name: %name with type: %type is already exists.';

}
