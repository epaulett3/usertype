<?php

namespace Drupal\usertype\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class BusinessListBuilder extends EntityListBuilder {

  public function buildHeader() {
    $header = [];
    $header['title'] = $this->t('Business Name');
    // $header['date'] = $this->t('Date');
    // $header['published'] = $this->t('Published');
    $header['business_type'] = $this->t('Business Type');
    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $business) {
    /** @var \Drupal\usertype\Entity\EventEntity $business */
    $row = [];
    $row['title'] = $business->getTitle();
    // $row['date'] = $usertype->getDate()->format('m/d/y h:i:s a');
    // $row['published'] = $business->isPublished() ? $this->t('Yes') : $this->t('No');
    $row['business_type'] = $business->getBusinessTypeDisplay();
    return $row + parent::buildRow($business);
  }

}