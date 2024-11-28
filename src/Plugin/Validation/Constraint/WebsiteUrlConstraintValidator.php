<?php

namespace Drupal\usertype\Plugin\Validation\Constraint;

use Drupal\user\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WebsiteUrlConstraintValidator extends ConstraintValidator {

  public function validate($value, Constraint $constraint) {
    /* @var \Drupal\Core\Field\FieldItemListInterface $value */
    /* @var \Drupal\usertype\Plugin\Validation\Constraint\WebsiteUrlConstraint $constraint */

    foreach ($value as $item) {
      $website_url = $item->value;
      if (! empty($website_url)) {
        $new_website_url = strpos($website_url, 'http') !== 0 ? "http://$website_url" : $website_url;
        $host = parse_url($new_website_url, PHP_URL_HOST);
        $is_valid = filter_var($new_website_url, FILTER_VALIDATE_URL) && $host && strpos($host, '.') !== false;
        if (! $is_valid) {
          $this->context->buildViolation($constraint->message)
            ->setParameter('%url', $website_url)
            ->addViolation();
          return;
        }
      }
    }
  }
}
