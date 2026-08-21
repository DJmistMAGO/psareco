import './bootstrap.js';
import * as fecha from 'fecha';
import Alpine from 'alpinejs';
import flatpickr from "flatpickr";

import HotelDatepicker from 'hotel-datepicker';
import 'hotel-datepicker/dist/css/hotel-datepicker.css';

import './bootstrap';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.HotelDatepicker = HotelDatepicker;
window.flatpickr = flatpickr;
window.Swal = Swal;

Alpine.start();
