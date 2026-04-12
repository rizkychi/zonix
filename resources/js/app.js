import './bootstrap';
import './layout';
import './assets';
import './app-core';

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
import './plugin/lordicon.js';
import './plugin/datatables.init.js';
import { swal, toast } from './plugin/swal.init.js';

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
window.Select2    = Select2;
window.swal       = swal;
window.toast      = toast;

// ── Init
Waves.init();
feather.replace();

// ── SimpleBar auto-init for elements with [data-simplebar] attribute
document.querySelectorAll('[data-simplebar]').forEach(el => new SimpleBar(el));

// ── Auto-import page-specific JS modules based on body data-page attribute
const page = document.body.dataset.page
const modules = import.meta.glob('./pages/**/*.js')

if (page) {
    const key = `./pages/${page}.js`

    if (modules[key]) {
        modules[key]()
            .then(m => m.init?.())
            .catch(err => console.warn(`[page-loader] failed to load "${key}":`, err))
    }
    // if no matching page module found, it's not necessarily an error, so we won't log anything.
}