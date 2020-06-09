<?php namespace Drupal\moviedb\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
/**
 * Defines a form that configures forms module settings.
 */
class TestForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'moviedb_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'moviedb.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('moviedb.settings');
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your title'),
      '#default_value' => $config->get('title'),
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your Description'),
      '#default_value' => $config->get('description'),
    ];
    $form['my_file'] = [
      '#type' => 'managed_file',
      '#title' => 'my file',
      '#name' => 'my_custom_file',
      '#description' => $this->t('my file description'),
      '#default_value' => $config->get('image'),
      '#upload_location' => 'public://profile-pictures',
      '#upload_validators' => array(
          'file_validate_extensions' => array('gif png jpg jpeg'),
          // 'file_validate_size' => array(25600000),
      ),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $desc = $form_state->getValue('description');
    if (!preg_match("/^[a-zA-Z ]+$/i",$title)) {
        $form_state->setErrorByName ('title', t('the %title is not valid.', array('%title' => $title)));
    }
    if (strlen($desc)<7) {
        $form_state->setErrorByName ('description', t('the length of description should be greater than or equal to 7.'));
    }
  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_file = $form_state->getValue('my_file', 0);
    if (isset($form_file[0]) && !empty($form_file[0])) {
      $file = File::load($form_file[0]);
      $file->setPermanent();
      $file->save();
      $image_entity = \Drupal\file\Entity\File::load($form_file[0]);
      $image_entity_url = $image_entity->url();
      // Add to file usage calculation.
      // \Drupal::service('file.usage')->add;
    }
    // else {
    //   $image_entity_url = "/sites/default/files/default_images/obama.png";
    // }
    // db_insert('form_list')
    //   ->fields(array(
    //     'title' => $form_state->getValue('title'),
    //     'description' => $form_state->getValue('description'),
    //     'image' => $image_entity_url,
    // ))
    // ->execute();

    $this->config('moviedb.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('description', $form_state->getValue('description'))
      ->set('image', $form_state->getValue('my_file'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
