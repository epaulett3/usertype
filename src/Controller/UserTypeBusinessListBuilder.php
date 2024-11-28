<?php

namespace Drupal\usertype\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class UserTypeBusinessListBuilder extends EntityListBuilder {

  public function buildHeader() {
    $header = [];
    $header['title'] = $this->t('Business Name');
    // $header['date'] = $this->t('Date');
    $header['published'] = $this->t('Published');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $usertype_business) {
    /** @var \Drupal\usertype\Entity\EventEntity $event */
    $row = [];
    $row['title'] = $usertype_business->getTitle();
    // $row['date'] = $usertype->getDate()->format('m/d/y h:i:s a');
    $row['published'] = $usertype_business->isPublished() ? $this->t('Yes') : $this->t('No');
    return $row + parent::buildRow($usertype_business);
  }

}