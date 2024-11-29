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
 *   id = "usertype",
 *   label = @Translation("User Type"),
 *   label_collection = @Translation("User Type"),
 *   label_singular = @Translation("User Type"),
 *   label_plural = @Translation("User Types"),
 *   base_table = "usertype",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "owner" = "author",
 *   },
 *   handlers = {
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *     "form" = {
 *       "add" = "Drupal\usertype\Form\UserTypeForm",
 *       "edit" = "Drupal\usertype\Form\UserTypeForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "default" = "Drupal\usertype\Form\UserTypeForm"
 *     },
 *     "list_builder" = "Drupal\usertype\Controller\UserTypeListBuilder",
 *     "local_action_provider" = {
 *       "collection" = "Drupal\entity\Menu\EntityCollectionLocalActionProvider",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "collection" = "/admin/people/usertype",
 *     "add-form" = "/admin/people/usertype/add",
 *     "edit-form" = "/admin/people/usertype/manage/{usertype}",
 *     "delete-form" = "/admin/people/usertype/manage/{usertype}/delete",
 *   },
 *   admin_permission = "administer usertype",
 *   
 * )
 */
class UserType extends ContentEntityBase implements EntityOwnerInterface, EntityPublishedInterface {
  use EntityOwnerTrait, EntityPublishedTrait;

  /**
   * Define fields here
   * 
   * @param EntityTypeInterface $entity_type
   * 
   * @return [type]
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    // get the field definations for 'id' and 'uuid' from the parent.
    $fields = parent::baseFieldDefinitions($entity_type);

    // define field: title
    $fields['title'] = BaseFieldDefinition::create('string')
      -> setLabel(t('Title'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 0])
      ->setDisplayConfigurable('form', TRUE);

    // define field: description
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

    // Get the field definitions for 'author' and 'published' from the trait.
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    return $fields;
  }

  /**
   * Get the value of the title field
   * 
   * @return string
   */
  public function getTitle() {
      return $this->get('title')->value;
  }

  /**
   * set the value of the title field
   * 
   * @param string $title
   *
   * @return $this
   */
  public function setTitle($title) {
    return $this->set('title', $title);
  }

    /**
     * get the description
     * 
     * @return \Drupal\filter\Render\FilteredMarkup
     */
    public function getDescription() {
      return $this->get('description')->processed;
  }
  
  /**
   * Set the description
   * 
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

}