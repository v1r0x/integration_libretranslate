<template>
    <div id="libretranslate_prefs" class="section">
        <NcSettingsSection
            :name="t('integration_libretranslate', 'Libretranslate Integration')"
            description="Settings for Libretranslate Integration">
            <NcTextField :value.sync="state.host"
                placeholder="https://ltranslate.your-domain.tld"
                trailing-button-icon="close"
                :label="t('integration_libretranslate', 'Host')"
                :show-trailing-button="state.host !== ''"
                @trailing-button-click="clearHost"/>
            <NcTextField :value.sync="state.port"
                placeholder="8080"
                type="number"
                trailing-button-icon="close"
                :label="t('integration_libretranslate', 'Port')"
                :show-trailing-button="state.port !== null"
                @trailing-button-click="clearPort"/>
            <NcTextField :value.sync="state.apikey"
                placeholder="xxxx-xxxx-xxxx-xxxx"
                trailing-button-icon="close"
                :label="t('integration_libretranslate', 'API-Key')"
                :show-trailing-button="state.apikey !== ''"
                @trailing-button-click="clearApikey"/>
            <NcTextField :value.sync="state.fromLang"
                placeholder="en"
                trailing-button-icon="close"
                :label="t('integration_libretranslate', 'Source Language')"
                :show-trailing-button="state.fromLang !== ''"
                @trailing-button-click="clearSrcLang"/>
            <NcTextField :value.sync="state.toLang"
                placeholder="de"
                trailing-button-icon="close"
                :label="t('integration_libretranslate', 'Target Language')"
                :show-trailing-button="state.toLang !== ''"
                @trailing-button-click="clearTgtLang"/>

            <NcButton
                style="margin-top: 0.5rem"
                type="success"
                :disabled="disabled"
                :readonly="readonly"
                @click="saveConfig">
                <template #icon>
                    <ContentSave
                        :size="20" />
                </template>
                {{ t('integration_libretranslate', 'Save Settings') }}
            </NcButton>
        </NcSettingsSection>

    </div>
</template>

<script>
    import axios from '@nextcloud/axios';
    import {
        loadState,
    } from '@nextcloud/initial-state';
    import {
        generateUrl,
    } from '@nextcloud/router';
    import {
        translate as t,
        translatePlural as n,
    } from '@nextcloud/l10n';
    import {
        NcSettingsSection,
        NcTextField,
        NcButton,
    } from '@nextcloud/vue';
    import {
        showSuccess,
        showError,
    } from '@nextcloud/dialogs';

    import ContentSave from 'vue-material-design-icons/ContentSave';

    export default {
        name: 'AdminSettings',
        components: {
            NcSettingsSection,
            NcTextField,
            NcButton,
            ContentSave,
        },
        methods: {
            t,
            n,
            clearHost() {
                this.state.host = '';
            },
            clearPort() {
                this.state.port = null;
            },
            clearApikey() {
                this.state.apikey = '';
            },
            clearSrcLang() {
                this.state.fromLang = '';
            },
            clearTgtLang() {
                this.state.toLang = '';
            },
            saveConfig() {
                const url = generateUrl('/apps/integration_libretranslate/admin_config');
                const data = {
                    values: {
                        host: this.state.host,
                        port: this.state.port,
                        apikey: this.state.apikey,
                        from_lang: this.state.fromLang,
                        to_lang: this.state.toLang,
                    },
                };

                axios.put(url, data).then(_ => {
                    const msg = t('integration_libretranslate', 'LibreTranslate admin options saved');
                    showSuccess(msg);
                }).catch(error => {
                    const msg = t('integration_libretranslate', 'Failed to save LibreTranslate admin options!\n') + (error.response?.request?.responseText ?? '');
                    showError(msg);
                    console.error(error);
                });
            },
        },
        data() {
            return {
                disabled: false,
                readonly: false,
                state: loadState('integration_libretranslate', 'admin_config'),
            };
        }
    }
</script>