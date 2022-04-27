(self["webpackChunk"] = self["webpackChunk"] || []).push([["question"],{

/***/ "./assets/controllers/question.js":
/*!****************************************!*\
  !*** ./assets/controllers/question.js ***!
  \****************************************/
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

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/controllers/question.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicXVlc3Rpb24uanMiLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7QUFBQUEsTUFBTSxDQUFDLFlBQVk7QUFDZixNQUFNQyxNQUFNLEdBQUdDLENBQUMsQ0FBQyxpQkFBRCxDQUFoQjtBQUNBLE1BQUlDLGFBQWEsR0FBR0YsTUFBTSxDQUFDRyxHQUFQLEdBQWFDLFdBQWIsRUFBcEI7QUFDQUosRUFBQUEsTUFBTSxDQUFDSyxFQUFQLENBQVUsUUFBVixFQUFvQixZQUFZO0FBQzVCSixJQUFBQSxDQUFDLENBQUMsTUFBTUMsYUFBUCxDQUFELENBQXVCSSxJQUF2QixDQUE0QixRQUE1QixFQUFzQyxJQUF0QztBQUNBTCxJQUFBQSxDQUFDLENBQUMsTUFBTUQsTUFBTSxDQUFDRyxHQUFQLEdBQWFDLFdBQWIsRUFBUCxDQUFELENBQW9DRSxJQUFwQyxDQUF5QyxRQUF6QyxFQUFtRCxLQUFuRDtBQUNBSixJQUFBQSxhQUFhLEdBQUdGLE1BQU0sQ0FBQ0csR0FBUCxHQUFhQyxXQUFiLEVBQWhCO0FBQ0gsR0FKRDtBQUtILENBUkssQ0FBTiIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy9xdWVzdGlvbi5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJqUXVlcnkoZnVuY3Rpb24gKCkge1xyXG4gICAgY29uc3Qgc2VsZWN0ID0gJCgnI3NlbGVjdEV4Y2hhbmdlJyk7XHJcbiAgICBsZXQgZmlyc3RFeGNoYW5nZSA9IHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpO1xyXG4gICAgc2VsZWN0Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgJCgnIycgKyBmaXJzdEV4Y2hhbmdlKS5hdHRyKCdoaWRkZW4nLCB0cnVlKTtcclxuICAgICAgICAkKCcjJyArIHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpKS5hdHRyKCdoaWRkZW4nLCBmYWxzZSk7XHJcbiAgICAgICAgZmlyc3RFeGNoYW5nZSA9IHNlbGVjdC52YWwoKS50b0xvd2VyQ2FzZSgpO1xyXG4gICAgfSlcclxufSkiXSwibmFtZXMiOlsialF1ZXJ5Iiwic2VsZWN0IiwiJCIsImZpcnN0RXhjaGFuZ2UiLCJ2YWwiLCJ0b0xvd2VyQ2FzZSIsIm9uIiwiYXR0ciJdLCJzb3VyY2VSb290IjoiIn0=