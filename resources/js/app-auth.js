import './bootstrap';
import './assets';

// ── Core UI
import * as bootstrap from 'bootstrap';
import Waves from 'node-waves';
import feather from 'feather-icons';

// ── Form Plugins yang dipakai di halaman auth
import flatpickr from 'flatpickr';
import Toastify from 'toastify-js';
import Swal from 'sweetalert2';
import './plugin/particles.init.js';
import './plugin/lordicon.js';

// ── CSS
import 'toastify-js/src/toastify.css';

// ── Expose ke window
window.bootstrap = bootstrap;
window.Waves     = Waves;
window.feather   = feather;
window.flatpickr = flatpickr;
window.Toastify  = Toastify;
window.Swal      = Swal;

// ── Init
Waves.init();
feather.replace();

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