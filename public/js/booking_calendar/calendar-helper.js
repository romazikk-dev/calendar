/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/ts/booking_calendar/helper/Time.ts":
/*!*********************************************************!*\
  !*** ./resources/js/ts/booking_calendar/helper/Time.ts ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Time = void 0;

var Time =
/** @class */
function () {
  function Time() {
    this.timezone = timezone;
  }

  Time.prototype.getEventDate = function (event) {
    if (event === null || typeof event.time === 'undefined' || event.time === null) return null;
    var momentDate = moment(event.date, 'YYYY-MM-DD');
    return momentDate.format('D MMMM YYYY, ddd'); // return this.e.year + '-' + this.e.month + '-' + this.e.day;
  };

  Time.prototype.parseStringHourMinutesToMinutes = function (hourMinutesStr) {
    var arr, elHours, elMinutes;
    arr = hourMinutesStr.split(':');
    elHours = Number(arr[0]);
    elMinutes = Number(arr[1]);
    return elHours * 60 + elMinutes;
  };

  Time.prototype.composeHourMinuteTimeFromMinutes = function (mins) {
    var minutesStr, hoursStr, minutes, hours;
    minutes = mins % 60;
    hours = Math.floor(mins / 60);

    if (minutes <= 0) {
      minutesStr = '00';
    } else if (minutes > 0 && minutes < 10) {
      minutesStr = '0' + String(minutes);
    } else {
      minutesStr = String(minutes);
    }

    if (hours <= 0) {
      hoursStr = '00';
    } else if (hours > 0 && hours < 10) {
      hoursStr = '0' + String(hours);
    } else {
      hoursStr = String(hours);
    }

    return hoursStr + ':' + minutesStr;
  };

  return Time;
}();

exports.Time = Time;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
var exports = __webpack_exports__;
/*!********************************************************!*\
  !*** ./resources/js/ts/booking_calendar/helper/app.ts ***!
  \********************************************************/


Object.defineProperty(exports, "__esModule", ({
  value: true
})); // export function helloWorld(): string {
//     return "Hello world!";
// }
// import { MovingE } from "./MovingE";
// import { NewE } from "./NewE";
// import { Person } from "./Person";

var Time_1 = __webpack_require__(/*! ./Time */ "./resources/js/ts/booking_calendar/helper/Time.ts");

var Helper =
/** @class */
function () {
  // view?: View;
  // filter?: Filter;
  // freeGetDataParams?: FreeGetDataParams;
  // numericHelper?: NumericHelper;
  function Helper() {
    // this.movingE = new MovingE();
    // this.newE = new NewE();
    // this.person = new Person();
    this.time = new Time_1.Time(); // this.view = new View();
    // this.filter = new Filter();
    // this.freeGetDataParams = new FreeGetDataParams();
    // this.numericHelper = new NumericHelper();
  }

  return Helper;
}();

window.calendarHelper = new Helper();
})();

/******/ })()
;