import './bootstrap';

import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

// ── Core UI
import * as bootstrap from 'bootstrap';
import Waves from 'node-waves';
import feather from 'feather-icons';

// ── Form Plugins yang dipakai di halaman auth
import flatpickr from 'flatpickr';
import Toastify from 'toastify-js';
import Swal from 'sweetalert2';
import './pages/plugin/particles.init.js';
import './pages/plugin/lordicon.js';

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