// ENTRY JS - JavaScript Public Website
// Domain : SHARED - dipakai lintas domain (ubah hanya setelah koordinasi)
// Status : placeholder, belum diimplementasikan.

import './app';
import './public/product-configurator.js';
import { initSubmitGuard } from './shared/submit-guard';

initSubmitGuard();
import { initFileUpload } from './public/file-upload';
initFileUpload();

// Import modul fitur saat fitur dikerjakan, contoh:
// import './public/mobile-nav';
// import './shared/lightbox';
