<?php
namespace Drupal\kees_cookiebar\Form;

use \Drupal\Core\Form\ConfigFormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Cache\Cache;
use \Drupal\Core\Url;

class KeesCookiebarSettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kees_cookiebar_settings_form';
    }

    /**
     * This method will create the settings form
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return array
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Form constructor
        $form = parent::buildForm($form, $form_state);
        
        // Get config
        $config = $this->config('kees_cookiebar.settings');
        $cookiebar_type = $config->get('kees_cookiebar.cookiebar_type');
        $cookiepage_path = $config->get('kees_cookiebar.cookiepage_path');

        // Build form
        $options = array(
            0 => '<b>Default:</b> yes/no cookiebar',
            1 => '<b>Advanced:</b> multiple options cookiebar block',
        );
        $form['cookiebar_type'] = array(
            '#type' => 'radios',
            '#title' => 'Cookiebar type:',
            '#options' => $options,
            '#default_value' => $cookiebar_type,
        );

        $form['cookiepage_path'] = array(
            '#type' => 'textfield',
            '#title' => 'Path to the cookies settings page',
            '#default_value' => $cookiepage_path,
            '#size' => 60,
            '#description' => t('On this path the cookiebar or cookieblock will always be visible'),
            '#maxlength' => 128,
            '#required' => true,
          );

        return $form;
    }

    /**
     * Form validation for settings form
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // if key is machine readable
        $field_name = 'cookiepage_path';
        if (substr($form_state->getValue($field_name), 0, 1) !== "/") {
            $form_state->setErrorByName($field_name, $this->t('Path must start with a \'/\''));
        }
        if (substr($form_state->getValue($field_name), -1) === "/") {
            $form_state->setErrorByName($field_name, $this->t('Path should not end with a \'/\''));
        }
        if (strlen($form_state->getValue($field_name)) < 3) {
            $form_state->setErrorByName($field_name, $this->t('Path should be atleast 3 charaters long'));
        }
    }

    /**
     * Save to form by submitting it.
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return parent::submitForm
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cookiebar_type = $form_state->getValue('cookiebar_type');
        $cookiepage_path = $form_state->getValue('cookiepage_path');

        $config = $this->config('kees_cookiebar.settings');
        $config->set('kees_cookiebar.cookiebar_type', $cookiebar_type);
        $config->set('kees_cookiebar.cookiepage_path', $cookiepage_path);
        $config->save();

        return parent::submitForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return [
            'kees_cookiebar.settings',
        ];
    }
}
