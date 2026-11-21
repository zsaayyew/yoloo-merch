// ENTRY JS - JavaScript Public Website
// Domain : SHARED - dipakai lintas domain (ubah hanya setelah koordinasi)

import './app';
import './public/mobile-nav.js';
import './public/gallery-filter.js';
import './public/product-configurator.js';
import { initSubmitGuard } from './shared/submit-guard';

initSubmitGuard();
import { initFileUpload } from './public/file-upload';
initFileUpload();
