/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/dashboard.js":
/*!*****************************************!*\
  !*** ./resources/js/admin/dashboard.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$('document').ready(function () {
  // Visitors chart
  var $chrt_border_color = "#efefef";
  var $chrt_second = "#6595b4";
  var $chrt_fthird = "#7e9d3a";

  if ($("#visitors-chart").length) {
    var visitData = [];
    var max = 0;

    for (var key in window.visitList) {
      if (max < window.visitList[key]) max = window.visitList[key].length;
      var dateParts = key.split("/");
      visitData.push([new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).getTime(), window.visitList[key].length]);
    }

    visitData = visitData.sort(function (a, b) {
      return a[0] > b[0] ? 1 : -1;
    });
    var options = {
      xaxis: {
        mode: "time",
        tickLength: 5,
        tickSize: [1, "day"]
      },
      series: {
        lines: {
          show: true,
          lineWidth: 1,
          fill: true,
          fillColor: {
            colors: [{
              opacity: 0.1
            }, {
              opacity: 0.15
            }]
          }
        },
        points: {
          show: true
        },
        shadowSize: 0
      },
      selection: {
        mode: "x"
      },
      grid: {
        hoverable: true,
        clickable: true,
        tickColor: $chrt_border_color,
        borderWidth: 0,
        borderColor: $chrt_border_color
      },
      tooltip: true,
      tooltipOpts: {
        content: "Visitantes: <span>%y</span>",
        //dateFormat : "%y-%0m-%0d",
        defaultTheme: false
      },
      colors: [$chrt_second]
    };
    var plot = $.plot($("#visitors-chart"), [visitData], options);
  }

  ;
});

/***/ }),

/***/ 13:
/*!***********************************************!*\
  !*** multi ./resources/js/admin/dashboard.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\mundovegano\resources\js\admin\dashboard.js */"./resources/js/admin/dashboard.js");


/***/ })

/******/ });