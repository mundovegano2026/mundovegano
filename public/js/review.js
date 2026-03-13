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
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/review.js":
/*!**************************************!*\
  !*** ./resources/js/admin/review.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$('document').ready(function () {
  // Review actions
  $(document).on('click', '.review-action', function () {
    var button = $(this);
    var endPoint = button.data('type') == 'up' ? 'upvote' : 'downvote';
    var review_id = button.data('review_id');

    var _token = $('input[name="_token"]').val();

    $('#overlay').fadeIn();
    $.ajax({
      url: window.env_basepath + "admin/reviews/" + endPoint,
      method: "POST",
      data: {
        review_id: review_id,
        _token: _token
      },
      success: function success(data) {
        if (data) {
          button.toggleClass('selected');
          var oppositeButton = button.siblings('.review-action');

          if (oppositeButton.hasClass('selected') && button.hasClass('selected')) {
            oppositeButton.toggleClass('selected');
          }
        }

        $('#overlay').fadeOut();
      },
      error: function error(xhr, _error) {
        utils.addFlash('danger', '#' + productManager.modalId + ' .modal-body', 'Erro ao avaliar ação do utilizador.'); // Unload spinner

        $('#overlay').fadeOut();
      }
    });
  });
  $(document).on('click', '#submit-review', function () {
    $('#review-form').submit();
  }); // On clicking edit chain button

  $('.editReview').on('click', function () {
    // Prepare chain data
    var this_id = $(this).attr('data-id');
    var this_action = $(this).attr('data-action');
    var page = window.location.href.split("?")[0].replace("#", ""); // Clear modal

    $('#editRecordModal .load_modal').html('');
    $('#newRecordModal .load_modal').html(''); // For the edit button

    if (this_action == 'edit') {
      // Get the modal screen to load
      $.get(page + "/loadModal" + "/" + this_id, function (data) {
        $('#editRecordModal').modal();
        $('#editRecordModal').on('shown.bs.modal', function () {
          // Load modal screen
          $('#editRecordModal .load_modal').html(data); // chainManager.init();   
          // chainManager.modalId = 'editRecordModal';   
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
          $('#deleteRecordModal .load_modal').html(data); // productManager.init();   
          // productManager.modalId = 'deleteRecordModal';   
          // Mark as validation screen

          if (window.location.href.indexOf("validation") > -1) $('#validation').val("true");
        });
        $('#deleteRecordModal').on('hidden.bs.modal', function () {
          $('#deleteRecordModal .modal-body').data('');
        });
      });
    }
  });
});

/***/ }),

/***/ 12:
/*!********************************************!*\
  !*** multi ./resources/js/admin/review.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\mundovegano\resources\js\admin\review.js */"./resources/js/admin/review.js");


/***/ })

/******/ });