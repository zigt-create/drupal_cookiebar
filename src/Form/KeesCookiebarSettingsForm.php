<?php
namespace Drupal\kees_cookiebar\Form;

use \Drupal\Core\Form\ConfigFormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Cache\Cache;
use Drupal\node\Entity\Node;
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
        $node = Node::load($cookiepage_path);

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
            '#description' => 'When changing this, all users will have to re-set there cookie preference.',
        );

        $form['cookiepage_path'] = array(
            '#type' => 'entity_autocomplete',
            '#target_type' => 'node',
            '#title' => 'Cookies settings page',
            '#default_value' => $node,
            '#description' => '',
            '#required' => true,
          );

        return $form;
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
