<?php
namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\locale\SourceString;

use Drupal\kees_cookiebar\Helper\ConfigHelper;

class KeesCookiebarForm extends ConfigFormBase
{
    protected $ConfigHelper;

    public function __construct()
    {
        $this->ConfigHelper = new ConfigHelper();
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kees_cookiebar_form';
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

        // get config value
        $cookiebar_type = $this->ConfigHelper->base_config->get('kees_cookiebar.cookiebar_type');
        
        // Language links
        $form = $this->ConfigHelper->addLanguageLinks($form);
        
        // Page title field.
        $form['label'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Label:'),
            '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.label'),
            '#description' => $this->t('Bold text at the beginning of the cookiebar.'),
        );

        // Text field.
        $form['text'] = array(
            '#type' => 'textarea',
            '#title' => $this->t('Description:'),
            '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.text'),
            '#description' => $this->t('Main text on the center of the cookiebar.'),
        );

        // Accept button text field
        $title = ($cookiebar_type == "1")? "Submit cookies button text:" : "Accept cookies button text:";
        $description = ($cookiebar_type == "1")? "Text to show on the button to submit cookie preference" : "Text to show on the button to accept the cookies";
        $form['accept_button_text'] = array(
            '#type' => 'textfield',
            '#title' => $this->t($title),
            '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.accept_button_text'),
            '#description' => $this->t($description),
        );

        // Decline button text field
        if ($cookiebar_type == "0") {
            $form['decline_button_text'] = array(
                '#type' => 'textfield',
                '#title' => $this->t('Decline cookies button text:'),
                '#default_value' => $this->ConfigHelper->translatable_config->get('kees_cookiebar.decline_button_text'),
                '#description' => $this->t('Text to show on the button to decline the cookies'),
            );
        }

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
        // Not (yet) implemented
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
        $this->ConfigHelper->translatable_config->set('kees_cookiebar.label', $form_state->getValue('label'));
        $this->ConfigHelper->translatable_config->set('kees_cookiebar.text', $form_state->getValue('text'));
        
        $this->ConfigHelper->translatable_config->set('kees_cookiebar.accept_button_text', $form_state->getValue('accept_button_text'));
        if ($this->ConfigHelper->base_config->get('kees_cookiebar.cookiebar_type') == "0") {
            $this->ConfigHelper->translatable_config->set('kees_cookiebar.decline_button_text', $form_state->getValue('decline_button_text'));
        }

        $this->ConfigHelper->translatable_config->save();

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
