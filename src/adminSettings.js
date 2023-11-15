import Vue from 'vue';
import AdminSettings from './components/AdminSettings.vue';

const settings = Vue.extend(AdminSettings);
new settings().$mount('#integration-libretranslate-prefs');