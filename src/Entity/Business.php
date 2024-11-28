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
    
    // Define field: phone country code
    $fields['phone_country_code'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Phone Country Code'))
      ->setSettings([
        'allowed_values' => Business::getCountryCodes(),
      ])
      ->setDefaultValue('1')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 4])
      ->setDisplayOptions('view', [
        // 'type' => 'telephone_default',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);
    
    // Define field: phone number
    $fields['phone_number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Phone Number'))
      ->setDefaultValue('')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['weight' => 4])
      ->setDisplayOptions('view', [
        // 'type' => 'telephone_default',
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

    // Define field: address line 2
    $fields['address_line2'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address Line 2'))
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

  /**
   * an array of telephone country codes
   * 
   * @return [type]
   */
  public static function getCountryCodes(){
    $countryCodes = self::countryCodes();
    $return = [];
    foreach ($countryCodes as $countrycode) {
      $return[$countrycode["code"]] = t( $countrycode['country'] . " (+". $countrycode['code'] .")" );
    }
    return $return;
  }

  public static function countryCodes(){
    return [
      ["country" => "Afghanistan","code" => "93","iso" => "AF"],
      ["country" => "Albania","code" => "355","iso" => "AL"],
      ["country" => "Algeria","code" => "213","iso" => "DZ"],
      ["country" => "American Samoa","code" => "1-684","iso" => "AS"],
      ["country" => "Andorra","code" => "376","iso" => "AD"],
      ["country" => "Angola","code" => "244","iso" => "AO"],
      ["country" => "Anguilla","code" => "1-264","iso" => "AI"],
      ["country" => "Antarctica","code" => "672","iso" => "AQ"],
      ["country" => "Antigua and Barbuda","code" => "1-268","iso" => "AG"],
      ["country" => "Argentina","code" => "54","iso" => "AR"],
      ["country" => "Armenia","code" => "374","iso" => "AM"],
      ["country" => "Aruba","code" => "297","iso" => "AW"],
      ["country" => "Australia","code" => "61","iso" => "AU"],
      ["country" => "Austria","code" => "43","iso" => "AT"],
      ["country" => "Azerbaijan","code" => "994","iso" => "AZ"],
      ["country" => "Bahamas","code" => "1-242","iso" => "BS"],
      ["country" => "Bahrain","code" => "973","iso" => "BH"],
      ["country" => "Bangladesh","code" => "880","iso" => "BD"],
      ["country" => "Barbados","code" => "1-246","iso" => "BB"],
      ["country" => "Belarus","code" => "375","iso" => "BY"],
      ["country" => "Belgium","code" => "32","iso" => "BE"],
      ["country" => "Belize","code" => "501","iso" => "BZ"],
      ["country" => "Benin","code" => "229","iso" => "BJ"],
      ["country" => "Bermuda","code" => "1-441","iso" => "BM"],
      ["country" => "Bhutan","code" => "975","iso" => "BT"],
      ["country" => "Bolivia","code" => "591","iso" => "BO"],
      ["country" => "Bosnia and Herzegovina","code" => "387","iso" => "BA"],
      ["country" => "Botswana","code" => "267","iso" => "BW"],
      ["country" => "Brazil","code" => "55","iso" => "BR"],
      ["country" => "British Indian Ocean Territory","code" => "246","iso" => "IO"],
      ["country" => "British Virgin Islands","code" => "1-284","iso" => "VG"],
      ["country" => "Brunei","code" => "673","iso" => "BN"],
      ["country" => "Bulgaria","code" => "359","iso" => "BG"],
      ["country" => "Burkina Faso","code" => "226","iso" => "BF"],
      ["country" => "Burundi","code" => "257","iso" => "BI"],
      ["country" => "Cambodia","code" => "855","iso" => "KH"],
      ["country" => "Cameroon","code" => "237","iso" => "CM"],
      ["country" => "Canada","code" => "1","iso" => "CA"],
      ["country" => "Cape Verde","code" => "238","iso" => "CV"],
      ["country" => "Cayman Islands","code" => "1-345","iso" => "KY"],
      ["country" => "Central African Republic","code" => "236","iso" => "CF"],
      ["country" => "Chad","code" => "235","iso" => "TD"],
      ["country" => "Chile","code" => "56","iso" => "CL"],
      ["country" => "China","code" => "86","iso" => "CN"],
      ["country" => "Christmas Island","code" => "61","iso" => "CX"],
      ["country" => "Cocos Islands","code" => "61","iso" => "CC"],
      ["country" => "Colombia","code" => "57","iso" => "CO"],
      ["country" => "Comoros","code" => "269","iso" => "KM"],
      ["country" => "Cook Islands","code" => "682","iso" => "CK"],
      ["country" => "Costa Rica","code" => "506","iso" => "CR"],
      ["country" => "Croatia","code" => "385","iso" => "HR"],
      ["country" => "Cuba","code" => "53","iso" => "CU"],
      ["country" => "Curacao","code" => "599","iso" => "CW"],
      ["country" => "Cyprus","code" => "357","iso" => "CY"],
      ["country" => "Czech Republic","code" => "420","iso" => "CZ"],
      ["country" => "Democratic Republic of the Congo","code" => "243","iso" => "CD"],
      ["country" => "Denmark","code" => "45","iso" => "DK"],
      ["country" => "Djibouti","code" => "253","iso" => "DJ"],
      ["country" => "Dominica","code" => "1-767","iso" => "DM"],
      ["country" => "Dominican Republic","code" => "1-809, 1-829, 1-849","iso" => "DO"],
      ["country" => "East Timor","code" => "670","iso" => "TL"],
      ["country" => "Ecuador","code" => "593","iso" => "EC"],
      ["country" => "Egypt","code" => "20","iso" => "EG"],
      ["country" => "El Salvador","code" => "503","iso" => "SV"],
      ["country" => "Equatorial Guinea","code" => "240","iso" => "GQ"],
      ["country" => "Eritrea","code" => "291","iso" => "ER"],
      ["country" => "Estonia","code" => "372","iso" => "EE"],
      ["country" => "Ethiopia","code" => "251","iso" => "ET"],
      ["country" => "Falkland Islands","code" => "500","iso" => "FK"],
      ["country" => "Faroe Islands","code" => "298","iso" => "FO"],
      ["country" => "Fiji","code" => "679","iso" => "FJ"],
      ["country" => "Finland","code" => "358","iso" => "FI"],
      ["country" => "France","code" => "33","iso" => "FR"],
      ["country" => "French Polynesia","code" => "689","iso" => "PF"],
      ["country" => "Gabon","code" => "241","iso" => "GA"],
      ["country" => "Gambia","code" => "220","iso" => "GM"],
      ["country" => "Georgia","code" => "995","iso" => "GE"],
      ["country" => "Germany","code" => "49","iso" => "DE"],
      ["country" => "Ghana","code" => "233","iso" => "GH"],
      ["country" => "Gibraltar","code" => "350","iso" => "GI"],
      ["country" => "Greece","code" => "30","iso" => "GR"],
      ["country" => "Greenland","code" => "299","iso" => "GL"],
      ["country" => "Grenada","code" => "1-473","iso" => "GD"],
      ["country" => "Guam","code" => "1-671","iso" => "GU"],
      ["country" => "Guatemala","code" => "502","iso" => "GT"],
      ["country" => "Guernsey","code" => "44-1481","iso" => "GG"],
      ["country" => "Guinea","code" => "224","iso" => "GN"],
      ["country" => "Guinea-Bissau","code" => "245","iso" => "GW"],
      ["country" => "Guyana","code" => "592","iso" => "GY"],
      ["country" => "Haiti","code" => "509","iso" => "HT"],
      ["country" => "Honduras","code" => "504","iso" => "HN"],
      ["country" => "Hong Kong","code" => "852","iso" => "HK"],
      ["country" => "Hungary","code" => "36","iso" => "HU"],
      ["country" => "Iceland","code" => "354","iso" => "IS"],
      ["country" => "India","code" => "91","iso" => "IN"],
      ["country" => "Indonesia","code" => "62","iso" => "ID"],
      ["country" => "Iran","code" => "98","iso" => "IR"],
      ["country" => "Iraq","code" => "964","iso" => "IQ"],
      ["country" => "Ireland","code" => "353","iso" => "IE"],
      ["country" => "Isle of Man","code" => "44-1624","iso" => "IM"],
      ["country" => "Israel","code" => "972","iso" => "IL"],
      ["country" => "Italy","code" => "39","iso" => "IT"],
      ["country" => "Ivory Coast","code" => "225","iso" => "CI"],
      ["country" => "Jamaica","code" => "1-876","iso" => "JM"],
      ["country" => "Japan","code" => "81","iso" => "JP"],
      ["country" => "Jersey","code" => "44-1534","iso" => "JE"],
      ["country" => "Jordan","code" => "962","iso" => "JO"],
      ["country" => "Kazakhstan","code" => "7","iso" => "KZ"],
      ["country" => "Kenya","code" => "254","iso" => "KE"],
      ["country" => "Kiribati","code" => "686","iso" => "KI"],
      ["country" => "Kosovo","code" => "383","iso" => "XK"],
      ["country" => "Kuwait","code" => "965","iso" => "KW"],
      ["country" => "Kyrgyzstan","code" => "996","iso" => "KG"],
      ["country" => "Laos","code" => "856","iso" => "LA"],
      ["country" => "Latvia","code" => "371","iso" => "LV"],
      ["country" => "Lebanon","code" => "961","iso" => "LB"],
      ["country" => "Lesotho","code" => "266","iso" => "LS"],
      ["country" => "Liberia","code" => "231","iso" => "LR"],
      ["country" => "Libya","code" => "218","iso" => "LY"],
      ["country" => "Liechtenstein","code" => "423","iso" => "LI"],
      ["country" => "Lithuania","code" => "370","iso" => "LT"],
      ["country" => "Luxembourg","code" => "352","iso" => "LU"],
      ["country" => "Macao","code" => "853","iso" => "MO"],
      ["country" => "Macedonia","code" => "389","iso" => "MK"],
      ["country" => "Madagascar","code" => "261","iso" => "MG"],
      ["country" => "Malawi","code" => "265","iso" => "MW"],
      ["country" => "Malaysia","code" => "60","iso" => "MY"],
      ["country" => "Maldives","code" => "960","iso" => "MV"],
      ["country" => "Mali","code" => "223","iso" => "ML"],
      ["country" => "Malta","code" => "356","iso" => "MT"],
      ["country" => "Marshall Islands","code" => "692","iso" => "MH"],
      ["country" => "Mauritania","code" => "222","iso" => "MR"],
      ["country" => "Mauritius","code" => "230","iso" => "MU"],
      ["country" => "Mayotte","code" => "262","iso" => "YT"],
      ["country" => "Mexico","code" => "52","iso" => "MX"],
      ["country" => "Micronesia","code" => "691","iso" => "FM"],
      ["country" => "Moldova","code" => "373","iso" => "MD"],
      ["country" => "Monaco","code" => "377","iso" => "MC"],
      ["country" => "Mongolia","code" => "976","iso" => "MN"],
      ["country" => "Montenegro","code" => "382","iso" => "ME"],
      ["country" => "Montserrat","code" => "1-664","iso" => "MS"],
      ["country" => "Morocco","code" => "212","iso" => "MA"],
      ["country" => "Mozambique","code" => "258","iso" => "MZ"],
      ["country" => "Myanmar","code" => "95","iso" => "MM"],
      ["country" => "Namibia","code" => "264","iso" => "NA"],
      ["country" => "Nauru","code" => "674","iso" => "NR"],
      ["country" => "Nepal","code" => "977","iso" => "NP"],
      ["country" => "Netherlands","code" => "31","iso" => "NL"],
      ["country" => "Netherlands Antilles","code" => "599","iso" => "AN"],
      ["country" => "New Caledonia","code" => "687","iso" => "NC"],
      ["country" => "New Zealand","code" => "64","iso" => "NZ"],
      ["country" => "Nicaragua","code" => "505","iso" => "NI"],
      ["country" => "Niger","code" => "227","iso" => "NE"],
      ["country" => "Nigeria","code" => "234","iso" => "NG"],
      ["country" => "Niue","code" => "683","iso" => "NU"],
      ["country" => "North Korea","code" => "850","iso" => "KP"],
      ["country" => "Northern Mariana Islands","code" => "1-670","iso" => "MP"],
      ["country" => "Norway","code" => "47","iso" => "NO"],
      ["country" => "Oman","code" => "968","iso" => "OM"],
      ["country" => "Pakistan","code" => "92","iso" => "PK"],
      ["country" => "Palau","code" => "680","iso" => "PW"],
      ["country" => "Palestine","code" => "970","iso" => "PS"],
      ["country" => "Panama","code" => "507","iso" => "PA"],
      ["country" => "Papua New Guinea","code" => "675","iso" => "PG"],
      ["country" => "Paraguay","code" => "595","iso" => "PY"],
      ["country" => "Peru","code" => "51","iso" => "PE"],
      ["country" => "Philippines","code" => "63","iso" => "PH"],
      ["country" => "Pitcairn","code" => "64","iso" => "PN"],
      ["country" => "Poland","code" => "48","iso" => "PL"],
      ["country" => "Portugal","code" => "351","iso" => "PT"],
      ["country" => "Puerto Rico","code" => "1-787, 1-939","iso" => "PR"],
      ["country" => "Qatar","code" => "974","iso" => "QA"],
      ["country" => "Republic of the Congo","code" => "242","iso" => "CG"],
      ["country" => "Reunion","code" => "262","iso" => "RE"],
      ["country" => "Romania","code" => "40","iso" => "RO"],
      ["country" => "Russia","code" => "7","iso" => "RU"],
      ["country" => "Rwanda","code" => "250","iso" => "RW"],
      ["country" => "Saint Barthelemy","code" => "590","iso" => "BL"],
      ["country" => "Saint Helena","code" => "290","iso" => "SH"],
      ["country" => "Saint Kitts and Nevis","code" => "1-869","iso" => "KN"],
      ["country" => "Saint Lucia","code" => "1-758","iso" => "LC"],
      ["country" => "Saint Martin","code" => "590","iso" => "MF"],
      ["country" => "Saint Pierre and Miquelon","code" => "508","iso" => "PM"],
      ["country" => "Saint Vincent and the Grenadines","code" => "1-784","iso" => "VC"],
      ["country" => "Samoa","code" => "685","iso" => "WS"],
      ["country" => "San Marino","code" => "378","iso" => "SM"],
      ["country" => "Sao Tome and Principe","code" => "239","iso" => "ST"],
      ["country" => "Saudi Arabia","code" => "966","iso" => "SA"],
      ["country" => "Senegal","code" => "221","iso" => "SN"],
      ["country" => "Serbia","code" => "381","iso" => "RS"],
      ["country" => "Seychelles","code" => "248","iso" => "SC"],
      ["country" => "Sierra Leone","code" => "232","iso" => "SL"],
      ["country" => "Singapore","code" => "65","iso" => "SG"],
      ["country" => "Sint Maarten","code" => "1-721","iso" => "SX"],
      ["country" => "Slovakia","code" => "421","iso" => "SK"],
      ["country" => "Slovenia","code" => "386","iso" => "SI"],
      ["country" => "Solomon Islands","code" => "677","iso" => "SB"],
      ["country" => "Somalia","code" => "252","iso" => "SO"],
      ["country" => "South Africa","code" => "27","iso" => "ZA"],
      ["country" => "South Korea","code" => "82","iso" => "KR"],
      ["country" => "South Sudan","code" => "211","iso" => "SS"],
      ["country" => "Spain","code" => "34","iso" => "ES"],
      ["country" => "Sri Lanka","code" => "94","iso" => "LK"],
      ["country" => "Sudan","code" => "249","iso" => "SD"],
      ["country" => "Suriname","code" => "597","iso" => "SR"],
      ["country" => "Svalbard and Jan Mayen","code" => "47","iso" => "SJ"],
      ["country" => "Swaziland","code" => "268","iso" => "SZ"],
      ["country" => "Sweden","code" => "46","iso" => "SE"],
      ["country" => "Switzerland","code" => "41","iso" => "CH"],
      ["country" => "Syria","code" => "963","iso" => "SY"],
      ["country" => "Taiwan","code" => "886","iso" => "TW"],
      ["country" => "Tajikistan","code" => "992","iso" => "TJ"],
      ["country" => "Tanzania","code" => "255","iso" => "TZ"],
      ["country" => "Thailand","code" => "66","iso" => "TH"],
      ["country" => "Togo","code" => "228","iso" => "TG"],
      ["country" => "Tokelau","code" => "690","iso" => "TK"],
      ["country" => "Tonga","code" => "676","iso" => "TO"],
      ["country" => "Trinidad and Tobago","code" => "1-868","iso" => "TT"],
      ["country" => "Tunisia","code" => "216","iso" => "TN"],
      ["country" => "Turkey","code" => "90","iso" => "TR"],
      ["country" => "Turkmenistan","code" => "993","iso" => "TM"],
      ["country" => "Turks and Caicos Islands","code" => "1-649","iso" => "TC"],
      ["country" => "Tuvalu","code" => "688","iso" => "TV"],
      ["country" => "U.S. Virgin Islands","code" => "1-340","iso" => "VI"],
      ["country" => "Uganda","code" => "256","iso" => "UG"],
      ["country" => "Ukraine","code" => "380","iso" => "UA"],
      ["country" => "United Arab Emirates","code" => "971","iso" => "AE"],
      ["country" => "United Kingdom","code" => "44","iso" => "GB"],
      ["country" => "United States","code" => "1","iso" => "US"],
      ["country" => "Uruguay","code" => "598","iso" => "UY"],
      ["country" => "Uzbekistan","code" => "998","iso" => "UZ"],
      ["country" => "Vanuatu","code" => "678","iso" => "VU"],
      ["country" => "Vatican","code" => "379","iso" => "VA"],
      ["country" => "Venezuela","code" => "58","iso" => "VE"],
      ["country" => "Vietnam","code" => "84","iso" => "VN"],
      ["country" => "Wallis and Futuna","code" => "681","iso" => "WF"],
      ["country" => "Western Sahara","code" => "212","iso" => "EH"],
      ["country" => "Yemen","code" => "967","iso" => "YE"],
      ["country" => "Zambia","code" => "260","iso" => "ZM"],
      ["country" => "Zimbabwe","code" => "263","iso" => "ZW"],
      ];
  }

}