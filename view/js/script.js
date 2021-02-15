// base url on top
const baseURL = 'http://localhost/gym/index.php';

// datepicker and search bar top
// $(function () {
//   $(".element-search input").on("keyup", function () {
//     var value = $(this).val().toLowerCase();
//     $(".tbody .row").filter(function () {
//       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
//     });
//   });
// });

// side bar js
$(document).ready(function () {
  $('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
      if ($(window).width() < 770) { $('.element-search.autosuggest-search-activator').toggleClass('d-none'); }
      $(this).toggleClass('active');
  });

  $('#SearchOrder').focus();
  
  $('#filter-btn').on('click', () => $('#filter-table').slideToggle());
  
  $('[data-toggle="tooltip"]').tooltip();
  
  $('.datetime').daterangepicker({
    singleDatePicker: true,
    timePicker: true,
    showDropdowns: true,
    locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });

});

// query selector
window.queries = {};
$.each(document.location.search.substr(1).split('&'),function(c,q){
    var i = q.split('=');
    i.length > 1 && (queries[i[0].toString()] = i[1].toString());
});

function created_sort(a, b) {
  return new Date(a.created).getTime() - new Date(b.created).getTime();
}

var getPosition = function (options) {
  return new Promise(function (resolve, reject) {
    navigator.geolocation.getCurrentPosition(resolve, reject, options);
  });
}


var feedbackstars = value =>  
         `<span class="fa fa-star ${value > 0 && 'checked'}"></span>
          <span class="fa fa-star ${value > 1 && 'checked'}"></span>
          <span class="fa fa-star ${value > 2 && 'checked'}"></span>
          <span class="fa fa-star ${value > 3 && 'checked'}"></span>
          <span class="fa fa-star ${value > 4 && 'checked'}"></span>`;

function parseJwt (token) {
  var base64Url = token.split('.')[1];
  var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
  var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));

  return JSON.parse(jsonPayload);
};

// script js ########################################################################################################
var customRenderMenu = function (ul, items) {

  var self = this;
  var categoryArr = [];

  function contain(item, array) {
    var contains = false;
    $.each(array, function (index, value) {
      if (item == value) {
        contains = true;
        return false;
      }
    });
    return contains;
  }

  $.each(items, function (index, item) {
    if (!contain(item.category, categoryArr)) {
      categoryArr.push(item.category);
    }
  });

  $.each(categoryArr, function (index, category) {
    ul.append("<li class='ui-autocomplete-group'>" + category + "</li>");
    $.each(items, function (index, item) {
      if (item.category == category) {
        self._renderItemData(ul, item);
      }
    });
  });
};