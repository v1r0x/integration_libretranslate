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

 /** @var array $_ */
?>
<div id="integration-libretranslate" class="section">
    <h2 class="inlineblock">Libretranslate Integration</h2>

    <form>
        <label for="integration-libretranslate-host">
            Host:
        </label>
        <input type="text" id="integration-libretranslate-host" value="<?php p($_['host']) ?>" placeholder="https://cloud.your-domain.tld" />
        <label for="integration-libretranslate-port">
            Port:
        </label>
        <input type="text" id="integration-libretranslate-port" value="<?php p($_['port']) ?>" placeholder="E.g. 5000" />
        <label for="integration-libretranslate-apikey">
            API-Key:
        </label>
        <input type="text" id="integration-libretranslate-apikey" value="<?php p($_['apikey']) ?>" placeholder="xxxxx-xxxxx-xxxxx" />
        <label for="integration-libretranslate-from-lang">
            Source Language:
        </label>
        <input type="text" id="integration-libretranslate-from-lang" value="<?php p($_['from']) ?>" placeholder="en" />
        <label for="integration-libretranslate-to-lang">
            Target Language:
        </label>
        <input type="text" id="integration-libretranslate-to-lang" value="<?php p($_['to']) ?>" placeholder="de" />
    </form>
</div>