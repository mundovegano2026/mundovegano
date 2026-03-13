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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/prices.js":
/*!**************************************!*\
  !*** ./resources/js/admin/prices.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./resources/js/utils.js");

var priceManager = {
  isValid: false,
  priceForm: "",
  priceErrorMessageList: [],
  originalName: "",
  modalId: "",
  init: function init() {
    priceManager.priceForm = $('#price-form');
    priceManager.originalName = $('#name').val();
  },
  validate: function validate() {
    // Clear error styles
    priceManager.priceForm.find('input, select').removeClass('is-invalid'); // Init Validation

    priceManager.isValid = true;
    priceManager.priceErrorMessageList = [];

    var _token = $('input[name="_token"]').val(); // Validate Required


    priceManager.priceForm.find('input[required], select[required]').each(function (i, el) {
      var field = $(el);

      if (field.val() == "") {
        field.addClass('is-invalid');
        priceManager.isValid = false;
        priceManager.priceErrorMessageList.push("Deve preencher " + field.data('desc') + ".");
      }
    }); // Validate Unique Fields

    if ($('#name').val() != "") {
      if ($('#name').val() != priceManager.originalName) {
        // Unique name
        $.ajax({
          url: window.env_basepath + "admin/prices/validateName",
          method: "POST",
          data: {
            name: $('#name').val(),
            _token: _token
          },
          success: function success(data) {
            // If name and brand unique key exists
            if (data) {
              $('#name').addClass('is-invalid');
              priceManager.isValid = false;
              priceManager.priceErrorMessageList.push("Nome de cadeia já existe.");
              priceManager.showErrors();
            } else if (priceManager.isValid) {
              priceManager.validateLogic();
            } else {
              priceManager.showErrors();
            }
          },
          error: function error(xhr, _error) {
            _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].removeFlash('#' + priceManager.modalId + ' .modal-body');
            _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].addFlash('danger', '#' + priceManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');
          }
        });
      } else {
        priceManager.validateLogic();
      }
    } else {
      // Set error if form is invalid
      priceManager.showErrors();
    }
  },
  validateLogic: function validateLogic() {
    // Submit form if valid
    if (priceManager.isValid) {
      priceManager.priceForm.submit();
    } else {
      priceManager.showErrors();
    }
  },
  showErrors: function showErrors() {
    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].removeFlash('#' + priceManager.modalId + ' .modal-body');

    if (!priceManager.isValid) {
      for (var i = priceManager.priceErrorMessageList.length - 1; i >= 0; i--) {
        _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].addFlash('danger', '#' + priceManager.modalId + ' .modal-body', priceManager.priceErrorMessageList[i]);
      }
    }
  }
};
window.priceManager = priceManager;
$('document').ready(function () {
  $(document).on('click', '#submit-price', function () {
    priceManager.validate();
  }); // On clicking edit price button

  $('.editPrice').on('click', function () {
    // Prepare price data
    var this_id = $(this).attr('data-id');
    var this_action = $(this).attr('data-action');
    var page = window.location.href.split("?")[0].replace("#", ""); // Clear modal

    $('#editRecordModal .load_modal').html('');
    $('#newRecordModal .load_modal').html(''); // For the create button

    if (this_action == 'create') {
      // Get the modal screen to load
      $.get(page + "/loadModal" + "/" + 0, function (data) {
        $('#newRecordModal').modal();
        $('#newRecordModal').on('shown.bs.modal', function () {
          // Load modal screen
          $('#newRecordModal .load_modal').html(data);
          priceManager.init();
          priceManager.modalId = 'newRecordModal';
        });
        $('#newRecordModal').on('hidden.bs.modal', function () {
          $('#newRecordModal .modal-body').data('');
        });
      });
    } // For the edit button


    if (this_action == 'edit') {
      // Get the modal screen to load
      $.get(page + "/loadModal" + "/" + this_id, function (data) {
        $('#editRecordModal').modal();
        $('#editRecordModal').on('shown.bs.modal', function () {
          // Load modal screen
          $('#editRecordModal .load_modal').html(data);
          priceManager.init();
          priceManager.modalId = 'editRecordModal';
        });
        $('#editRecordModal').on('hidden.bs.modal', function () {
          $('#editRecordModal .modal-body').data('');
        });
      });
    }
  });
});

/***/ }),

/***/ "./resources/js/utils.js":
/*!*******************************!*\
  !*** ./resources/js/utils.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var utils = {
  removeAfter: function removeAfter(selector, currentPos, fieldName) {
    $(selector).each(function (i, el) {
      var curEl = $(el);

      if (parseInt(curEl.attr('pos')) > currentPos) {
        curEl.remove();
      }
    });
    $(selector).last().attr('name', fieldName);
  },
  tableContains: function tableContains(tableObj, fieldText) {
    var textFound = false;
    var fields = tableObj.find('td').each(function (i, el) {
      if ($(el).text() == fieldText) {
        textFound = true;
        return textFound;
      }
    });
    return textFound;
  },
  removeFlash: function removeFlash(container) {
    // Remove current messages
    $(container).find('.alert').remove();
  },
  addFlash: function addFlash(type, container, message) {
    // Add new messages
    $(container).prepend('<div class="alert alert-' + type + ' alert-block">\
        <a class="close" data-dismiss="alert" href="#">×</a>\
        ' + message + '\
        </div>'); // Scroll modal to top

    $(window).scrollTop($(container).offset().top);
    $(container).parents('.modal').animate({
      scrollTop: 0
    }, 'slow');
  },
  addVueFlash: function addVueFlash(type, container, message) {
    // Add new messages
    $(container).prepend('<div class="alert alert-' + type + ' alert-block">\
        <a class="close" data-dismiss="alert" href="#">×</a>\
        ' + message + '\
        </div>'); // Scroll modal to top

    $(window).scrollTop($(container).offset().top);
    $(container).parents('.modal').scrollTop(0);
    console.log(container);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (utils);

/***/ }),

/***/ 5:
/*!********************************************!*\
  !*** multi ./resources/js/admin/prices.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\mundovegano\resources\js\admin\prices.js */"./resources/js/admin/prices.js");


/***/ })

/******/ });