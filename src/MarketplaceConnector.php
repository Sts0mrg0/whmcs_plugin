<?php


namespace MicroweberAddon;


class MarketplaceConnector
{

    private $manifest_url = 'https://microweber.org/api/market_json';
    private $package_manager_urls = array(
        'https://packages.microweberapi.com/packages.json',
        'https://private-packages.microweberapi.com/packages.json',
    );

    public function get_packages()
    {

        $allowed_package_types = array(
            'microweber-template',
            'microweber-module',
        );

        $return = array();
        $packages = array();
        $packages_by_type = array();

        if ($this->package_manager_urls) {
            foreach ($this->package_manager_urls as $url) {
                $package_manager_resp = @file_get_contents($url);
                $package_manager_resp = @json_decode($package_manager_resp, true);
                if ($package_manager_resp and isset($package_manager_resp['packages']) and is_array($package_manager_resp['packages'])) {
                    $packages = array_merge($packages, $package_manager_resp['packages']);
                }
            }

        }

        if ($packages) {
            foreach ($packages as $pk=>$package) {

                $version_type = false;
                $package_item = $package;
                $last_item = array_pop($package_item);
                if (isset($last_item['type'])) {
                    $version_type = $last_item['type'];
                }

                if ($version_type and in_array($version_type, $allowed_package_types)) {
                    $package_is_allowed = true;
                    $return[$pk] = $package;
                    if(!isset($packages_by_type[$version_type])){
                        $packages_by_type[$version_type] = array();
                    }
                    $packages_by_type[$version_type][$pk] = $package;

                }

            }
        }

        return $packages_by_type;
    }


    public function get_templates()
    {


        $templates = $this->get_packages();

//
//        $templates = file_get_contents($this->manifest_url);
//        $templates = @json_decode($templates, true);
        $return = array();
        if ($templates and isset($templates["microweber-template"])) {
            foreach ($templates["microweber-template"] as $pk =>$template) {
                $package_item = $template;
                $last_item = array_pop($package_item);
                $template['latest_version'] = $last_item;
                $screenshot = '';
                if (isset($template['latest_version']) AND isset($template['latest_version']['extra']) AND isset($template['latest_version']['extra']['_meta']) AND isset($template['latest_version']['extra']['_meta']['screenshot'])) {
                    $screenshot = $template['latest_version']['extra']['_meta']['screenshot'];
                }
                if (isset($template['latest_version']) AND isset($template['latest_version']['extra']) AND isset($template['latest_version']['extra']['_meta']) AND isset($template['latest_version']['extra']['_meta']['screenshot'])) {
                    $screenshot = $template['latest_version']['extra']['_meta']['screenshot'];
                }


              //$template['screenshot'] = $screenshot;
                $return[$pk] = $template;

            }

        }
        return $return;

    }


}