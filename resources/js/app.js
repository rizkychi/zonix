import './bootstrap';

// ── Core UI
import * as bootstrap from 'bootstrap';
import SimpleBar from 'simplebar';
import Waves from 'node-waves';
import feather from 'feather-icons';

// ── Common Form Plugins (global, mostly used)
import Choices from 'choices.js';
import flatpickr from 'flatpickr';
import Toastify from 'toastify-js';
import Swal from 'sweetalert2';
import Select2 from 'select2';
import './pages/plugin/lordicon.js';

// ── CSS Library Imports
import 'choices.js/public/assets/styles/choices.css';
import 'toastify-js/src/toastify.css';
import 'select2/dist/css/select2.min.css';

// ── Expose to window (needed by inline scripts in Blade)
window.bootstrap  = bootstrap;
window.SimpleBar  = SimpleBar;
window.Waves      = Waves;
window.feather    = feather;
window.Choices    = Choices;
window.flatpickr  = flatpickr;
window.Toastify   = Toastify;
window.Swal       = Swal;
window.Select2     = Select2;

// ── Layout & App core
import('./layout').then(() => {
    import('./app-core');
});

// ── Init
Waves.init();
feather.replace();

// ── SimpleBar auto-init for elements with [data-simplebar] attribute
document.querySelectorAll('[data-simplebar]').forEach(el => new SimpleBar(el));
