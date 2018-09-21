<?php
namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class KeesCookiebarAddConfigForm extends ConfigFormBase
{
    private $isEdit = false;

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kees_cookiebar_add_config_form';
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
        // Form constructor.
        $form = parent::buildForm($form, $form_state);

        $config = $this->config('kees_cookiebar.settings');
        $cookies = $config->get('kees_cookiebar.settings_cookies');
        $key = \Drupal::request()->query->get('key');

        if (array_key_exists($key, $cookies)) {
            // edit existing
            $this->isEdit = true;
            $edit_cookie = $cookies[$key];
        }

        $form['cookie'] = array(
            '#type' => 'fieldset',
            '#title' => ($this->isEdit)? $this->t('Edit Cookie type') : $this->t('Add Cookie type') ,
            '#collapsible' => false,
            '#collapsed' => false,
        );
        $form['cookie']['cookie_name'] = array(
            '#placeholder' => 'Cookie name',
            '#type' => 'textfield',
            '#title' => t('Title'),
            '#default_value' => ($this->isEdit)? $edit_cookie['label'] : null,
        );
        $form['cookie']['cookie_key'] = array(
            '#placeholder' => 'cookie_key',
            '#type' => 'textfield',
            '#title' => t('Key'),
            '#disabled' => ($this->isEdit)? true : false,
            '#default_value' => ($this->isEdit)? $key : null,
        );
        if ($this->isEdit) {
            // Set value to be key so it cant be changed
            $form['cookie']['cookie_key']['#value'] = $key;
        }
        $form['cookie']['desc'] = array(
            '#type' => 'text_format',
            '#title' => $this->t('Description'),
            '#description' => $this->t('Description of this cookie-type'),
            '#format'=> 'basic_html',
            '#default_value' => ($this->isEdit)? $edit_cookie['desc'] : null,
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
        if (!preg_match('(^[a-z_]*$)', $form_state->getValue('cookie_key'))) {
            $form_state->setErrorByName('cookie_key', $this->t('Key should only have lowercase characters and lowecases! (and no spaces)'));
        }
        // If field is nog empty aka more than 1 character long
        if (empty($form_state->getValue('cookie_key')) || strlen($form_state->getValue('cookie_key')) <= 1) {
            $form_state->setErrorByName('cookie_key', $this->t('Key field should not be empty! (field must contain more then 1 character)'));
        }
        if (empty($form_state->getValue('cookie_name')) || strlen($form_state->getValue('cookie_name')) <= 1) {
            $form_state->setErrorByName('cookie_name', $this->t('Title field should not be empty! (field must contain more then 1 character)'));
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
        // Get fields
        $name = $form_state->getValue('cookie_name');
        $key = $form_state->getValue('cookie_key');
        $desc = $form_state->getValue('desc')['value'];
        // Add to existing config
        $config = $this->config('kees_cookiebar.settings');
        $cookies = $config->get('kees_cookiebar.settings_cookies');
        $cookies[$key] = array(
            'label' => $name,
            'desc' => $desc,
        );
        // Set new config with new array
        $config->set('kees_cookiebar.settings_cookies', $cookies);
        $config->save();
        $form_state->setRedirect('kees_cookiebar.config');
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
