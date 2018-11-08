<?php

namespace Drupal\widget_openlayer\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\geolocation\GeolocationCore;


/**
 * Plugin implementation of the 'open_layer_widget' widget.
 *
 * @FieldWidget(
 *   id = "open_layer_widget",
 *   label = @Translation("Open layer widget"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class OpenLayerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 60,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['size'] = [
      '#type' => 'number',
      '#title' => t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    ];
    $elements['placeholder'] = [
      '#type' => 'textfield',
      '#title' => t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['#type'] = 'fieldset';
    $form['map_canvas_img'] = array(
      '#prefix' => '<div><img id="icon-img" class="icon-img-oculto" src="https://raw.githubusercontent.com/jonataswalker/map-utils/master/images/marker.png"/></div>',
    );
    $element['map_canvas'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'id' => 'map',
        'class' => ['geolocation-map-canvas'],
      ],
      '#attached' => [
        'library' => ['widget_openlayer/library-base-geoloc']
      ]
    ];
    $element['lat'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Latitude'),
      '#default_value' => (isset($items[$delta]->lat)) ? $items[$delta]->lat : NULL,
      '#empty_value' => '',
      '#maxlength' => 255,
      '#required' => $this->fieldDefinition->isRequired(),
    ];

    $lat_example = $element['lat']['#default_value'] ?: '51.47879';

    $element['lat']['#description'] = $this->t('Enter either in decimal %decimal or sexagesimal format %sexagesimal', [
      '%decimal' => $lat_example,
      '%sexagesimal' => GeolocationCore::decimalToSexagesimal($lat_example),
    ]);

    $element['lng'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Longitude'),
      '#empty_value' => '',
      '#default_value' => (isset($items[$delta]->lng)) ? $items[$delta]->lng : NULL,
      '#maxlength' => 255,
      '#required' => $this->fieldDefinition->isRequired(),
    ];

    $lng_example = $element['lng']['#default_value'] ?: '-0.010677';

    $element['lng']['#description'] = $this->t('Enter either in decimal %decimal or sexagesimal format %sexagesimal', [
      '%decimal' => $lng_example,
      '%sexagesimal' => GeolocationCore::decimalToSexagesimal($lng_example),
    ]);

    return $element;
  }

}
