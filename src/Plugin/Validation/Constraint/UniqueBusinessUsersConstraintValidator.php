<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Drupal\user\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueBusinessUsersConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\usertype\Plugin\Validation\Constraint\UniqueBusinessUsersConstraint $constraint */
    $user_ids = [];
    foreach ($value as $delta => $item) {
      $user_id = $item->target_id;
      if (in_array($user_id, $user_ids, TRUE)) {
        $this->context->buildViolation($constraint->message)
          ->setParameter('%name', User::load($user_id)->getDisplayName())
          ->atPath((string) $delta)
          ->addViolation();
        return;
      }
      $user_ids[] = $user_id;
    }
  }

}
