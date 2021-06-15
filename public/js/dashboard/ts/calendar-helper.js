/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/ts/dashboard/calendar_helper/movingE.ts":
/*!**************************************************************!*\
  !*** ./resources/js/ts/dashboard/calendar_helper/movingE.ts ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.movingE = void 0;

var movingE =
/** @class */
function () {
  // range: DateRange;
  function movingE() {
    this.movingEvent = movingEvent;
  }

  movingE.prototype.parse = function () {// console.log(movingEvent);
    // console.log(JSON.parse(movingEvent));
    // console.log(JSON.parse(JSON.stringify(this.movingEvent)));
  };

  movingE.prototype.getItem = function (item) {
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

  return movingE;
}();

exports.movingE = movingE;

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

var movingE_1 = __webpack_require__(/*! ./movingE */ "./resources/js/ts/dashboard/calendar_helper/movingE.ts"); // import { View as EnumView } from "./enums/View";


var Helper =
/** @class */
function () {
  // range: DateRange;
  function Helper() {
    this.movingE = new movingE_1.movingE(); // alert(1111); 
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