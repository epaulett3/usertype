<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "UniqueBusinessUsers",
 *   label = @Translation("Unique users"),
 * )
 */
class UniqueBusinessUsersConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The user %name is already part of this business.';

}
