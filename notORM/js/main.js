'use strict';

/*

Main javascript functions to init most of the elements

#1. CHAT APP
#2. CALENDAR INIT
#3. FORM VALIDATION
#4. DATE RANGE PICKER
#5. DATATABLES
#6. EDITABLE TABLES
#7. FORM STEPS FUNCTIONALITY
#8. SELECT 2 ACTIVATION
#9. CKEDITOR ACTIVATION
#10. CHARTJS CHARTS http://www.chartjs.org/
#11. MENU RELATED STUFF
#12. CONTENT SIDE PANEL TOGGLER
#13. EMAIL APP
#14. FULL CHAT APP
#15. CRM PIPELINE
#16. OUR OWN CUSTOM DROPDOWNS 
#17. BOOTSTRAP RELATED JS ACTIVATIONS
#18. TODO Application

*/

// ------------------------------------
// HELPER FUNCTIONS TO TEST FOR SPECIFIC DISPLAY SIZE (RESPONSIVE HELPERS)
// ------------------------------------

function is_display_type(display_type) {
  return $('.display-type').css('content') == display_type || $('.display-type').css('content') == '"' + display_type + '"';
}
function not_display_type(display_type) {
  return $('.display-type').css('content') != display_type && $('.display-type').css('content') != '"' + display_type + '"';
}

$(function () {

  // #1. CHAT APP

  $('.floated-chat-btn, .floated-chat-w .chat-close').on('click', function () {
    $('.floated-chat-w').toggleClass('active');
    return false;
  });

  $('.message-input').on('keypress', function (e) {
    if (e.which == 13) {
      $('.chat-messages').append('<div class="message self"><div class="message-content">' + $(this).val() + '</div></div>');
      $(this).val('');
      var $messages_w = $('.floated-chat-w .chat-messages');
      $messages_w.scrollTop($messages_w.prop("scrollHeight"));
      $messages_w.perfectScrollbar('update');
      return false;
    }
  });

  $('.floated-chat-w .chat-messages').perfectScrollbar();

  // #2. CALENDAR INIT

  if ($("#fullCalendar").length) {
    var calendar, d, date, m, y;

    date = new Date();

    d = date.getDate();

    m = date.getMonth();

    y = date.getFullYear();

    calendar = $("#fullCalendar").fullCalendar({
      header: {
        left: "prev,next today",
        center: "title",
        right: "month,agendaWeek,agendaDay"
      },
      selectable: true,
      selectHelper: true,
      select: function select(start, end, allDay) {
        var title;
        title = prompt("Event Title:");
        if (title) {
          calendar.fullCalendar("renderEvent", {
            title: title,
            start: start,
            end: end,
            allDay: allDay
          }, true);
        }
        return calendar.fullCalendar("unselect");
      },
      editable: true,
      events: [{
        title: "Long Event",
        start: new Date(y, m, 3, 12, 0),
        end: new Date(y, m, 7, 14, 0)
      }, {
        title: "Lunch",
        start: new Date(y, m, d, 12, 0),
        end: new Date(y, m, d + 2, 14, 0),
        allDay: false
      }, {
        title: "Click for Google",
        start: new Date(y, m, 28),
        end: new Date(y, m, 29),
        url: "http://google.com/"
      }]
    });
  }

  // #3. FORM VALIDATION

  if ($('#formValidate').length) {
    $('#formValidate').validator();
  }

  // #4. DATE RANGE PICKER

  $('input.single-daterange').daterangepicker({ "singleDatePicker": true });
  $('input.multi-daterange').daterangepicker({ "startDate": "03/28/2017", "endDate": "04/06/2017" });

  // #5. DATATABLES

  if ($('#formValidate').length) {
    $('#formValidate').validator();
  }
  if ($('#dataTable1').length) {
    $('#dataTable1').DataTable({ buttons: ['copy', 'excel', 'pdf'] });
  }
  // #6. EDITABLE TABLES

  if ($('#editableTable').length) {
    $('#editableTable').editableTableWidget();
  }

  // #7. FORM STEPS FUNCTIONALITY

  $('.step-trigger-btn').on('click', function () {
    var btn_href = $(this).attr('href');
    $('.step-trigger[href="' + btn_href + '"]').click();
    return false;
  });

  // FORM STEP CLICK
  $('.step-trigger').on('click', function () {
    var prev_trigger = $(this).prev('.step-trigger');
    if (prev_trigger.length && !prev_trigger.hasClass('active') && !prev_trigger.hasClass('complete')) return false;
    var content_id = $(this).attr('href');
    $(this).closest('.step-triggers').find('.step-trigger').removeClass('active');
    $(this).prev('.step-trigger').addClass('complete');
    $(this).addClass('active');
    $('.step-content').removeClass('active');
    $('.step-content' + content_id).addClass('active');
    return false;
  });
  // END STEPS FUNCTIONALITY


  // #8. SELECT 2 ACTIVATION

  if ($('.select2').length) {
    $('.select2').select2();
  }

  // #9. CKEDITOR ACTIVATION

  if ($('#ckeditor1').length) {
    CKEDITOR.replace('ckeditor1');
  }

  // #10. CHARTJS CHARTS http://www.chartjs.org/

  if (typeof Chart !== 'undefined') {

    var fontFamily = '"Proxima Nova W01", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    // set defaults
    Chart.defaults.global.defaultFontFamily = fontFamily;
    Chart.defaults.global.tooltips.titleFontSize = 14;
    Chart.defaults.global.tooltips.titleMarginBottom = 4;
    Chart.defaults.global.tooltips.displayColors = false;
    Chart.defaults.global.tooltips.bodyFontSize = 12;
    Chart.defaults.global.tooltips.xPadding = 10;
    Chart.defaults.global.tooltips.yPadding = 8;

    // init lite line chart if element exists

    if ($("#liteLineChart").length) {
      var liteLineChart = $("#liteLineChart");

      var liteLineGradient = liteLineChart[0].getContext('2d').createLinearGradient(0, 0, 0, 200);
      liteLineGradient.addColorStop(0, 'rgba(30,22,170,0.08)');
      liteLineGradient.addColorStop(1, 'rgba(30,22,170,0)');

      // line chart data
      var liteLineData = {
        labels: ["January 1", "January 5", "January 10", "January 15", "January 20", "January 25"],
        datasets: [{
          label: "Sold",
          fill: true,
          lineTension: 0.4,
          backgroundColor: liteLineGradient,
          borderColor: "#8f1cad",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "#fff",
          pointBackgroundColor: "#2a2f37",
          pointBorderWidth: 2,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: "#FC2055",
          pointHoverBorderColor: "#fff",
          pointHoverBorderWidth: 2,
          pointRadius: 4,
          pointHitRadius: 5,
          data: [13, 28, 19, 24, 43, 49],
          spanGaps: false
        }]
      };

      // line chart init
      var myLiteLineChart = new Chart(liteLineChart, {
        type: 'line',
        data: liteLineData,
        options: {
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              display: false,
              ticks: {
                fontSize: '11',
                fontColor: '#969da5'
              },
              gridLines: {
                color: 'rgba(0,0,0,0.0)',
                zeroLineColor: 'rgba(0,0,0,0.0)'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                beginAtZero: true,
                max: 55
              }
            }]
          }
        }
      });
    }

    // init line chart if element exists
    if ($("#lineChart").length) {
      var lineChart = $("#lineChart");

      // line chart data
      var lineData = {
        labels: ["1", "5", "10", "15", "20", "25", "30", "35"],
        datasets: [{
          label: "Visitors Graph",
          fill: false,
          lineTension: 0,
          backgroundColor: "#fff",
          borderColor: "#6896f9",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "#fff",
          pointBackgroundColor: "#2a2f37",
          pointBorderWidth: 3,
          pointHoverRadius: 10,
          pointHoverBackgroundColor: "#FC2055",
          pointHoverBorderColor: "#fff",
          pointHoverBorderWidth: 3,
          pointRadius: 6,
          pointHitRadius: 10,
          data: [27, 20, 44, 24, 29, 22, 43, 52],
          spanGaps: false
        }]
      };

      // line chart init
      var myLineChart = new Chart(lineChart, {
        type: 'line',
        data: lineData,
        options: {
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              ticks: {
                fontSize: '11',
                fontColor: '#969da5'
              },
              gridLines: {
                color: 'rgba(0,0,0,0.05)',
                zeroLineColor: 'rgba(0,0,0,0.05)'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                beginAtZero: true,
                max: 65
              }
            }]
          }
        }
      });
    }

    // init donut chart if element exists
    if ($("#barChart1").length) {
      var barChart1 = $("#barChart1");
      var barData1 = {
        labels: ["January", "February", "March", "April", "May", "June"],
        datasets: [{
          label: "My First dataset",
          backgroundColor: ["#5797FC", "#629FFF", "#6BA4FE", "#74AAFF", "#7AAEFF", '#85B4FF'],
          borderColor: ['rgba(255,99,132,0)', 'rgba(54, 162, 235, 0)', 'rgba(255, 206, 86, 0)', 'rgba(75, 192, 192, 0)', 'rgba(153, 102, 255, 0)', 'rgba(255, 159, 64, 0)'],
          borderWidth: 1,
          data: [24, 42, 18, 34, 56, 28]
        }]
      };
      // -----------------
      // init bar chart
      // -----------------
      new Chart(barChart1, {
        type: 'bar',
        data: barData1,
        options: {
          scales: {
            xAxes: [{
              display: false,
              ticks: {
                fontSize: '11',
                fontColor: '#969da5'
              },
              gridLines: {
                color: 'rgba(0,0,0,0.05)',
                zeroLineColor: 'rgba(0,0,0,0.05)'
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                color: 'rgba(0,0,0,0.05)',
                zeroLineColor: '#6896f9'
              }
            }]
          },
          legend: {
            display: false
          },
          animation: {
            animateScale: true
          }
        }
      });
    }

    // init pie chart if element exists
    if ($("#pieChart1").length) {
      var pieChart1 = $("#pieChart1");

      // pie chart data
      var pieData1 = {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple"],
        datasets: [{
          data: [300, 50, 100, 30, 70],
          backgroundColor: ["#5797fc", "#7e6fff", "#4ecc48", "#ffcc29", "#f37070"],
          hoverBackgroundColor: ["#5797fc", "#7e6fff", "#4ecc48", "#ffcc29", "#f37070"],
          borderWidth: 0
        }]
      };

      // -----------------
      // init pie chart
      // -----------------
      new Chart(pieChart1, {
        type: 'pie',
        data: pieData1,
        options: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 15,
              fontColor: '#3e4b5b'
            }
          },
          animation: {
            animateScale: true
          }
        }
      });
    }

    // -----------------
    // init donut chart if element exists
    // -----------------
    if ($("#donutChart").length) {
      var donutChart = $("#donutChart");

      // donut chart data
      var data = {
        labels: ["Red", "Blue", "Yellow", "Green", "Purple"],
        datasets: [{
          data: [300, 50, 100, 30, 70],
          backgroundColor: ["#5797fc", "#7e6fff", "#4ecc48", "#ffcc29", "#f37070"],
          hoverBackgroundColor: ["#5797fc", "#7e6fff", "#4ecc48", "#ffcc29", "#f37070"],
          borderWidth: 0
        }]
      };

      // -----------------
      // init donut chart
      // -----------------
      new Chart(donutChart, {
        type: 'doughnut',
        data: data,
        options: {
          legend: {
            display: false
          },
          animation: {
            animateScale: true
          },
          cutoutPercentage: 80
        }
      });
    }
  }

  // #11. MENU RELATED STUFF

  // INIT MOBILE MENU TRIGGER BUTTON
  $('.mobile-menu-trigger').on('click', function () {
    $('.menu-mobile .menu-and-user').slideToggle(200, 'swing');
    return false;
  });

  // INIT MENU TO ACTIVATE ON HOVER
  var menu_timer;
  $('.menu-activated-on-hover ul.main-menu > li.has-sub-menu').mouseenter(function () {
    var $elem = $(this);
    clearTimeout(menu_timer);
    $elem.closest('ul').addClass('has-active').find('> li').removeClass('active');
    $elem.addClass('active');
  });
  $('.menu-activated-on-hover ul.main-menu > li.has-sub-menu').mouseleave(function () {
    var $elem = $(this);
    menu_timer = setTimeout(function () {
      $elem.removeClass('active').closest('ul').removeClass('has-active');
    }, 200);
  });

  // INIT MENU TO ACTIVATE ON CLICK
  $('.menu-activated-on-click li.has-sub-menu > a').on('click', function (event) {
    var $elem = $(this).closest('li');
    if ($elem.hasClass('active')) {
      $elem.removeClass('active');
    } else {
      $elem.closest('ul').find('li.active').removeClass('active');
      $elem.addClass('active');
    }
    return false;
  });

  // #12. CONTENT SIDE PANEL TOGGLER

  $('.content-panel-toggler, .content-panel-close, .content-panel-open').on('click', function () {
    $('.all-wrapper').toggleClass('content-panel-active');
  });

  // #13. EMAIL APP 

  $('.more-messages').on('click', function () {
    $(this).hide();
    $('.older-pack').slideDown(100);
    $('.aec-full-message-w.show-pack').removeClass('show-pack');
    return false;
  });

  // CKEDITOR ACTIVATION FOR MAIL REPLY
  if (typeof CKEDITOR !== 'undefined') {
    CKEDITOR.disableAutoInline = true;
    if ($('#ckeditorEmail').length) {
      CKEDITOR.config.uiColor = '#ffffff';
      CKEDITOR.config.toolbar = [['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'About']];
      CKEDITOR.config.height = 110;
      CKEDITOR.replace('ckeditor1');
    }
  }

  // EMAIL SIDEBAR MENU TOGGLER
  $('.ae-side-menu-toggler').on('click', function () {
    $('.app-email-w').toggleClass('compact-side-menu');
  });

  // EMAIL MOBILE SHOW MESSAGE
  $('.ae-item').on('click', function () {
    $('.app-email-w').addClass('forse-show-content');
  });

  if ($('.app-email-w').length) {
    if (is_display_type('phone') || is_display_type('tablet')) {
      $('.app-email-w').addClass('compact-side-menu');
    }
  }

  // #14. FULL CHAT APP
  function add_full_chat_message($input) {
    $('.chat-content').append('<div class="chat-message self"><div class="chat-message-content-w"><div class="chat-message-content">' + $input.val() + '</div></div><div class="chat-message-date">1:23pm</div><div class="chat-message-avatar"><img alt="" src="img/avatar1.jpg"></div></div>');
    $input.val('');
    var $messages_w = $('.chat-content-w');
    $messages_w.scrollTop($messages_w.prop("scrollHeight"));
    $messages_w.perfectScrollbar('update');
  }

  $('.chat-content-w').perfectScrollbar({ wheelPropagation: true });

  $('.chat-btn a').on('click', function () {
    add_full_chat_message($('.chat-input input'));
    return false;
  });
  $('.chat-input input').on('keypress', function (e) {
    if (e.which == 13) {
      add_full_chat_message($(this));
      return false;
    }
  });

  // #15. CRM PIPELINE
  if ($('.pipeline').length) {
    // INIT DRAG AND DROP FOR PIPELINE ITEMS
    var dragulaObj = dragula($('.pipeline-body').toArray(), {}).on('drag', function () {}).on('drop', function (el) {}).on('over', function (el, container) {
      $(container).closest('.pipeline-body').addClass('over');
    }).on('out', function (el, container, source) {

      var new_pipeline_body = $(container).closest('.pipeline-body');
      new_pipeline_body.removeClass('over');
      var old_pipeline_body = $(source).closest('.pipeline-body');
    });
  }

  // #16. OUR OWN CUSTOM DROPDOWNS 
  $('.os-dropdown-trigger').on('mouseenter', function () {
    $(this).addClass('over');
  });
  $('.os-dropdown-trigger').on('mouseleave', function () {
    $(this).removeClass('over');
  });

  // #17. BOOTSTRAP RELATED JS ACTIVATIONS

  // - Activate tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // - Activate popovers
  $('[data-toggle="popover"]').popover();

  // #18. TODO Application

  // Tasks foldable trigger
  $('.tasks-header-toggler').on('click', function () {
    $(this).closest('.tasks-section').find('.tasks-list-w').slideToggle(100);
    return false;
  });

  // Sidebar Sections foldable trigger
  $('.todo-sidebar-section-toggle').on('click', function () {
    $(this).closest('.todo-sidebar-section').find('.todo-sidebar-section-contents').slideToggle(100);
    return false;
  });

  // Sidebar Sub Sections foldable trigger
  $('.todo-sidebar-section-sub-section-toggler').on('click', function () {
    $(this).closest('.todo-sidebar-section-sub-section').find('.todo-sidebar-section-sub-section-content').slideToggle(100);
    return false;
  });

  // Drag init
  if ($('.tasks-list').length) {
    // INIT DRAG AND DROP FOR Todo Tasks
    var dragulaTasksObj = dragula($('.tasks-list').toArray(), {
      moves: function moves(el, container, handle) {
        return handle.classList.contains('drag-handle');
      }
    }).on('drag', function () {}).on('drop', function (el) {}).on('over', function (el, container) {
      $(container).closest('.tasks-list').addClass('over');
    }).on('out', function (el, container, source) {

      var new_pipeline_body = $(container).closest('.tasks-list');
      new_pipeline_body.removeClass('over');
      var old_pipeline_body = $(source).closest('.tasks-list');
    });
  }

  // Task actions init

  // Complete/Done
  $('.task-btn-done').on('click', function () {
    $(this).closest('.draggable-task').toggleClass('complete');
    return false;
  });

  // Favorite/star
  $('.task-btn-star').on('click', function () {
    $(this).closest('.draggable-task').toggleClass('favorite');
    return false;
  });

  // Delete
  var timeoutDeleteTask;
  $('.task-btn-delete').on('click', function () {
    if (confirm('Are you sure you want to delete this task?')) {
      var $task_to_remove = $(this).closest('.draggable-task');
      $task_to_remove.addClass('pre-removed');
      $task_to_remove.append('<a href="#" class="task-btn-undelete">Undo Delete</a>');
      timeoutDeleteTask = setTimeout(function () {
        $task_to_remove.slideUp(300, function () {
          $(this).remove();
        });
      }, 5000);
    }
    return false;
  });

  $('.tasks-list').on('click', '.task-btn-undelete', function () {
    $(this).closest('.draggable-task').removeClass('pre-removed');
    $(this).remove();
    if (typeof timeoutDeleteTask !== 'undefined') {
      clearTimeout(timeoutDeleteTask);
    }
    return false;
  });
});
