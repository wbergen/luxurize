import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue/dist/vue.esm-bundler';
window.createApp = createApp;
window.Alpine = Alpine;
Alpine.start();
document.dispatchEvent(new CustomEvent('app-loaded'));
