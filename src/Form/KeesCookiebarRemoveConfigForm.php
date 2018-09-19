<?php
namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Url;

class KeesCookiebarRemoveConfigForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kees_cookiebar_remove_config_form';
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
        $key = \Drupal::request()->query->get('key');
        $title = \Drupal::request()->query->get('title');

        $form['key_text'] = array(
            '#type' => 'hidden',
            '#value' => $key,
        );

        $form['sure_text'] = array(
            '#markup' => '<p>' . $this->t('Are you sure you want to delete this cookie-type') . ': <b>' . $title . '</b> ?</p>'
        );

        $form['actions'] = array('#type' => 'actions');
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#attributes' => [
                "class" => ['button--primary'],
            ],
            '#value' => t('Yes, delete'),
            '#suffix' => '<a href="' . Url::fromRoute('kees_cookiebar.config')->toString() . '">No, cancel</a>',
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
        $config = $this->config('kees_cookiebar.settings');
        $cookies = $config->get('kees_cookiebar.settings_cookies');
        $key = $form_state->getValue('key_text');
        // If field is not empty
        if (empty($key)) {
            $form_state->setErrorByName('key_text', $this->t('No key found! <a href="' . Url::fromRoute('kees_cookiebar.config')->toString() . '">Go back</a>'));
        }

        // If key exists
        if (!array_key_exists($key, $cookies)) {
            $form_state->setErrorByName('key_text', $this->t('Key doesn\'t match with current configuration! <a href="' . Url::fromRoute('kees_cookiebar.config')->toString() . '">Go back</a>'));
        }

        // Primary cookies cannot be deleted
        if ("primary_cookies" == $key) {
            $form_state->setErrorByName('key_text', $this->t('Primary cookies cannot be deleted! <a href="' . Url::fromRoute('kees_cookiebar.config')->toString() . '">Go back</a>'));
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
        // Get variables
        $key = $form_state->getValue('key_text');
        $config = $this->config('kees_cookiebar.settings');
        $cookies = $config->get('kees_cookiebar.settings_cookies');

        // remove cookie from array
        unset($cookies[$key]);
        // save new array
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
