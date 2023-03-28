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


namespace OCA\IntegrationLibreTranslate;

use Jefs42\LibreTranslate;
use OCA\IntegrationLibreTranslate\AppInfo\Application;
use OCP\ICacheFactory;
use OCP\IConfig;
use OCP\L10N\IFactory;
use OCP\Translation\IDetectLanguageProvider;
use OCP\Translation\ITranslationProvider;
use OCP\Translation\LanguageTuple;
use Psr\Log\LoggerInterface;
use RuntimeException;

class LibretranslateProvider implements ITranslationProvider, IDetectLanguageProvider {
	private LibreTranslate $translator;
	
	private string $host = '';
	private array $languages = [];
	private array $localCache = [];

	public function __construct(
		private IConfig $config,
		private ICacheFactory $cacheFactory,
		private LoggerInterface $logger,
		private IFactory $l10nFactory
	) {
		$host = $this->config->getAppValue(Application::APP_ID, 'host', 'http://localhost');
		$port = $this->config->getAppValue(Application::APP_ID, 'port', null);
		$key = $this->config->getAppValue(Application::APP_ID, 'apikey', null);
		$src = $this->config->getAppValue(Application::APP_ID, 'from_lang', 'en');
		$tgt = $this->config->getAppValue(Application::APP_ID, 'to_lang', 'de');
		
		$urlParts = parse_url($host);
		$scheme = $urlParts['scheme'] ?? 'xxx';
		$serv = $urlParts['host'] ?? 'UnknownProvider';
		$this->host = $scheme  . "://" . $serv;

		if($port == '' || $port == -1) {
			$port = null;
		}
		if($key == '') {
			$key = null;
		}
				
		try {
			$this->translator = new LibreTranslate($host, $port, $src, $tgt);
			if(isset($key)) {
				$this->translator->setApiKey($key);
			}
			
			$this->languages = $this->translator->Languages();
		} catch(\Exception $e) {
			$this->logger->error('Failed to initialize libretranslate service!', ['exception' => $e]);
		}
	}

	public function getName(): string {
		return "LibreTranslate - $this->host";
	}

	// Based on Code from https://github.com/juliushaertl/integration_deepl/blob/main/lib/DeeplProvider.php
	// Thanks @juliushaertl
	public function getAvailableLanguages(): array {
		$cache = $this->cacheFactory->createDistributed('integration_libretranslate');
		$fromCache = $cache->get('languages');
		if(isset($fromCache)) {
			return array_map(function($entry) {
				return $entry instanceof LanguageTuple ? $entry : LanguageTuple::fromArray($entry);
			}, $fromCache);
		}

		$l10nLangs = $this->l10nFactory->getLanguages();
		$commonLangs = $l10nLangs['commonLanguages'];
		$otherLangs = $l10nLangs['otherLanguages'];
		$merged = array_merge($commonLangs, $otherLangs);
		$coreLanguages = array_reduce($merged, function($carry, $val) {
			$carry[$val['code']] = $val['name'];
			return $carry;
		});

		$availableLanguages = [];
		foreach($this->languages as $code => $lang) {
			foreach($this->languages as $tcode => $tlang) {
				if($code == $tcode) continue;

				$sourceName = $coreLanguages[strtolower($code)] ?? $lang;
				$targetName = $coreLanguages[strtolower($tcode)] ?? $tlang;
				$availableLanguages[] = new LanguageTuple(
					$code, $sourceName,
					$tcode, $targetName
				);
			}
		}

		$cache->set('languages', $availableLanguages, 3600);
		return $availableLanguages;
	}

	public function detectLanguage(string $text): ?string {
		try {
			$cacheKey = md5($text);
			$result = $this->localCache[$cacheKey] ?? $this->translator->Detect($text);
			if(!$result) {
				$this->logger->error('Failed to detect language!');
				return null;
			} else {
				$this->localCache[$cacheKey] = $result;
				return $result;
			}
		} catch(\Exception $e) {
			$this->logger->error('Failed to detect language!', ['exception' => $e]);
			return null;
		}
	}

	// Based on Code from https://github.com/juliushaertl/integration_deepl/blob/main/lib/DeeplProvider.php
	// Thanks @juliushaertl
	public function translate(?string $fromLanguage, string $toLanguage, string $text): string {
		try {
			$cacheKey = ($fromLanguage ?? '') . '/' . $toLanguage . '/' . md5($text);
			$splitLanguage = explode('_', $toLanguage, 2);
			$toLanguage = $splitLanguage[0];

			$result = $this->localCache[$cacheKey] ?? $this->translator->Translate($text, $fromLanguage, $toLanguage);
			$this->localCache[$cacheKey] = $result;
			return $result;
		} catch(\Exception $e) {
			throw new \RuntimeException("Failed to translate text from {$fromLanguage} to {$toLanguage}", 0, $e);
		}
	}
}

