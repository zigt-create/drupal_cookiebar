<?php
namespace Drupal\kees_cookiebar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class KeesCookiebarConfigForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'kees_cookiebar_config_form';
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

        // Default settings.
        $config = $this->config('kees_cookiebar.settings');

        $rows = array(
            array(
                'label' => 'Primaire cookies',
                'field' => array(
                    'title' => 'Functionle cookies',
                    'key' => 'primary_cookies',
                ),
            ),
            array(
                'label' => 'Cookie set 1',
                'field' => array(
                    'title' => 'Analytische cookies',
                    'key' => 'analytical_cookies',
                ),
            ),
            array(
                'label' => 'Cookie set 2',
                'field' => array(
                    'title' => 'Marketing cookies',
                    'key' => 'marketing_cookies',
                ),
            ),
            array(
                'label' => 'Cookie set 3',
                'field' => array(
                    'title' => '',
                    'key' => '',
                ),
            ),
            array(
                'label' => 'Cookie set 4',
                'field' => array(
                    'title' => '',
                    'key' => '',
                ),
            ),
        );

        foreach ($rows as $key => $row) {
            $form[$key] = array(
                '#type' => 'fieldset',
                '#title' => t($row['label']),
                '#collapsible' => false,
                '#collapsed' => false,
            );

            $form[$key]['title'] = array(
                '#type' => 'textfield',
                '#title' => t('Title'),
                '#value' => $row['field']['title'],
            );

            $form[$key]['key'] = array(
                '#type' => 'textfield',
                '#title' => t('Key'),
                '#value' => $row['field']['key'],
                '#disabled' => ($key < 1 ),
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
        // $config = $this->config('kees_cookiebar.settings');
        // $config->set('kees_cookiebar.label', $form_state->getValue('label'));
        // $config->set('kees_cookiebar.text', $form_state->getValue('text'));
        // $config->set('kees_cookiebar.accept_button_text', $form_state->getValue('accept_button_text'));
        // $config->set('kees_cookiebar.decline_button_text', $form_state->getValue('decline_button_text'));
        // $config->save();

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
