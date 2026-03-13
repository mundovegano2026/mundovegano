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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/store.js":
/*!*************************************!*\
  !*** ./resources/js/admin/store.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./resources/js/utils.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr && (typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]); if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


var storeManager = {
  validChain: true,
  isValid: false,
  storeForm: "",
  storeErrorMessageList: [],
  chainField: "",
  originalName: "",
  originalChain: "",
  modalId: "",
  init: function init() {
    storeManager.storeForm = $('#store-form');
    storeManager.chainField = $('#chain');
    storeManager.originalName = $('#name').val();
    storeManager.originalChain = $('#chain').val();
  },
  updateCaopSelect: function updateCaopSelect(selectBox, updateOptions, list, selectedValue) {
    selectBox.find('option').not(':first').remove();

    if (updateOptions) {
      var compare = function compare(a, b) {
        if (a.value < b.value) {
          return -1;
        }

        if (a.value > b.value) {
          return 1;
        }

        return 0;
      };

      var sortedList = [];
      Object.entries(list).forEach(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            key = _ref2[0],
            value = _ref2[1];

        sortedList.push({
          key: key,
          value: value
        });
      });
      sortedList.sort(compare);

      for (var i = 0; i < sortedList.length; i++) {
        var selected = "";
        var key = sortedList[i].key;
        var value = sortedList[i].value; // Set new value    

        if (key == selectedValue) selected = "selected";
        selectBox.append("<option value=" + key + " " + selected + ">" + value + "</option>");
      } // Object.entries(list).forEach(([key, value]) => {           
      //     var selected = "";
      //     // Set new value    
      //     if(key == selectedValue) selected = "selected"; 
      //     selectBox.append("<option value=" + key + " " + selected + ">" + value + "</option>");
      // });  

    }
  },
  updateCaop: function updateCaop(data) {
    var distritoSelect = $('#distrito');
    var concelhoSelect = $('#concelho');
    var freguesiaSelect = $('#freguesia');
    var originalDistrito = distritoSelect.val();
    var originalConcelho = concelhoSelect.val();
    var newDistrito = data["dicofre"]["dicofre"].substr(0, 2);
    var newConcelho = data["dicofre"]["dicofre"].substr(0, 4);
    var newFreguesia = data["dicofre"]["dicofre"];
    var concelhoList = data["concelhos"];
    var freguesiaList = data["freguesias"];

    if (originalDistrito != newDistrito) {
      // Set new distrito                        
      distritoSelect.val(newDistrito); // Update Concelhos

      this.updateCaopSelect(concelhoSelect, newDistrito != "", concelhoList, newConcelho);
    }

    if (originalConcelho != newConcelho) {
      // Update Freguesias
      this.updateCaopSelect(freguesiaSelect, newConcelho != "", freguesiaList, newFreguesia);
    }
  },
  updateDistrito: function updateDistrito(featureCoordField) {
    // Clear previously present stores
    storeManager.resetLayer(false, featureCoordField);
    var distritoSelect = $('#distrito');
    var concelhoSelect = $('#concelho');
    var freguesiaSelect = $('#freguesia');

    if (distritoSelect.val() == "") {
      concelhoSelect.find('option').not(':first').remove();
      freguesiaSelect.find('option').not(':first').remove();
    } else {
      // Load spinner
      $('#overlay').fadeIn();
      $.ajax({
        type: "GET",
        url: window.env_basepath + "admin/getConcelhos/" + distritoSelect.val(),
        contentType: false,
        processData: false,
        success: function success(response) {
          // Update select boxes
          storeManager.updateCaopSelect(concelhoSelect, true, response["concelhos"], "");
          freguesiaSelect.find('option').not(':first').remove(); // Unload spinner

          $('#overlay').fadeOut();
        }
      });
    }
  },
  updateConcelho: function updateConcelho(featureCoordField) {
    // Clear previously present stores
    storeManager.resetLayer(false, featureCoordField);
    var concelhoSelect = $('#concelho');
    var freguesiaSelect = $('#freguesia');

    if (concelhoSelect.val() == "") {
      freguesiaSelect.find('option').not(':first').remove();
    } else {
      // Load spinner
      $('#overlay').fadeIn();
      $.ajax({
        type: "GET",
        url: window.env_basepath + "admin/getFreguesias/" + concelhoSelect.val(),
        contentType: false,
        processData: false,
        success: function success(response) {
          // Update select boxes
          storeManager.updateCaopSelect(freguesiaSelect, true, response["freguesias"], ""); // Unload spinner

          $('#overlay').fadeOut();
        }
      });
    }
  },
  resetLayer: function resetLayer(newCoord, featureCoordField) {
    window.olMap.mapObj.getLayers().forEach(function (layer) {
      if (_typeof(layer.values_) != undefined && layer.values_.title === 'stores') {
        layer.getSource().clear();

        if (newCoord) {
          window.olMap.addFeature(layer, newCoord.coordinate);
          featureCoordField.val(newCoord.coordinate);
        } else {
          featureCoordField.val("");
        }
      }
    });
  },
  setCaopFromCoord: function setCaopFromCoord(e, featureCoordField) {
    console.log("Changing"); // Load spinner

    $('#overlay').fadeIn();
    console.log(e); // Clear previously present stores

    storeManager.resetLayer(e, featureCoordField);
    console.log(window.env_basepath); // GET ADDRESS
    // Coords of click is evt.coordinate

    console.log("evt.coordinate: " + e.coordinate); // You must transform the coordinates because evt.coordinate 
    // is by default Web Mercator (EPSG:3857) 
    // and not "usual coords" (EPSG:4326) 

    var coords_click = window.olMap.transform(e.coordinate, 'EPSG:3763', 'EPSG:4326'); // console.log("Mouse Click coordinates: " + coords_click);
    // MOUSE CLICK: Longitude

    var lon = coords_click[0]; // MOUSE CLICK: Latitude

    var lat = coords_click[1]; // DATA to put in NOMINATIM URL to find address of mouse click location

    var data_for_url = {
      lon: lon,
      lat: lat,
      format: "json",
      limit: 1
    }; // ENCODED DATA for URL

    var encoded_data = Object.keys(data_for_url).map(function (k) {
      return encodeURIComponent(k) + '=' + encodeURIComponent(data_for_url[k]);
    }).join('&'); // FULL URL for searching address of mouse click

    var url_nominatim = 'https://nominatim.openstreetmap.org/reverse?' + encoded_data;
    $.ajax({
      type: "GET",
      url: url_nominatim,
      success: function success(response_text) {
        // JSON Data of the response to the request Nominatim
        var data_json = response_text; // All the informations of the address are here

        var res_address = data_json.address;

        if (res_address.road != '') {
          $('#address').val(res_address.road);
        } // Query new caop


        $.ajax({
          type: "GET",
          url: window.env_basepath + "admin/getDicofre/" + e.coordinate[0] + "," + e.coordinate[1],
          contentType: false,
          processData: false,
          success: function success(response) {
            // Update select boxes
            storeManager.updateCaop(response); // Unload spinner

            $('#overlay').fadeOut();
          },
          error: function error(xhr, _error) {
            // Unload spinner
            $('#overlay').fadeOut();
            $('#' + storeManager.modalId + ' .modal-header').after('<div class="alert alert-danger alert-block">\
                        <a class="close" data-dismiss="alert" href="#">×</a>\
                        Erro ao obter área administrativa na localização escolhida.\
                        </div>');
          }
        });
      },
      error: function error(xhr, _error2) {
        // Query new caop
        $.ajax({
          type: "GET",
          url: window.env_basepath + "admin/getDicofre/" + e.coordinate[0] + "," + e.coordinate[1],
          contentType: false,
          processData: false,
          success: function success(response) {
            // Update select boxes
            storeManager.updateCaop(response); // Unload spinner

            $('#overlay').fadeOut();
          },
          error: function error(xhr, _error3) {
            // Unload spinner
            $('#overlay').fadeOut();
            $('#' + storeManager.modalId + ' .modal-header').after('<div class="alert alert-danger alert-block">\
                        <a class="close" data-dismiss="alert" href="#">×</a>\
                        Erro ao obter área administrativa na localização escolhida.\
                        </div>');
          }
        });
      }
    });
  },
  validate: function validate() {
    // Clear error styles
    storeManager.storeForm.find('input, select').removeClass('is-invalid'); // Init Validation

    storeManager.isValid = true;
    storeManager.storeErrorMessageList = [];

    var _token = $('input[name="_token"]').val(); // Validate Required


    storeManager.storeForm.find('input[required], select[required]').each(function (i, el) {
      var field = $(el);

      if (field.val() == "") {
        field.addClass('is-invalid');
        storeManager.isValid = false;
        storeManager.storeErrorMessageList.push("Deve preencher " + field.data('desc') + ".");
      }
    }); // Chain

    if (!storeManager.validChain) {
      storeManager.chainField.addClass('is-invalid');
      storeManager.isValid = false;
      storeManager.storeErrorMessageList.push("Cadeia inválida.");
    } // Validate Unique Fields


    if ($('#name').val() != "" && storeManager.validChain) {
      if ($('#name').val() != storeManager.originalName || $('#chain').val() != storeManager.originalChain) {
        // Unique name + chain validation
        $.ajax({
          url: window.env_basepath + "admin/stores/validateName",
          method: "POST",
          data: {
            name: $('#name').val(),
            chain: $('#chain').val(),
            _token: _token
          },
          success: function success(data) {
            // If name and brand unique key exists
            if (data) {
              $('#name').addClass('is-invalid');
              storeManager.isValid = false;
              storeManager.storeErrorMessageList.push("Nome de loja já existe.");
              storeManager.showErrors();
            } else if (storeManager.isValid) {
              storeManager.validateLogic();
            } else {
              storeManager.showErrors();
            }
          },
          error: function error(xhr, _error4) {
            _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].removeFlash('#' + storeManager.modalId + ' .modal-body');
            _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].addFlash('danger', '#' + storeManager.modalId + ' .modal-body', 'Erro ao obter informações de nome.');
          }
        });
      } else {
        storeManager.validateLogic();
      }
    } else {
      // Set error if form is invalid
      storeManager.showErrors();
    }
  },
  validateLogic: function validateLogic() {
    // Submit form if valid
    if (storeManager.isValid) {
      storeManager.storeForm.submit();
    } else {
      storeManager.showErrors();
    }
  },
  showErrors: function showErrors() {
    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].removeFlash('#' + storeManager.modalId + ' .modal-body');

    if (!storeManager.isValid) {
      for (var i = storeManager.storeErrorMessageList.length - 1; i >= 0; i--) {
        _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].addFlash('danger', '#' + storeManager.modalId + ' .modal-body', storeManager.storeErrorMessageList[i]);
      }
    }
  }
};
window.storeManager = storeManager;
$('document').ready(function () {
  $(document).on('click', '#submit-store', function () {
    storeManager.validate();
  }); // On clicking edit store button

  $('.editStore').on('click', function () {
    // Prepare store data
    var this_id = $(this).attr('data-id');
    var this_coord = $(this).attr('data-coord');
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
          storeManager.init();
          storeManager.modalId = 'newRecordModal'; // Display store map

          var map = window.olMap.display();
          var layer = window.olMap.addLayer('stores', map); // Events
          // Update CAOP Select boxes

          map.addEventListener('click', function (event) {
            return function (featureCoordField) {
              storeManager.setCaopFromCoord(event, featureCoordField);
            }($('#location'));
          });
          $('#distrito').on('change', function () {
            storeManager.updateDistrito($('#location'));
          });
          $('#concelho').on('change', function () {
            storeManager.updateConcelho($('#location'));
          });
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
          storeManager.init();
          storeManager.modalId = 'editRecordModal'; // Mark as validation screen

          if (window.location.href.indexOf("validation") > -1) $('#validation').val("true"); // Display store map

          var map = window.olMap.display();
          var point = this_coord;
          var coords = point.replace('POINT', '').replace('(', '').replace(')', '').split(" ");
          var layer = window.olMap.addLayer('stores', map, coords); // Events
          // Update CAOP Select boxes

          map.addEventListener('click', function (event) {
            return function (featureCoordField) {
              storeManager.setCaopFromCoord(event, featureCoordField);
            }($('#location'));
          });
          $('#distrito').on('change', function () {
            storeManager.updateDistrito($('#location'));
          });
          $('#concelho').on('change', function () {
            storeManager.updateConcelho($('#location'));
          });
        });
        $('#editRecordModal').on('hidden.bs.modal', function () {
          $('#editRecordModal .modal-body').data('');
        });
      });
    } // For the edit button


    if (this_action == 'delete') {
      // Get the modal screen to load
      $.get(page + "/loadModalDelete" + "/" + this_id, function (data) {
        $('#deleteRecordModal').modal();
        $('#deleteRecordModal').on('shown.bs.modal', function () {
          // Load modal screen
          $('#deleteRecordModal .load_modal').html(data);
          chainManager.init();
          chainManager.modalId = 'deleteRecordModal'; // Mark as validation screen

          if (window.location.href.indexOf("validation") > -1) $('#validation').val("true");
        });
        $('#deleteRecordModal').on('hidden.bs.modal', function () {
          $('#deleteRecordModal .modal-body').data('');
        });
      });
    }
  });
  /* Autocomplete Chain */

  $(document).on('input', '#chain', function () {
    console.log("INPUT");
    storeManager.validChain = false;
    var query = $(this).val();

    if (query != '') {
      var _token = $('input[name="_token"]').val();

      $.ajax({
        url: window.env_basepath + "admin/chains/fetch",
        method: "POST",
        data: {
          query: query,
          _token: _token
        },
        success: function success(data) {
          $('#chainList').fadeIn();
          $('#chainList').html(data);
        }
      });
    } else {
      storeManager.validChain = true;
    }
  });
  $(document).on('click', 'li.suggestion', function () {
    $('#chain').val($(this).text());
    storeManager.validChain = true;
    $('#chainList').fadeOut();
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

/***/ 2:
/*!*******************************************!*\
  !*** multi ./resources/js/admin/store.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\mundovegano\resources\js\admin\store.js */"./resources/js/admin/store.js");


/***/ })

/******/ });