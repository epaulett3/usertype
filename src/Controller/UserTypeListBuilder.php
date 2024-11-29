<?php

namespace Drupal\usertype\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class UserTypeListBuilder extends EntityListBuilder {

  /**
   * Build the head of the table list
   * 
   * @return array
   */
  public function buildHeader() {
    $header = [];
    $header['id'] = $this->t('ID');
    $header['title'] = $this->t('Title');
    // $header['date'] = $this->t('Date');
    $header['description'] = $this->t('Description');
    // $header['published'] = $this->t('Published');
    return $header + parent::buildHeader();
  }

  /**
   * build the rows of the table list
   * 
   * @param EntityInterface $usertype
   * 
   * @return array
   */
  public function buildRow(EntityInterface $usertype) {
    /** @var \Drupal\usertype\Entity\EventEntity $event */
    $row = [];
    $row['id'] = $usertype->id();
    $row['title'] = $usertype->getTitle();
    $row['description'] = $usertype->getDescription();
    // $row['date'] = $usertype->getDate()->format('m/d/y h:i:s a');
    // $row['published'] = $usertype->isPublished() ? $this->t('Yes') : $this->t('No');
    return $row + parent::buildRow($usertype);
  }

}