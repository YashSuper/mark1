<?php
namespace Drupal\moviedb\Plugin\WebformHandler;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\Component\Utility\Html;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
/**
 * Webform validate handler.
 *
 * @WebformHandler(
 *   id = "moviedb_custom_validator",
 *   label = @Translation("Query length validator"),
 *   category = @Translation("Settings"),
 *   description = @Translation("Form alter to validate it."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */
class WebVal extends WebformHandlerBase {
  use StringTranslationTrait;
  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $values = $webform_submission->getData();

    // kint($values);
    $sid = $webform_submission->id();
    // kint($sid);
    // drupal_set_message($sid);
    $service = \Drupal::service('moviedb.result');
    $msg = $service->getresult($sid,"ft2_15");
    drupal_set_message($msg);
    // just set the confirmation to the reload of same page instead of redirect.
    // drupal_set_message(kint(anything));

  }

}
