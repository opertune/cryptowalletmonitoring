(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$":
/*!****************************************************************************************************************!*\
  !*** ./assets/controllers/ sync ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \.[jt]sx?$ ***!
  \****************************************************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./navbar.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/navbar.js",
	"./question.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/question.js",
	"./wallet.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/wallet.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$";

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json":
/*!************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
});

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/navbar.js":
/*!********************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/navbar.js ***!
  \********************************************************************************************************/
/***/ (() => {

var navbar = $('nav.navbar');
var navbarCollapse = $('.navbar-collapse'); // Adding classes to indicate the navbar state

navbarCollapse.on('show.bs.collapse', function () {
  navbar.addClass('expanded'); // navbar button style

  $('#logoutButton').removeClass();
  $('#logoutButton').addClass('nav-link');
  $('#loginButton').removeClass();
  $('#loginButton').addClass('nav-link');
  $('#registerButton').removeClass();
  $('#registerButton').addClass('nav-link');
});
navbarCollapse.on('hide.bs.collapse', function () {
  navbar.removeClass('expanded'); // navbar button style

  $('#logoutButton').removeClass();
  $('#logoutButton').addClass('btn btn-danger');
  $('#loginButton').removeClass();
  $('#loginButton').addClass('btn btn-danger me-2');
  $('#registerButton').removeClass();
  $('#registerButton').addClass('btn btn-primary');
});

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/question.js":
/*!**********************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/question.js ***!
  \**********************************************************************************************************/
/***/ (() => {

jQuery(function () {
  var select = $('#selectExchange');
  var firstExchange = select.val().toLowerCase();
  select.on('change', function () {
    $('#' + firstExchange).attr('hidden', true);
    $('#' + select.val().toLowerCase()).attr('hidden', false);
    firstExchange = select.val().toLowerCase();
  });
});

/***/ }),

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/wallet.js":
/*!********************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/wallet.js ***!
  \********************************************************************************************************/
/***/ (() => {

$('#add_wallet_name').on('change', function () {
  if ($('#add_wallet_name').val() == 'Kucoin') {
    $('#passPhraseID').show();
  } else {
    $('#apiKey').show();
    $('#secretKey').show();
    $('#passPhraseID').hide();
    $('#add_wallet_passPhrase').prop("required", false);
  }

  if ($('#add_wallet_name').val() == 'Coinbase') {
    $('#coinbaseOauth').show();
    $('#apiKey').hide();
    $('#secretKey').hide();
    $('#add_wallet_apiKey').prop("required", false);
    $('#add_wallet_secretKey').prop("required", false);
  } else {
    $('#coinbaseOauth').hide();
  }
}); // on click increase/decrease max height and set/unset overflow for expended div

var expended = false;
$('.customButton').on('click', function () {
  // extend div
  if (expended) {
    expended = false;
    $('#table' + this.id).css({
      "max-height": "200px",
      "overflow-y": "hidden"
    });
    $('#rotateSVG' + this.id).css("transform", "rotate(0deg)");
  } else {
    // collapse div
    expended = true;
    $('#table' + this.id).css({
      "overflow-y": "none",
      "max-height": $(document).height() + "px"
    });
    $('#rotateSVG' + this.id).css("transform", "rotate(-90deg)");
  }
}); // on hover collapse button background color and rotate arrow

$('.customButton').on('mouseenter', function () {
  // Rotate on mouse enter with expanded check
  expended ? $('#rotateSVG' + this.id).css({
    "transform": "rotate(0deg)"
  }) : $('#rotateSVG' + this.id).css({
    "transform": "rotate(-90deg)"
  }); // Background color on mouse enter

  $('#' + this.id).css("background-color", "#404040");
}).on('mouseleave', function () {
  // Reset rotate on mouse enter with expanded check
  expended ? $('#rotateSVG' + this.id).css({
    "transform": "rotate(-90deg)",
    "background-color": "transparent"
  }) : $('#rotateSVG' + this.id).css({
    "transform": "rotate(0deg)",
    "background-color": "transparent"
  }); // Default background color on mouse leave

  $('#' + this.id).css("background-color", "transparent");
}); // Hide expand button when table have less than 3 rows

jQuery(function ($) {
  $('.walletCustomTable').each(function (index) {
    if ($('#t' + index + ' tr').length <= 5) {
      $('#' + index).hide();
    }
  });
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

/***/ }),

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./bootstrap */ "./assets/bootstrap.js");
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.esm.js");
/* harmony import */ var _controllers_navbar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./controllers/navbar */ "./assets/controllers/navbar.js");
/* harmony import */ var _controllers_navbar__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_controllers_navbar__WEBPACK_IMPORTED_MODULE_3__);
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// any CSS you import will output into a single css file (app.css in this case)
 // start the Stimulus application

 // import bootstrap javascript

 // Custom js



/***/ }),

/***/ "./assets/bootstrap.js":
/*!*****************************!*\
  !*** ./assets/bootstrap.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "app": () => (/* binding */ app)
/* harmony export */ });
/* harmony import */ var _symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @symfony/stimulus-bridge */ "./node_modules/@symfony/stimulus-bridge/dist/index.js");
 // Registers Stimulus controllers from controllers.json and in the controllers/ directory

var app = (0,_symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__.startStimulusApp)(__webpack_require__("./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$")); // register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

/***/ }),

/***/ "./assets/controllers/navbar.js":
/*!**************************************!*\
  !*** ./assets/controllers/navbar.js ***!
  \**************************************/
/***/ (() => {

var navbar = $('nav.navbar');
var navbarCollapse = $('.navbar-collapse'); // Adding classes to indicate the navbar state

navbarCollapse.on('show.bs.collapse', function () {
  navbar.addClass('expanded'); // navbar button style

  $('#logoutButton').removeClass();
  $('#logoutButton').addClass('nav-link');
  $('#loginButton').removeClass();
  $('#loginButton').addClass('nav-link');
  $('#registerButton').removeClass();
  $('#registerButton').addClass('nav-link');
});
navbarCollapse.on('hide.bs.collapse', function () {
  navbar.removeClass('expanded'); // navbar button style

  $('#logoutButton').removeClass();
  $('#logoutButton').addClass('btn btn-danger');
  $('#loginButton').removeClass();
  $('#loginButton').addClass('btn btn-danger me-2');
  $('#registerButton').removeClass();
  $('#registerButton').addClass('btn btn-primary');
});

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_symfony_stimulus-bridge_dist_index_js-node_modules_bootstrap_dist_js_boo-da594f"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUQsQ0FBRCxDQUFtQkksV0FBbkI7QUFDQUosRUFBQUEsQ0FBQyxDQUFDLGVBQUQsQ0FBRCxDQUFtQkcsUUFBbkIsQ0FBNEIsVUFBNUI7QUFFQUgsRUFBQUEsQ0FBQyxDQUFDLGNBQUQsQ0FBRCxDQUFrQkksV0FBbEI7QUFDQUosRUFBQUEsQ0FBQyxDQUFDLGNBQUQsQ0FBRCxDQUFrQkcsUUFBbEIsQ0FBMkIsVUFBM0I7QUFFQUgsRUFBQUEsQ0FBQyxDQUFDLGlCQUFELENBQUQsQ0FBcUJJLFdBQXJCO0FBQ0FKLEVBQUFBLENBQUMsQ0FBQyxpQkFBRCxDQUFELENBQXFCRyxRQUFyQixDQUE4QixVQUE5QjtBQUNILENBWkQ7QUFjQUYsY0FBYyxDQUFDQyxFQUFmLENBQWtCLGtCQUFsQixFQUFzQyxZQUFZO0FBQzlDSCxFQUFBQSxNQUFNLENBQUNLLFdBQVAsQ0FBbUIsVUFBbkIsRUFEOEMsQ0FHOUM7O0FBQ0FKLEVBQUFBLENBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJJLFdBQW5CO0FBQ0FKLEVBQUFBLENBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJHLFFBQW5CLENBQTRCLGdCQUE1QjtBQUVBSCxFQUFBQSxDQUFDLENBQUMsY0FBRCxDQUFELENBQWtCSSxXQUFsQjtBQUNBSixFQUFBQSxDQUFDLENBQUMsY0FBRCxDQUFELENBQWtCRyxRQUFsQixDQUEyQixxQkFBM0I7QUFFQUgsRUFBQUEsQ0FBQyxDQUFDLGlCQUFELENBQUQsQ0FBcUJJLFdBQXJCO0FBQ0FKLEVBQUFBLENBQUMsQ0FBQyxpQkFBRCxDQUFELENBQXFCRyxRQUFyQixDQUE4QixpQkFBOUI7QUFDSCxDQVpEOzs7Ozs7Ozs7Ozs7QUtsQkEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vIFxcLltqdF1zeCIsIndlYnBhY2s6Ly8vLi9hc3NldHMvY29udHJvbGxlcnMuanNvbiIsIndlYnBhY2s6Ly8vLi9hc3NldHMvY29udHJvbGxlcnMvbmF2YmFyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy9xdWVzdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvY29udHJvbGxlcnMvd2FsbGV0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Jvb3RzdHJhcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvc3R5bGVzL2FwcC5zY3NzIl0sInNvdXJjZXNDb250ZW50IjpbInZhciBtYXAgPSB7XG5cdFwiLi9uYXZiYXIuanNcIjogXCIuL25vZGVfbW9kdWxlcy9Ac3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlci5qcyEuL2Fzc2V0cy9jb250cm9sbGVycy9uYXZiYXIuanNcIixcblx0XCIuL3F1ZXN0aW9uLmpzXCI6IFwiLi9ub2RlX21vZHVsZXMvQHN5bWZvbnkvc3RpbXVsdXMtYnJpZGdlL2xhenktY29udHJvbGxlci1sb2FkZXIuanMhLi9hc3NldHMvY29udHJvbGxlcnMvcXVlc3Rpb24uanNcIixcblx0XCIuL3dhbGxldC5qc1wiOiBcIi4vbm9kZV9tb2R1bGVzL0BzeW1mb255L3N0aW11bHVzLWJyaWRnZS9sYXp5LWNvbnRyb2xsZXItbG9hZGVyLmpzIS4vYXNzZXRzL2NvbnRyb2xsZXJzL3dhbGxldC5qc1wiXG59O1xuXG5cbmZ1bmN0aW9uIHdlYnBhY2tDb250ZXh0KHJlcSkge1xuXHR2YXIgaWQgPSB3ZWJwYWNrQ29udGV4dFJlc29sdmUocmVxKTtcblx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oaWQpO1xufVxuZnVuY3Rpb24gd2VicGFja0NvbnRleHRSZXNvbHZlKHJlcSkge1xuXHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKG1hcCwgcmVxKSkge1xuXHRcdHZhciBlID0gbmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIiArIHJlcSArIFwiJ1wiKTtcblx0XHRlLmNvZGUgPSAnTU9EVUxFX05PVF9GT1VORCc7XG5cdFx0dGhyb3cgZTtcblx0fVxuXHRyZXR1cm4gbWFwW3JlcV07XG59XG53ZWJwYWNrQ29udGV4dC5rZXlzID0gZnVuY3Rpb24gd2VicGFja0NvbnRleHRLZXlzKCkge1xuXHRyZXR1cm4gT2JqZWN0LmtleXMobWFwKTtcbn07XG53ZWJwYWNrQ29udGV4dC5yZXNvbHZlID0gd2VicGFja0NvbnRleHRSZXNvbHZlO1xubW9kdWxlLmV4cG9ydHMgPSB3ZWJwYWNrQ29udGV4dDtcbndlYnBhY2tDb250ZXh0LmlkID0gXCIuL2Fzc2V0cy9jb250cm9sbGVycyBzeW5jIHJlY3Vyc2l2ZSAuL25vZGVfbW9kdWxlcy9Ac3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlci5qcyEgXFxcXC5banRdc3g/JFwiOyIsImV4cG9ydCBkZWZhdWx0IHtcbn07IiwiY29uc3QgbmF2YmFyID0gJCgnbmF2Lm5hdmJhcicpO1xyXG5jb25zdCBuYXZiYXJDb2xsYXBzZSA9ICQoJy5uYXZiYXItY29sbGFwc2UnKTtcclxuXHJcbi8vIEFkZGluZyBjbGFzc2VzIHRvIGluZGljYXRlIHRoZSBuYXZiYXIgc3RhdGVcclxubmF2YmFyQ29sbGFwc2Uub24oJ3Nob3cuYnMuY29sbGFwc2UnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICBuYXZiYXIuYWRkQ2xhc3MoJ2V4cGFuZGVkJyk7XHJcblxyXG4gICAgLy8gbmF2YmFyIGJ1dHRvbiBzdHlsZVxyXG4gICAgJCgnI2xvZ291dEJ1dHRvbicpLnJlbW92ZUNsYXNzKCk7XHJcbiAgICAkKCcjbG9nb3V0QnV0dG9uJykuYWRkQ2xhc3MoJ25hdi1saW5rJyk7XHJcblxyXG4gICAgJCgnI2xvZ2luQnV0dG9uJykucmVtb3ZlQ2xhc3MoKTtcclxuICAgICQoJyNsb2dpbkJ1dHRvbicpLmFkZENsYXNzKCduYXYtbGluaycpO1xyXG5cclxuICAgICQoJyNyZWdpc3RlckJ1dHRvbicpLnJlbW92ZUNsYXNzKCk7XHJcbiAgICAkKCcjcmVnaXN0ZXJCdXR0b24nKS5hZGRDbGFzcygnbmF2LWxpbmsnKTtcclxufSk7XHJcblxyXG5uYXZiYXJDb2xsYXBzZS5vbignaGlkZS5icy5jb2xsYXBzZScsIGZ1bmN0aW9uICgpIHtcclxuICAgIG5hdmJhci5yZW1vdmVDbGFzcygnZXhwYW5kZWQnKTtcclxuXHJcbiAgICAvLyBuYXZiYXIgYnV0dG9uIHN0eWxlXHJcbiAgICAkKCcjbG9nb3V0QnV0dG9uJykucmVtb3ZlQ2xhc3MoKTtcclxuICAgICQoJyNsb2dvdXRCdXR0b24nKS5hZGRDbGFzcygnYnRuIGJ0bi1kYW5nZXInKTtcclxuXHJcbiAgICAkKCcjbG9naW5CdXR0b24nKS5yZW1vdmVDbGFzcygpO1xyXG4gICAgJCgnI2xvZ2luQnV0dG9uJykuYWRkQ2xhc3MoJ2J0biBidG4tZGFuZ2VyIG1lLTInKTtcclxuXHJcbiAgICAkKCcjcmVnaXN0ZXJCdXR0b24nKS5yZW1vdmVDbGFzcygpO1xyXG4gICAgJCgnI3JlZ2lzdGVyQnV0dG9uJykuYWRkQ2xhc3MoJ2J0biBidG4tcHJpbWFyeScpO1xyXG59KTsiLCJqUXVlcnkoZnVuY3Rpb24gKCkge1xyXG4gICAgY29uc3Qgc2VsZWN0ID0gJCgnI3NlbGVjdEV4Y2hhbmdlJyk7XHJcbiAgICBsZXQgZmlyc3RFeGNoYW5nZSA9IHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpO1xyXG4gICAgc2VsZWN0Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgJCgnIycgKyBmaXJzdEV4Y2hhbmdlKS5hdHRyKCdoaWRkZW4nLCB0cnVlKTtcclxuICAgICAgICAkKCcjJyArIHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpKS5hdHRyKCdoaWRkZW4nLCBmYWxzZSk7XHJcbiAgICAgICAgZmlyc3RFeGNoYW5nZSA9IHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpO1xyXG4gICAgfSlcclxufSkiLCIkKCcjYWRkX3dhbGxldF9uYW1lJykub24oJ2NoYW5nZScsIGZ1bmN0aW9uICgpIHtcclxuICAgIGlmICgkKCcjYWRkX3dhbGxldF9uYW1lJykudmFsKCkgPT0gJ0t1Y29pbicpIHtcclxuICAgICAgICAkKCcjcGFzc1BocmFzZUlEJykuc2hvdygpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICAkKCcjYXBpS2V5Jykuc2hvdygpO1xyXG4gICAgICAgICQoJyNzZWNyZXRLZXknKS5zaG93KCk7XHJcbiAgICAgICAgJCgnI3Bhc3NQaHJhc2VJRCcpLmhpZGUoKTtcclxuICAgICAgICAkKCcjYWRkX3dhbGxldF9wYXNzUGhyYXNlJykucHJvcChcInJlcXVpcmVkXCIsIGZhbHNlKTtcclxuICAgIH1cclxuXHJcbiAgICBpZiAoJCgnI2FkZF93YWxsZXRfbmFtZScpLnZhbCgpID09ICdDb2luYmFzZScpIHtcclxuICAgICAgICAkKCcjY29pbmJhc2VPYXV0aCcpLnNob3coKTtcclxuICAgICAgICAkKCcjYXBpS2V5JykuaGlkZSgpO1xyXG4gICAgICAgICQoJyNzZWNyZXRLZXknKS5oaWRlKCk7XHJcbiAgICAgICAgJCgnI2FkZF93YWxsZXRfYXBpS2V5JykucHJvcChcInJlcXVpcmVkXCIsIGZhbHNlKTtcclxuICAgICAgICAkKCcjYWRkX3dhbGxldF9zZWNyZXRLZXknKS5wcm9wKFwicmVxdWlyZWRcIiwgZmFsc2UpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICAkKCcjY29pbmJhc2VPYXV0aCcpLmhpZGUoKTtcclxuICAgIH1cclxufSlcclxuXHJcbi8vIG9uIGNsaWNrIGluY3JlYXNlL2RlY3JlYXNlIG1heCBoZWlnaHQgYW5kIHNldC91bnNldCBvdmVyZmxvdyBmb3IgZXhwZW5kZWQgZGl2XHJcbmxldCBleHBlbmRlZCA9IGZhbHNlO1xyXG4kKCcuY3VzdG9tQnV0dG9uJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG4gICAgLy8gZXh0ZW5kIGRpdlxyXG4gICAgaWYgKGV4cGVuZGVkKSB7XHJcbiAgICAgICAgZXhwZW5kZWQgPSBmYWxzZTtcclxuICAgICAgICAkKCcjdGFibGUnICsgdGhpcy5pZCkuY3NzKHsgXCJtYXgtaGVpZ2h0XCI6IFwiMjAwcHhcIiwgXCJvdmVyZmxvdy15XCI6IFwiaGlkZGVuXCIgfSk7XHJcbiAgICAgICAgJCgnI3JvdGF0ZVNWRycgKyB0aGlzLmlkKS5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJyb3RhdGUoMGRlZylcIik7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgIC8vIGNvbGxhcHNlIGRpdlxyXG4gICAgICAgIGV4cGVuZGVkID0gdHJ1ZTtcclxuICAgICAgICAkKCcjdGFibGUnICsgdGhpcy5pZCkuY3NzKHsgXCJvdmVyZmxvdy15XCI6IFwibm9uZVwiLCBcIm1heC1oZWlnaHRcIjogJChkb2N1bWVudCkuaGVpZ2h0KCkgKyBcInB4XCIgfSk7XHJcbiAgICAgICAgJCgnI3JvdGF0ZVNWRycgKyB0aGlzLmlkKS5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJyb3RhdGUoLTkwZGVnKVwiKTtcclxuICAgIH1cclxufSlcclxuXHJcbi8vIG9uIGhvdmVyIGNvbGxhcHNlIGJ1dHRvbiBiYWNrZ3JvdW5kIGNvbG9yIGFuZCByb3RhdGUgYXJyb3dcclxuJCgnLmN1c3RvbUJ1dHRvbicpLm9uKCdtb3VzZWVudGVyJywgZnVuY3Rpb24gKCkge1xyXG4gICAgLy8gUm90YXRlIG9uIG1vdXNlIGVudGVyIHdpdGggZXhwYW5kZWQgY2hlY2tcclxuICAgIGV4cGVuZGVkID8gJCgnI3JvdGF0ZVNWRycgKyB0aGlzLmlkKS5jc3MoeyBcInRyYW5zZm9ybVwiOiBcInJvdGF0ZSgwZGVnKVwiIH0pIDogJCgnI3JvdGF0ZVNWRycgKyB0aGlzLmlkKS5jc3MoeyBcInRyYW5zZm9ybVwiOiBcInJvdGF0ZSgtOTBkZWcpXCIgfSk7XHJcbiAgICAvLyBCYWNrZ3JvdW5kIGNvbG9yIG9uIG1vdXNlIGVudGVyXHJcbiAgICAkKCcjJyArIHRoaXMuaWQpLmNzcyhcImJhY2tncm91bmQtY29sb3JcIiwgXCIjNDA0MDQwXCIpO1xyXG59KS5vbignbW91c2VsZWF2ZScsIGZ1bmN0aW9uICgpIHtcclxuICAgIC8vIFJlc2V0IHJvdGF0ZSBvbiBtb3VzZSBlbnRlciB3aXRoIGV4cGFuZGVkIGNoZWNrXHJcbiAgICBleHBlbmRlZCA/ICQoJyNyb3RhdGVTVkcnICsgdGhpcy5pZCkuY3NzKHtcclxuICAgICAgICBcInRyYW5zZm9ybVwiOiBcInJvdGF0ZSgtOTBkZWcpXCIsXHJcbiAgICAgICAgXCJiYWNrZ3JvdW5kLWNvbG9yXCI6IFwidHJhbnNwYXJlbnRcIlxyXG4gICAgfSkgOiAkKCcjcm90YXRlU1ZHJyArIHRoaXMuaWQpLmNzcyh7XHJcbiAgICAgICAgXCJ0cmFuc2Zvcm1cIjogXCJyb3RhdGUoMGRlZylcIixcclxuICAgICAgICBcImJhY2tncm91bmQtY29sb3JcIjogXCJ0cmFuc3BhcmVudFwiXHJcbiAgICB9KTtcclxuICAgIC8vIERlZmF1bHQgYmFja2dyb3VuZCBjb2xvciBvbiBtb3VzZSBsZWF2ZVxyXG4gICAgJCgnIycgKyB0aGlzLmlkKS5jc3MoXCJiYWNrZ3JvdW5kLWNvbG9yXCIsIFwidHJhbnNwYXJlbnRcIik7XHJcbn0pO1xyXG5cclxuLy8gSGlkZSBleHBhbmQgYnV0dG9uIHdoZW4gdGFibGUgaGF2ZSBsZXNzIHRoYW4gMyByb3dzXHJcbmpRdWVyeShmdW5jdGlvbiAoJCkge1xyXG4gICAgJCgnLndhbGxldEN1c3RvbVRhYmxlJykuZWFjaChmdW5jdGlvbiAoaW5kZXgpIHtcclxuICAgICAgICBpZiAoJCgnI3QnICsgaW5kZXggKyAnIHRyJykubGVuZ3RoIDw9IDUpIHtcclxuICAgICAgICAgICAgJCgnIycgKyBpbmRleCkuaGlkZSgpO1xyXG4gICAgICAgIH1cclxuICAgIH0pXHJcbn0pXHJcblxyXG4kKGZ1bmN0aW9uICgpIHtcclxuICAgICQoJ1tkYXRhLXRvZ2dsZT1cInRvb2x0aXBcIl0nKS50b29sdGlwKCk7XHJcbn0pO1xyXG4iLCIvKlxuICogV2VsY29tZSB0byB5b3VyIGFwcCdzIG1haW4gSmF2YVNjcmlwdCBmaWxlIVxuICpcbiAqIFdlIHJlY29tbWVuZCBpbmNsdWRpbmcgdGhlIGJ1aWx0IHZlcnNpb24gb2YgdGhpcyBKYXZhU2NyaXB0IGZpbGVcbiAqIChhbmQgaXRzIENTUyBmaWxlKSBpbiB5b3VyIGJhc2UgbGF5b3V0IChiYXNlLmh0bWwudHdpZykuXG4gKi9cblxuLy8gYW55IENTUyB5b3UgaW1wb3J0IHdpbGwgb3V0cHV0IGludG8gYSBzaW5nbGUgY3NzIGZpbGUgKGFwcC5jc3MgaW4gdGhpcyBjYXNlKVxuaW1wb3J0ICcuL3N0eWxlcy9hcHAuc2Nzcyc7XG5cbi8vIHN0YXJ0IHRoZSBTdGltdWx1cyBhcHBsaWNhdGlvblxuaW1wb3J0ICcuL2Jvb3RzdHJhcCc7XG5cbi8vIGltcG9ydCBib290c3RyYXAgamF2YXNjcmlwdFxuaW1wb3J0IGJvb3RzdHJhcCBmcm9tICdib290c3RyYXAnO1xuXG4vLyBDdXN0b20ganNcbmltcG9ydCAnLi9jb250cm9sbGVycy9uYXZiYXInOyIsImltcG9ydCB7IHN0YXJ0U3RpbXVsdXNBcHAgfSBmcm9tICdAc3ltZm9ueS9zdGltdWx1cy1icmlkZ2UnO1xuXG4vLyBSZWdpc3RlcnMgU3RpbXVsdXMgY29udHJvbGxlcnMgZnJvbSBjb250cm9sbGVycy5qc29uIGFuZCBpbiB0aGUgY29udHJvbGxlcnMvIGRpcmVjdG9yeVxuZXhwb3J0IGNvbnN0IGFwcCA9IHN0YXJ0U3RpbXVsdXNBcHAocmVxdWlyZS5jb250ZXh0KFxuICAgICdAc3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlciEuL2NvbnRyb2xsZXJzJyxcbiAgICB0cnVlLFxuICAgIC9cXC5banRdc3g/JC9cbikpO1xuXG4vLyByZWdpc3RlciBhbnkgY3VzdG9tLCAzcmQgcGFydHkgY29udHJvbGxlcnMgaGVyZVxuLy8gYXBwLnJlZ2lzdGVyKCdzb21lX2NvbnRyb2xsZXJfbmFtZScsIFNvbWVJbXBvcnRlZENvbnRyb2xsZXIpO1xuIiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbIm5hdmJhciIsIiQiLCJuYXZiYXJDb2xsYXBzZSIsIm9uIiwiYWRkQ2xhc3MiLCJyZW1vdmVDbGFzcyIsImpRdWVyeSIsInNlbGVjdCIsImZpcnN0RXhjaGFuZ2UiLCJ2YWwiLCJ0b0xvd2VyQ2FzZSIsImF0dHIiLCJzaG93IiwiaGlkZSIsInByb3AiLCJleHBlbmRlZCIsImlkIiwiY3NzIiwiZG9jdW1lbnQiLCJoZWlnaHQiLCJlYWNoIiwiaW5kZXgiLCJsZW5ndGgiLCJ0b29sdGlwIiwiYm9vdHN0cmFwIiwic3RhcnRTdGltdWx1c0FwcCIsImFwcCIsInJlcXVpcmUiLCJjb250ZXh0Il0sInNvdXJjZVJvb3QiOiIifQ==