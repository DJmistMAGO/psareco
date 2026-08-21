import './bootstrap.js';
import * as fecha from 'fecha';
import Alpine from 'alpinejs';
import flatpickr from "flatpickr";

import HotelDatepicker from 'hotel-datepicker';
import 'hotel-datepicker/dist/css/hotel-datepicker.css';

import './bootstrap';
import Swal from 'sweetalert2';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Alpine = Alpine;
window.HotelDatepicker = HotelDatepicker;
window.flatpickr = flatpickr;
window.Swal = Swal;
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.interactionPlugin = interactionPlugin;

Alpine.start();
