import './bootstrap';

import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';

import { setupFaqs } from './admin/content-blocks';

window.Alpine = Alpine;
window.Swiper = Swiper;

/* Page and blog content built with the content builder: make FAQ answers open and close. */
window.setupContentBlocks = setupFaqs;
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => setupFaqs());
else setupFaqs();

Alpine.start();
