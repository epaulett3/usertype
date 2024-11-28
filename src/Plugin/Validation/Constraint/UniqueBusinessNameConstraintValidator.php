<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Drupal\user\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueBusinessNameConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\usertype\Plugin\Validation\Constraint\UniqueBusinessNameConstraint $constraint */

    $business_type = $this->context->getRoot()->getEntity()->get("business_type")->first()->getValue()['value'];
    foreach ($value as $item) {
      $query = \Drupal::entityTypeManager()->getStorage('business')->getQuery();
      $query->condition('title', $item->value);
      $query->condition('business_type', $business_type);
      if ($this->context->getRoot()->getEntity()->id()) {
        $query->condition('id', $this->context->getRoot()->getEntity()->id(), '!=');
      }
      $query->accessCheck(FALSE);
      $results = $query->execute();
      if (!empty($results)) {
        if ($business_type === 'sole_proprietor') {
          $business_type = ucwords(str_replace('_', ' ', $business_type));
        } else {
          $business_type = strtoupper($business_type);
        }
        $this->context->buildViolation($constraint->message)
          ->setParameter('%name', $item->value)
          ->setParameter('%type', $business_type)
          ->addViolation();
        return;
      }
    }
  }
}
