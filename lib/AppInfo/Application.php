<?php
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

namespace OCA\IntegrationLibreTranslate\AppInfo;

use OCA\IntegrationLibreTranslate\LibretranslateProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'integration_libretranslate';

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		require_once __DIR__ . '/../../vendor/autoload.php';

		$context->registerTranslationProvider(LibretranslateProvider::class);
	}

	public function boot(IBootContext $context): void {
	}
}
