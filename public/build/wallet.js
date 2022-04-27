(self["webpackChunk"] = self["webpackChunk"] || []).push([["wallet"],{

/***/ "./assets/controllers/wallet.js":
/*!**************************************!*\
  !*** ./assets/controllers/wallet.js ***!
  \**************************************/
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

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/controllers/wallet.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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RCxDQUFpQkMsR0FBakIsQ0FBcUIsa0JBQXJCLEVBQXlDLFNBQXpDO0FBQ0gsQ0FMRCxFQUtHUCxFQUxILENBS00sWUFMTixFQUtvQixZQUFZO0FBQzVCO0FBQ0FLLEVBQUFBLFFBQVEsR0FBR04sQ0FBQyxDQUFDLGVBQWUsS0FBS08sRUFBckIsQ0FBRCxDQUEwQkMsR0FBMUIsQ0FBOEI7QUFDckMsaUJBQWEsZ0JBRHdCO0FBRXJDLHdCQUFvQjtBQUZpQixHQUE5QixDQUFILEdBR0hSLENBQUMsQ0FBQyxlQUFlLEtBQUtPLEVBQXJCLENBQUQsQ0FBMEJDLEdBQTFCLENBQThCO0FBQy9CLGlCQUFhLGNBRGtCO0FBRS9CLHdCQUFvQjtBQUZXLEdBQTlCLENBSEwsQ0FGNEIsQ0FTNUI7O0FBQ0FSLEVBQUFBLENBQUMsQ0FBQyxNQUFNLEtBQUtPLEVBQVosQ0FBRCxDQUFpQkMsR0FBakIsQ0FBcUIsa0JBQXJCLEVBQXlDLGFBQXpDO0FBQ0gsQ0FoQkQsR0FrQkE7O0FBQ0FHLE1BQU0sQ0FBQyxVQUFVWCxDQUFWLEVBQWE7QUFDaEJBLEVBQUFBLENBQUMsQ0FBQyxvQkFBRCxDQUFELENBQXdCWSxJQUF4QixDQUE2QixVQUFVQyxLQUFWLEVBQWlCO0FBQzFDLFFBQUliLENBQUMsQ0FBQyxPQUFPYSxLQUFQLEdBQWUsS0FBaEIsQ0FBRCxDQUF3QkMsTUFBeEIsSUFBa0MsQ0FBdEMsRUFBeUM7QUFDckNkLE1BQUFBLENBQUMsQ0FBQyxNQUFNYSxLQUFQLENBQUQsQ0FBZVQsSUFBZjtBQUNIO0FBQ0osR0FKRDtBQUtILENBTkssQ0FBTjtBQVFBSixDQUFDLENBQUMsWUFBWTtBQUNWQSxFQUFBQSxDQUFDLENBQUMseUJBQUQsQ0FBRCxDQUE2QmUsT0FBN0I7QUFDSCxDQUZBLENBQUQiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvY29udHJvbGxlcnMvd2FsbGV0LmpzIl0sInNvdXJjZXNDb250ZW50IjpbIiQoJyNhZGRfd2FsbGV0X25hbWUnKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xyXG4gICAgaWYgKCQoJyNhZGRfd2FsbGV0X25hbWUnKS52YWwoKSA9PSAnS3Vjb2luJykge1xyXG4gICAgICAgICQoJyNwYXNzUGhyYXNlSUQnKS5zaG93KCk7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgICQoJyNhcGlLZXknKS5zaG93KCk7XHJcbiAgICAgICAgJCgnI3NlY3JldEtleScpLnNob3coKTtcclxuICAgICAgICAkKCcjcGFzc1BocmFzZUlEJykuaGlkZSgpO1xyXG4gICAgICAgICQoJyNhZGRfd2FsbGV0X3Bhc3NQaHJhc2UnKS5wcm9wKFwicmVxdWlyZWRcIiwgZmFsc2UpO1xyXG4gICAgfVxyXG5cclxuICAgIGlmICgkKCcjYWRkX3dhbGxldF9uYW1lJykudmFsKCkgPT0gJ0NvaW5iYXNlJykge1xyXG4gICAgICAgICQoJyNjb2luYmFzZU9hdXRoJykuc2hvdygpO1xyXG4gICAgICAgICQoJyNhcGlLZXknKS5oaWRlKCk7XHJcbiAgICAgICAgJCgnI3NlY3JldEtleScpLmhpZGUoKTtcclxuICAgICAgICAkKCcjYWRkX3dhbGxldF9hcGlLZXknKS5wcm9wKFwicmVxdWlyZWRcIiwgZmFsc2UpO1xyXG4gICAgICAgICQoJyNhZGRfd2FsbGV0X3NlY3JldEtleScpLnByb3AoXCJyZXF1aXJlZFwiLCBmYWxzZSk7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgICQoJyNjb2luYmFzZU9hdXRoJykuaGlkZSgpO1xyXG4gICAgfVxyXG59KVxyXG5cclxuLy8gb24gY2xpY2sgaW5jcmVhc2UvZGVjcmVhc2UgbWF4IGhlaWdodCBhbmQgc2V0L3Vuc2V0IG92ZXJmbG93IGZvciBleHBlbmRlZCBkaXZcclxubGV0IGV4cGVuZGVkID0gZmFsc2U7XHJcbiQoJy5jdXN0b21CdXR0b24nKS5vbignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAvLyBleHRlbmQgZGl2XHJcbiAgICBpZiAoZXhwZW5kZWQpIHtcclxuICAgICAgICBleHBlbmRlZCA9IGZhbHNlO1xyXG4gICAgICAgICQoJyN0YWJsZScgKyB0aGlzLmlkKS5jc3MoeyBcIm1heC1oZWlnaHRcIjogXCIyMDBweFwiLCBcIm92ZXJmbG93LXlcIjogXCJoaWRkZW5cIiB9KTtcclxuICAgICAgICAkKCcjcm90YXRlU1ZHJyArIHRoaXMuaWQpLmNzcyhcInRyYW5zZm9ybVwiLCBcInJvdGF0ZSgwZGVnKVwiKTtcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgICAgLy8gY29sbGFwc2UgZGl2XHJcbiAgICAgICAgZXhwZW5kZWQgPSB0cnVlO1xyXG4gICAgICAgICQoJyN0YWJsZScgKyB0aGlzLmlkKS5jc3MoeyBcIm92ZXJmbG93LXlcIjogXCJub25lXCIsIFwibWF4LWhlaWdodFwiOiAkKGRvY3VtZW50KS5oZWlnaHQoKSArIFwicHhcIiB9KTtcclxuICAgICAgICAkKCcjcm90YXRlU1ZHJyArIHRoaXMuaWQpLmNzcyhcInRyYW5zZm9ybVwiLCBcInJvdGF0ZSgtOTBkZWcpXCIpO1xyXG4gICAgfVxyXG59KVxyXG5cclxuLy8gb24gaG92ZXIgY29sbGFwc2UgYnV0dG9uIGJhY2tncm91bmQgY29sb3IgYW5kIHJvdGF0ZSBhcnJvd1xyXG4kKCcuY3VzdG9tQnV0dG9uJykub24oJ21vdXNlZW50ZXInLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAvLyBSb3RhdGUgb24gbW91c2UgZW50ZXIgd2l0aCBleHBhbmRlZCBjaGVja1xyXG4gICAgZXhwZW5kZWQgPyAkKCcjcm90YXRlU1ZHJyArIHRoaXMuaWQpLmNzcyh7IFwidHJhbnNmb3JtXCI6IFwicm90YXRlKDBkZWcpXCIgfSkgOiAkKCcjcm90YXRlU1ZHJyArIHRoaXMuaWQpLmNzcyh7IFwidHJhbnNmb3JtXCI6IFwicm90YXRlKC05MGRlZylcIiB9KTtcclxuICAgIC8vIEJhY2tncm91bmQgY29sb3Igb24gbW91c2UgZW50ZXJcclxuICAgICQoJyMnICsgdGhpcy5pZCkuY3NzKFwiYmFja2dyb3VuZC1jb2xvclwiLCBcIiM0MDQwNDBcIik7XHJcbn0pLm9uKCdtb3VzZWxlYXZlJywgZnVuY3Rpb24gKCkge1xyXG4gICAgLy8gUmVzZXQgcm90YXRlIG9uIG1vdXNlIGVudGVyIHdpdGggZXhwYW5kZWQgY2hlY2tcclxuICAgIGV4cGVuZGVkID8gJCgnI3JvdGF0ZVNWRycgKyB0aGlzLmlkKS5jc3Moe1xyXG4gICAgICAgIFwidHJhbnNmb3JtXCI6IFwicm90YXRlKC05MGRlZylcIixcclxuICAgICAgICBcImJhY2tncm91bmQtY29sb3JcIjogXCJ0cmFuc3BhcmVudFwiXHJcbiAgICB9KSA6ICQoJyNyb3RhdGVTVkcnICsgdGhpcy5pZCkuY3NzKHtcclxuICAgICAgICBcInRyYW5zZm9ybVwiOiBcInJvdGF0ZSgwZGVnKVwiLFxyXG4gICAgICAgIFwiYmFja2dyb3VuZC1jb2xvclwiOiBcInRyYW5zcGFyZW50XCJcclxuICAgIH0pO1xyXG4gICAgLy8gRGVmYXVsdCBiYWNrZ3JvdW5kIGNvbG9yIG9uIG1vdXNlIGxlYXZlXHJcbiAgICAkKCcjJyArIHRoaXMuaWQpLmNzcyhcImJhY2tncm91bmQtY29sb3JcIiwgXCJ0cmFuc3BhcmVudFwiKTtcclxufSk7XHJcblxyXG4vLyBIaWRlIGV4cGFuZCBidXR0b24gd2hlbiB0YWJsZSBoYXZlIGxlc3MgdGhhbiAzIHJvd3NcclxualF1ZXJ5KGZ1bmN0aW9uICgkKSB7XHJcbiAgICAkKCcud2FsbGV0Q3VzdG9tVGFibGUnKS5lYWNoKGZ1bmN0aW9uIChpbmRleCkge1xyXG4gICAgICAgIGlmICgkKCcjdCcgKyBpbmRleCArICcgdHInKS5sZW5ndGggPD0gNSkge1xyXG4gICAgICAgICAgICAkKCcjJyArIGluZGV4KS5oaWRlKCk7XHJcbiAgICAgICAgfVxyXG4gICAgfSlcclxufSlcclxuXHJcbiQoZnVuY3Rpb24gKCkge1xyXG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwidG9vbHRpcFwiXScpLnRvb2x0aXAoKTtcclxufSk7XHJcbiJdLCJuYW1lcyI6WyIkIiwib24iLCJ2YWwiLCJzaG93IiwiaGlkZSIsInByb3AiLCJleHBlbmRlZCIsImlkIiwiY3NzIiwiZG9jdW1lbnQiLCJoZWlnaHQiLCJqUXVlcnkiLCJlYWNoIiwiaW5kZXgiLCJsZW5ndGgiLCJ0b29sdGlwIl0sInNvdXJjZVJvb3QiOiIifQ==