// date formate
$.views.converters("format", date => moment(date).format('MMM D, YYYY'));
$.views.converters("day", date => moment(date).format('ddd - MMM D, YYYY'));
$.views.converters("datetime", date => moment(date).format('MMM D, YYYY hh:mm A'));
// date time from now
$.views.converters("fromNow", date => moment(date).fromNow());
// status
$.views.converters("status", value => value == 'Yes' ? 'success' : 'danger' );
$.views.converters("trueStatus", value => +value ? 'danger' : 'success' );
$.views.converters("toggleStatus", value => +value ? 'Deactivate' : 'Activate' );
// is ready
$.views.converters("time", value => value ? moment(value).format('hh:mm:A') : '' );
$.views.converters("isSelected", value => value ? '' : 'selected' );
$.views.converters("isAssigned", value => value && value > 0 ? 'btn-success' : 'btn-danger' );
$.views.converters("isActive", value => value ? 'active' : 'inactive' );
$.views.converters("isTrainer", value => value ? value : 'Assign Trainer' );
// yes or no
$.views.converters("yesOrNo", value => value ? 'Yes' : 'No' );
// currency
$.views.converters("currency", num => num ? Number(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') : '0.0' );
// $.views.converters("currency", num => num );
// isEmpty
$.views.converters("isEmpty", value => value ? value.toFixed(2) : '0.0' );
// ready to delivery
$.views.converters("timeDiff", function(date1, date2) {
    if (date1 && date2) {
        var a = moment(date1); //todays date
        var b = moment(date2); // another date
        return a.diff(b, 'minutes');
    } else {
        return 0;
    }
})

// makeId
$.views.converters("makeId", value => makeId(value));
// age
$.views.converters("age", value =>  moment().diff(value, 'years'));
// SubmittedR4
$.views.converters("isSubmitted", x => x == 0 ? '<div class="msg-box danger m-0 p-0">No</div>' : '<div class="msg-box success m-0 p-0">Yes</div>');
// fileDownload
$.views.converters("fileDownload", x => x ? `<img class="file" href="${fileBaseURL}/${x}" src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/media/svg/icons/Files/Selected-file.svg" key="${x}" width="20px" height="20px" />` : '');
$.views.converters("fileUrl", x => x ? `${fileBaseURL + x}` : x);
// btnToR4
$.views.converters("btnToR4", x => x == 0 ? '<a class="btn btn-sm btn-tech pt-1 pl-2 pr-2 pb-1  submitToR4" href="#" data-toggle="tooltip" data-placement="top" title="Submit To R4"><i class="icon-paper-plane bold"></i></a>' : '');
// typeToSpecify
$.views.converters("typeToSpecify", x => +x ? `text` : `hidden`);
// is present
$.views.converters("isPresent", value => +value ? '<span class="badge badge-success-inverted">Present</span>' : '<span class="badge badge-danger-inverted">Leaved</span>' );