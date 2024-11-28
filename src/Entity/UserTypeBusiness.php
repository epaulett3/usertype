<?php 

namespace Drupal\usertype\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * @ContentEntityType(
 *   id = "usertype_business",
 *   label = @Translation("Business"),
 *   label_collection = @Translation("Businesses"),
 *   label_singular = @Translation("Business"),
 *   label_plural = @Translation("Businesses"),
 *   base_table = "usertype_business",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "owner" = "author",
 *     "published" = "published",
 *   },
 *   handlers = {
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\usertype\Form\UserTypeBusinessForm",
 *       "edit" = "Drupal\usertype\Form\UserTypeBusinessForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "default" = "Drupal\usertype\Form\UserTypeBusinessForm"
 *     },
 *     "list_builder" = "Drupal\usertype\Controller\UserTypeBusinessListBuilder",
 *     "local_action_provider" = {
 *       "collection" = "Drupal\entity\Menu\EntityCollectionLocalActionProvider",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "add-form" = "/admin/people/usertype-business/add",
 *     "edit-form" = "/admin/people/usertype-business/manage/{usertype_business}",
 *     "delete-form" = "/admin/people/usertype-business/manage/{usertype_business}/delete",
 *     "collection" = "/admin/people/usertype-business"
 *   },
 *   admin_permission = "administer usertype_business",
 *   
 * )
 */
class UserTypeBusiness extends ContentEntityBase implements EntityOwnerInterface, EntityPublishedInterface {
  use EntityOwnerTrait, EntityPublishedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    // get the field definations for 'id' and 'uuid' from the parent.
    $fields = parent::baseFieldDefinitions($entity_type);

    // Define field: title
    $fields['title'] = BaseFieldDefinition::create('string')
      -> setLabel(t('Business Name'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE);

    // Define field: description
    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('view', [
          'label' => 'hidden',
          'weight' => 10,
        ])
      ->setDisplayOptions('form', ['weight' => 20])
      // ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
      // ->setRevisionable(TRUE)
      // ->setTranslatable(TRUE);
    
    // Define field: website
    $fields['website'] = BaseFieldDefinition::create('link')
      ->setLabel(t('Website'))
      ->setDisplayOptions('form', ['weight' => 30])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'settings' => [
          'url_only' => true,
        ],
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', true);

    // Define field: email
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 40])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 30,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: phone number
    $fields['phone_number'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone Number'))
      ->setDefaultValue('')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 50])
      ->setDisplayOptions('view', [
        'type' => 'telephone_default',
        'weight' => 40,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: address line
    $fields['address_line'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Address Line'))
    ->setRequired(TRUE)
    ->setDisplayOptions('form', ['weight' => 60])
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'weight' => 50,
    ])
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);
    
    // Define field: city
    $fields['city'] = BaseFieldDefinition::create('string')
    ->setLabel(t('City'))
    ->setRequired(TRUE)
    ->setDisplayOptions('form', ['weight' => 70])
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'weight' => 60,
    ])
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);
    
    // Define field: state
    $fields['state'] = BaseFieldDefinition::create('string')
    ->setLabel(t('State or Region'))
    ->setRequired(TRUE)
    ->setDisplayOptions('form', ['weight' => 80])
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'weight' => 70,
    ])
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);
    
    // Define field: Postal Code
    $fields['postal_code'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Postal Code'))
    ->setRequired(TRUE)
    ->setDisplayOptions('form', ['weight' => 90])
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'weight' => 80,
    ])
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);
    
    // Define field: Country
    $fields['country'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Country'))
    ->setRequired(TRUE)
    ->setDisplayOptions('form', ['weight' => 90])
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'weight' => 80,
    ])
    ->setDisplayConfigurable('form', true)
    ->setDisplayConfigurable('view', true);



    // Get the field definitions for 'author' and 'published' from the trait.
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['published']->setDisplayOptions('form', [
      'settings' => [
        'display_label' => TRUE,
      ],
      'weight' => 100,
    ])
    ->setTranslatable(TRUE);


    return $fields;
  }

  /**
   * @return string
   */
  public function getTitle() {
      return $this->get('title')->value;
  }

  /**
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title) {
    return $this->set('title', $title);
  }

    /**
     * @return \Drupal\filter\Render\FilteredMarkup
     */
    public function getDescription() {
      return $this->get('description')->processed;
  }
  
  /**
   * @param string $description
   * @param string $format
   *
   * @return $this
   */
  public function setDescription($description, $format) {
      return $this->set('description', [
      'value' => $description,
      'format' => $format,
      ]);
  }

  /**
   * @return string
   */
  public function getWebsite() {
    return $this->get('website')->value;
}

/**
 * @param string $website
 *
 * @return $this
 */
public function setWebsite($website) {
  return $this->set('website', $website);
}

}