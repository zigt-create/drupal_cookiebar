<?php
namespace Drupal\kees_cookiebar\Helper;

class ConfigHelper
{
    public $selected_langcode;

    public $languages;

    public $default_language;

    public $default_langcode;

    public $base_config;

    public $translatable_config;

    public function __construct()
    {
        // Set languages variables
        $this->languages = \Drupal::languageManager()->getLanguages();
        if (array_key_exists(\Drupal::request()->query->get('hl'), $this->languages)) {
            $this->selected_langcode = \Drupal::request()->query->get('hl');
        }
        $this->default_language = \Drupal::languageManager()->getDefaultLanguage();
        $this->default_langcode = $this->default_language->getId();
        unset($this->languages[$this->default_langcode]);

        // Set config
        $this->base_config = \Drupal::service('config.factory')->getEditable('kees_cookiebar.settings');
        $this->translatable_config = $this->base_config;
        if (!empty($this->selected_langcode)) {
            $this->translatable_config = \Drupal::languageManager()->getLanguageConfigOverride($this->selected_langcode, 'kees_cookiebar.settings');
        }
    }

    /**
     * Implement addLanguageLinks method
     *
     * @param array $form
     * @return array
     */
    public function addLanguageLinks(array $form) : array
    {
        if (!empty($this->languages)) {
            $form['lang_link_heading'] = [
                '#type' => 'html_tag',
                '#tag' => 'h3',
                '#value' => 'Translate',
            ];
            $url = $this::getUrlWithQueryParameters();

            $options = $url->getOptions();
            if (isset($options['query']['hl'])) {
                $active = $options['query']['hl'];
                unset($options['query']['hl']);
            } else {
                $active = 'default';
            }
            $url->setOptions($options);

            $form['lang_link_'. $this->default_langcode] = [
                '#type' => 'link',
                '#title' => $this->default_language->getName(),
                '#attributes' => array(
                    'class' => array(
                        'lang_link',
                        ($active == 'default')? 'active' : '',
                    ),
                ),
                '#url' => $url,
            ];
            foreach ($this->languages as $language) {
                $url = $this::getUrlWithQueryParameters();

                $options = $url->getOptions();
                $options['query']['hl'] = $language->getId();
                $url->setOptions($options);

                $form['lang_link_'. $language->getId()] = [
                    '#type' => 'link',
                    '#title' => $language->getName(),
                    '#attributes' => array(
                        'class' => array(
                            'lang_link',
                            ($active == $language->getId())? 'active' : '',
                        ),
                    ),
                        '#url' => $url,
                ];
            }
            $form['lang_link_spacing'] = [
                '#type' => 'html_tag',
                '#tag' => 'p',
                '#value' => '&nbsp;',
            ];
        }

        return $form;
    }

    public function getTranslatedCookies() : array
    {
        $cookies = $this->base_config->get('kees_cookiebar.settings_cookies');

        foreach ($cookies as $key => $cookie) {
            $translatedconf = $this->translatable_config->get('kees_cookiebar.settings_cookies');

            if (isset($translatedconf[$key])) {
                if (isset($translatedconf[$key]['label'])) {
                    $cookies[$key]['label'] = $translatedconf[$key]['label'];
                }

                if (isset($translatedconf[$key]['desc'])) {
                    $cookies[$key]['desc'] = $translatedconf[$key]['desc'];
                }
            }
        }

        return $cookies;
    }

    public static function getUrlWithQueryParameters()
    {
        return \Drupal\Core\Url::fromRoute('<current>', [], ['query' => \Drupal::request()->query->all()]);
    }
}
