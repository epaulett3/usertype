<?php 

namespace Drupal\usertype\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\UserInterface;

/**
 * @ContentEntityType(
 *   id = "business",
 *   label = @Translation("Business"),
 *   label_collection = @Translation("Businesses"),
 *   label_singular = @Translation("Business"),
 *   label_plural = @Translation("Businesses"),
 *   base_table = "business",
 *   revision_table = "business_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_author",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 *   show_revision_ui = true,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "owner" = "author",
 *     "published" = "published",
 *     "revision" = "revision_id",
 *   },
 *   handlers = {
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *       "revision" = "Drupal\entity\Routing\RevisionRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\usertype\Form\BusinessForm",
 *       "edit" = "Drupal\usertype\Form\BusinessForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "default" = "Drupal\usertype\Form\BusinessForm"
 *     },
 *     "list_builder" = "Drupal\usertype\Controller\BusinessListBuilder",
 *     "local_action_provider" = {
 *       "collection" = "Drupal\entity\Menu\EntityCollectionLocalActionProvider",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "canonical" = "/business/{business}",
 *     "add-form" = "/admin/business/add",
 *     "edit-form" = "/admin/business/manage/{business}",
 *     "delete-form" = "/admin/business/manage/{business}/delete",
 *     "collection" = "/admin/business",
 *     "version-history" = "/admin/business/{business}/revisions",
 *     "revision" = "/admin/business/{business}/revisions/{business_revision}",
 *     "revision-revert-form" = "/admin/business/{business}/revisions/{business_revision}/revert",
 *   },
 *   admin_permission = "administer business",
 * )
 */
class Business extends ContentEntityBase implements EntityOwnerInterface, EntityPublishedInterface {

  use EntityOwnerTrait, EntityPublishedTrait, RevisionLogEntityTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    // get the field definations for 'id' and 'uuid' from the parent.
    $fields = parent::baseFieldDefinitions($entity_type);

    // Define field: title
    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Business Name'))
      ->setRequired(TRUE)
      ->addConstraint('UniqueBusinessName')
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayOptions('view', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE);

    // Define field: description
    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('view', [
          'label' => 'hidden',
          'weight' => 10,
        ])
      ->setDisplayOptions('form', ['weight' => 1])
      ->setDisplayOptions('view', ['weight' => 1])
      // ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
      // ->setRevisionable(TRUE)
      // ->setTranslatable(TRUE);
    
    // Define field: website url
    $fields['website_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Website URL'))
      ->addConstraint('WebsiteUrl')
      ->setDisplayOptions('form', ['weight' => 2])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    // Define field: email
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 3])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: phone number
    $fields['phone_number'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone Number'))
      ->setDefaultValue('')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 4])
      ->setDisplayOptions('view', [
        'type' => 'telephone_default',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: address line
    $fields['address_line'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address Line'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 5])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: city
    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 6])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: state
    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State or Region'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 7])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: Postal Code
    $fields['postal_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postal Code'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 8])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 8,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: Country
    $fields['country'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Country'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 9])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 9,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    // Define field: Users
    $fields['users'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Users'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->addConstraint('UniqueBusinessUsers')
      ->setDisplayOptions('view', ['weight' => 10])
      ->setDisplayOptions('form', ['weight' => 10])
      ->setDisplayConfigurable('view', TRUE);

    // Define field: Parent Business
    $fields['parent_business'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Parent Business'))
      ->setSetting('target_type', 'business')
      ->setDisplayOptions('view', ['weight' => 11])
      ->setDisplayOptions('form', ['weight' => 11])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Define field: Business Type
    $fields['business_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Business Type'))
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'sole_proprietor' => t('Sole Proprietor'),
          'llc' => t('LLC'),
          'inc' => t('INC'),
          'llp' => t('LLP'),
        ],
      ])
      ->setDefaultValue('sole_proprietor')
      ->setDisplayOptions('view', ['weight' => 12])
      ->setDisplayOptions('form', ['weight' => 12])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Define field: Profit Type
    $fields['profit_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Profit Type'))
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'profit' => t('Profit'),
          'nonprofit' => t('Nonprofit'),
        ],
      ])
      ->setDefaultValue('profit')
      ->setDisplayOptions('view', ['weight' => 13])
      ->setDisplayOptions('form', ['weight' => 13])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Get the field definitions for 'author' and 'published' from the trait.
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    // $fields['published']->setDisplayOptions('form', [
    //   'settings' => [
    //     'display_label' => TRUE,
    //   ],
    //   'weight' => 100,
    // ])
    // ->setTranslatable(TRUE);

    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

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

  /**
   * @return \Drupal\user\UserInterface[]
   */
  public function getUsers() {
    return $this->get('users')->referencedEntities();
  }

  /**
   * @param \Drupal\user\UserInterface $user
   *
   * @return $this
   */
  public function addUser(UserInterface $user) {
    $field_items = $this->get('users');

    $exists = FALSE;
    foreach ($field_items as $field_item) {
      if ($field_item->target_id === $user->id()) {
        $exists = TRUE;
      }
    }

    if (!$exists) {
      $field_items->appendItem($user);
    }

    return $this;
  }

  /**
   * @param \Drupal\user\UserInterface $user
   *
   * @return $this
   */
  public function removeUser(UserInterface $user) {
    $field_items = $this->get('users');
    foreach ($field_items as $delta => $field_item) {
      if ($field_item->target_id == $user->id()) {
        $field_items->set($delta, NULL);
      }
    }
    $field_items->filterEmptyItems();
    return $this;
  }

  /**
   * @return string
   */
  public function getBusinessType() {
    return $this->get('business_type')->value;
  }

  /**
   * @param string $business_type
   *
   * @return $this
   */
  public function setBusinessType($business_type) {
    return $this->set('business_type', $business_type);
  }

  /**
   * @return string
   */
  public function getBusinessTypeDisplay() {
    $business_type = $this->get('business_type')->value;
    if ($business_type === 'sole_proprietor') {
      return ucwords(str_replace('_', ' ', $business_type));
    } else {
      return strtoupper($business_type);
    }
  }

}