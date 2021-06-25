/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/ts/dashboard/calendar_helper/MovingE.ts":
/*!**************************************************************!*\
  !*** ./resources/js/ts/dashboard/calendar_helper/MovingE.ts ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.MovingE = void 0;

var MovingE =
/** @class */
function () {
  // range: DateRange;
  function MovingE() {
    this.movingEvent = movingEvent;
  }

  MovingE.prototype.parse = function () {// console.log(movingEvent);
    // console.log(JSON.parse(movingEvent));
    // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
  };

  MovingE.prototype.getItem = function (item) {
    if (item === void 0) {
      item = null;
    }

    if (item === null || typeof this.movingEvent === 'undefined' || this.movingEvent === null) return null;
    item = item.split('.');

    if (item.length === 1) {
      return typeof this.movingEvent[item[0]] !== 'undefined' ? this.movingEvent[item[0]] : null;
    } else if (item.length === 2) {
      return typeof this.movingEvent[item[0]] !== 'undefined' && typeof this.movingEvent[item[0]][item[1]] !== 'undefined' ? this.movingEvent[item[0]][item[1]] : null;
    }

    return null;
  };

  return MovingE;
}();

exports.MovingE = MovingE;

/***/ }),

/***/ "./resources/js/ts/dashboard/calendar_helper/Person.ts":
/*!*************************************************************!*\
  !*** ./resources/js/ts/dashboard/calendar_helper/Person.ts ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Person = void 0;

var Person =
/** @class */
function () {
  function Person() {} // constructor() {
  //     this.movingEvent = movingEvent;
  // }


  Person.prototype.fullName = function (obj) {
    if (obj === null || typeof obj.first_name === 'undefined' || typeof obj.last_name === 'undefined') return null;
    var fullName = obj.first_name;
    if (obj.last_name !== null && typeof obj.last_name === 'string' && obj.last_name.length > 0) fullName += ' ' + obj.last_name;
    return fullName;
  };

  return Person;
}();

exports.Person = Person;

/***/ }),

/***/ "./resources/js/ts/dashboard/calendar_helper/Time.ts":
/*!***********************************************************!*\
  !*** ./resources/js/ts/dashboard/calendar_helper/Time.ts ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Time = void 0;

var Time =
/** @class */
function () {
  function Time() {} // constructor() {
  //     // this.movingEvent = movingEvent;
  // } 


  Time.prototype.composeHourMinuteTimeFromMinutes = function (mins) {
    // alert(mins);
    // console.log(mins);
    var minutesStr, hoursStr, minutes, hours;
    minutes = mins % 60;
    hours = Math.floor(mins / 60); // alert(hours);

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
    } // console.log(mins);
    // console.log(hours);
    // console.log(minutes);
    // alert(minutesStr);


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
/*!**********************************************************!*\
  !*** ./resources/js/ts/dashboard/calendar_helper/app.ts ***!
  \**********************************************************/


Object.defineProperty(exports, "__esModule", ({
  value: true
})); // export function helloWorld(): string {
//     return "Hello world!";
// }

var MovingE_1 = __webpack_require__(/*! ./MovingE */ "./resources/js/ts/dashboard/calendar_helper/MovingE.ts");

var Person_1 = __webpack_require__(/*! ./Person */ "./resources/js/ts/dashboard/calendar_helper/Person.ts");

var Time_1 = __webpack_require__(/*! ./Time */ "./resources/js/ts/dashboard/calendar_helper/Time.ts");

var Helper =
/** @class */
function () {
  // range: DateRange;
  function Helper() {
    this.movingE = new MovingE_1.MovingE();
    this.person = new Person_1.Person();
    this.time = new Time_1.Time(); // alert(1111); 
    // this.view = EnumView.MONTH;
    // this.range = new DateRange(EnumView.MONTH);
  }

  Helper.prototype.parse = function () {
    // return JSON.parse(JSON.stringify(value));
    alert(2222);
  };

  return Helper;
}();

window.calendarHelper = new Helper();
})();

/******/ })()
;