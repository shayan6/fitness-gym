// base url on top
const baseURL = '/gym/index.php';
window.dateranges = {
  'Today': [moment(), moment()],
  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
  'This Month': [moment().startOf('month'), moment().endOf('month')],
  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
  'This Year': [moment().startOf('year'), moment().endOf('year')],
  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
};

window.times = [
  "00:00", "00:15", "00:30", "00:45",
  "01:00", "01:15", "01:30", "01:45",
  "02:00", "02:15", "02:30", "02:45",
  "03:00", "03:15", "03:30", "03:45",
  "04:00", "04:15", "04:30", "04:45",
  "05:00", "05:15", "05:30", "05:45",
  "06:00", "06:15", "06:30", "06:45",
  "07:00", "07:15", "07:30", "07:45",
  "08:00", "08:15", "08:30", "08:45",
  "09:00", "09:15", "09:30", "09:45",
  "10:00", "10:15", "10:30", "10:45",
  "11:00", "11:15", "11:30", "11:45",
  "12:00", "12:15", "12:30", "12:45",
  "13:00", "13:15", "13:30", "13:45",
  "14:00", "14:15", "14:30", "14:45",
  "15:00", "15:15", "15:30", "15:45",
  "16:00", "16:15", "16:30", "16:45",
  "17:00", "17:15", "17:30", "17:45",
  "18:00", "18:15", "18:30", "18:45",
  "19:00", "19:15", "19:30", "19:45",
  "20:00", "20:15", "20:30", "20:45",
  "21:00", "21:15", "21:30", "21:45",
  "22:00", "22:15", "22:30", "22:45",
  "23:00", "23:15", "23:30", "23:45",
  "23:59"
];

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

  // click print 
  $('#print').on('click', function () {
    window.print();
  });

  // click to csv
  $('#csv').on('click', function () {
    exportTableToCSV('Gym-Report.csv');
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

function currency (num) {
 return num ? Number(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') : '0.0';
}

function format (date) {
  return date ? moment(date).format('MMM D, YYYY') : '';
 }

function exportTableToCSV(filename = 'download.csv', skipTotal = false) {
  var csv = [];
  var rows = document.querySelectorAll(".exportToCSV tr");
  for (var i = 0; i < rows.length; i++) {
      var row = [], cols = rows[i].querySelectorAll("td, th");
      if ($(rows[i]).parent().attr('id') == 'total' && skipTotal)
          continue;

      for (var j = 0; j < cols.length; j++) 
          row.push("\""+cols[j].innerText+"\"");

      csv.push(row.join(","));        
  }

  // Download CSV file
  downloadCSV(csv.join("\n"), filename);
}    

function downloadCSV(csv, filename) {
  var csvFile;
  var downloadLink;
  // CSV file
  console.log(csv);
  csvFile = new Blob([csv], {type: "text/plain;charset=utf-8;"});
  // Download link
  downloadLink = document.createElement("a");
  // File name
  downloadLink.download = filename;
  // Create a link to the file
  downloadLink.href = window.URL.createObjectURL(csvFile);
  // Hide download link
  downloadLink.style.display = "none";
  // Add the link to DOM
  document.body.appendChild(downloadLink);
  // Click download link
  downloadLink.click();
}