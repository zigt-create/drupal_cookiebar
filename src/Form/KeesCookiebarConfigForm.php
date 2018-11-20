<?php
namespace Drupal\kees_cookiebar\Form;

use \Drupal\Core\Form\ConfigFormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Url;

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
        // Get the current user
        $user = \Drupal::currentUser();

        // Form constructor.
        $form = parent::buildForm($form, $form_state);
        // Default settings.
        $config = $this->config('kees_cookiebar.settings');
        $cookies = $config->get('kees_cookiebar.settings_cookies');

        if ($user->hasPermission('administer cookiebar settings')) {
            $form['add_link'] = [
                '#attributes' => [
                    "class" => ['button', 'form-actions'],
                ],
                '#title' => $this->t('+ Add cookie-type'),
                '#type' => 'link',
                '#url' => Url::fromRoute('kees_cookiebar.add_config'),
            ];
        }

        $form['mytable'] = array(
            '#type' => 'table',
            '#header' => array(t('Label'), t('Key'), t('Operations')),
            '#empty' => t('No cookies found'),
        );

        foreach ($cookies as $key => $value) {
            // Some table columns containing raw markup.
            $form['mytable'][$key]['label'] = array(
                '#plain_text' => $value['label'],
            );
            $form['mytable'][$key]['key'] = array(
                '#plain_text' => $key,
            );
            $form['mytable'][$key]['operations'] = array(
                '#type' => 'operations',
                '#links' => array(),
            );
            $form['mytable'][$key]['operations']['#links']['edit'] = array(
                'title' => t('Edit'),
                'url' => Url::fromRoute('kees_cookiebar.add_config', array('key' => $key)),
            );
            if ("primary_cookies" != $key && $user->hasPermission('administer cookiebar settings')) {
                $form['mytable'][$key]['operations']['#links']['delete'] = array(
                    'title' => t('Delete'),
                    'url' => Url::fromRoute('kees_cookiebar.remove_config', array('key' => $key, 'title' => $value['label'])),
                );
            }
        }

        // Hide save button by giving actions->submit a empty array
        $form['actions'] = array('#type' => 'actions');
        $form['actions']['submit'] = array();

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
        // not needed / no submit button on this page
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
        // not needed / no submit button on this page
        // Do nothing
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
