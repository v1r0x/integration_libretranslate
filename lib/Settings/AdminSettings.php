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
 use OCP\AppFramework\Services\IInitialState;
 use OCP\IConfig;
 use OCP\Settings\ISettings;

 use OCA\IntegrationLibreTranslate\AppInfo\Application;
 
 class AdminSettings implements ISettings {
    private $config;
    private $initialStateService;

    /**
     * @param IConfig $config
     * @param IInitialState $initialStateService
     */
    public function __construct(
        IConfig $config,
        IInitialState $initialStateService,
    ) {
        $this->config = $config;
        $this->initialStateService = $initialStateService;
    }

    public function getForm(): TemplateResponse {
        $host = $this->config->getAppValue(Application::APP_ID, 'host', 'http://localhost');
        $port = $this->config->getAppValue(Application::APP_ID, 'port', null);
        $apikey = $this->config->getAppValue(Application::APP_ID, 'apikey', '');
        $fromLang = $this->config->getAppValue(Application::APP_ID, 'from_lang', 'en');
        $toLang = $this->config->getAppValue(Application::APP_ID, 'to_lang', 'de');
		
        $this->initialStateService->provideInitialState('admin_config', [
            'host' => $host,
            'port' => $port,
            'apikey' => $apikey,
            'fromLang' => $fromLang,
            'toLang' => $toLang,
        ]);

        return new TemplateResponse(Application::APP_ID, 'settings.admin');
    }

    public function getSection(): string {
        return 'additional';
    }

    public function getPriority():int {
        return 50;
    }
}