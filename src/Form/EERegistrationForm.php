<?php

namespace Drupal\ee_user_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Html;

// We building here form for EE Data Information.

class EERegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */

  // We set here form Id.

  public function getFormId() {
    return 'ee_user_registration_form';
  }


  // We building here form fields EE Data Information.

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="ee_form_message"></div>',
    ];
    $form['full_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Full Name:'),
      '#required' => TRUE,
    );
    $form['phone_number'] = array (
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
      '#required' => TRUE,
    );
    $form['actions'] = [
       '#type' => 'button',
       '#value' => $this->t('Register'),
       '#ajax' => [
         'callback' => '::setFormMessage',
       ],
     ];

    return $form;
  }


  // We create this function for AJAX callback.

  public function setFormMessage(array $form, FormStateInterface $form_state) {
    
    
    $form_values = $form_state->getValues();

    // checke values and validate.
    $user_info = [];
    $user_info["full_name"] = !empty($form_values["full_name"]) ? Html::escape($form_values["full_name"]) : "";
    $user_info["phone_number"] = !empty($form_values["phone_number"]) ? Html::escape($form_values["phone_number"]) : "";

    // checke values
    if ($user_info["full_name"] && $user_info["phone_number"]) {

      // connection with Database.
      $conntion = Database::getConnection();
      $conntion->insert('user_custom_details')->fields($user_info)->execute();

      // Ajax message.
      $response = new AjaxResponse();
      $response->addCommand(
        new HtmlCommand(
          '.ee_form_message',
          '<div class="my_top_message"> ' . t('EE Registration Done!! Registered EE Values are: ') . ($user_info["full_name"]) . t(" phone number is: ") . ($user_info["phone_number"]) . '</div>')
      );
    }
    else {
      $response = new AjaxResponse();
      $response->addCommand(
        new HtmlCommand(
          '.ee_form_message',
          '<div class="my_top_message"> ' . t('Please fill right data') . '</div>')
      );
    }


    return $response;

  }

  // We need to make sure this function submitForm here because it's required.

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}