<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Drupal\user\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueBusinessNameConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\usertype\Plugin\Validation\Constraint\UniqueBusinessNameConstraint $constraint */

    foreach ($value as $item) {
      $query = \Drupal::entityTypeManager()->getStorage('business')->getQuery();
      $query->condition('title', $item->value);
      if ($this->context->getRoot()->getEntity()->id()) {
        $query->condition('id', $this->context->getRoot()->getEntity()->id(), '!=');
      }
      $query->accessCheck(FALSE);
      $results = $query->execute();
      if (!empty($results)) {
        $this->context->buildViolation($constraint->message)
          ->setParameter('%name', $item->value)
          ->addViolation();
        return;
      }
    }
  }
}
