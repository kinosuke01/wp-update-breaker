<?php
if ( ! function_exists( 'get_plugin_data' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

class WP_Update_Breaker_Updater {
    private $plugin_data;
    private $api_url;
    private $plugin_slug;
    private $version;

    public function __construct() {
        $plugin_file = WP_PLUGIN_DIR . '/wp-update-breaker/wp-update-breaker.php';
        $plugin_data = get_plugin_data($plugin_file);

        $this->plugin_slug = strtolower(str_replace(' ', '-', $plugin_data['Name']));
        $this->version = $plugin_data['Version'];
        $this->api_url = $plugin_data['UpdateURI'];

        add_filter('site_transient_update_plugins', [$this, 'check_for_plugin_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
    }

    public function check_for_plugin_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        // Fetch update information
        $response = wp_remote_get($this->api_url);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $api_response = json_decode(wp_remote_retrieve_body($response), true);

            // Check if there is a new version
            if (version_compare($this->version, $api_response['version'], '<')) {
                $plugin_data = [
                    'slug'        => $this->plugin_slug,
                    'new_version' => $api_response['version'],
                    'package'     => $api_response['download_link'], // download link
                ];

                // Add update to the transient
                $transient->response[$this->plugin_slug . '/' . $this->plugin_slug . '.php'] = (object) $plugin_data;
            }
        }

        return $transient;
    }

    // Provide detailed information about the plugin
    public function plugin_info($false, $action, $args) {
        if ($action !== 'plugin_information' || $args->slug !== $this->plugin_slug) {
            return false;
        }

        // Retrieve detailed plugin information from the API
        $response = wp_remote_get($this->api_url);
        if (is_wp_error($response)) {
            return false;
        }

        $api_response = json_decode(wp_remote_retrieve_body($response), true);
        if (!$api_response) {
            return false;
        }

        // Return detailed plugin information
        return (object) [
            'name'          => $api_response['name'],
            'slug'          => $api_response['slug'],
            'version'       => $api_response['version'],
            'author'        => $api_response['author'],
            'download_link' => $api_response['download_link'],
            'sections'      => $api_response['sections'],
        ];
    }
}

// Start update check when the plugin is loaded
new WP_Update_Breaker_Updater();
