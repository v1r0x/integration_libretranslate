<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2023 Vinzenz Rosenkranz <vinzenz.rosenkranz@posteo.de>
 *
 * @author Vinzenz Rosenkranz <vinzenz.rosenkranz@posteo.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

 namespace OCA\IntegrationLibreTranslate\Settings;

 use OCP\AppFramework\Http\TemplateResponse;
 use OCP\IConfig;
 use OCP\Settings\ISettings;
 
 class AdminSettings implements ISettings {
    private $config;

    /**
     * @param IConfig $config
     */
    public function __construct(IConfig $config) {
        $this->config = $config;
    }

    public function getForm(): TemplateResponse {
        $host = $this->config->getAppValue('integration_libretranslate', 'host', 'http://localhost');
        $port = $this->config->getAppValue('integration_libretranslate', 'port', null);
        $apikey = $this->config->getAppValue('integration_libretranslate', 'apikey', null);
        $from = $this->config->getAppValue('integration_libretranslate', 'from_lang', 'en');
        $to = $this->config->getAppValue('integration_libretranslate', 'to_lang', 'de');
        return new TemplateResponse('integration_libretranslate', 'settings.admin', [
            'host' => $host,
            'port' => $port,
            'apikey' => $apikey,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function getSection(): string {
        return 'additional';
    }

    public function getPriority():int {
        return 50;
    }
}