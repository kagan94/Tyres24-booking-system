/**
 * Created by markus on 9.03.17.
 */

/* Extend functionality of validator method to capture uniqueIDs */
$.validator.addMethod("unique", function(value, element, params) {
    var prefix = params;
    var selector = jQuery.validator.format("[name!='{0}'][unique='{1}']", element.name, prefix);
    var matches = new Array();

    $(selector).each(function(index, item) {
        if (value == $(item).val()) {
            matches.push(item);
        }
    });

    return matches.length == 0;
}, "Value is not unique.");

/* Set default rule for the class to check UID */
$.validator.addClassRules('validate-uniqueID', {
    required: true,
    unique: true
});

/* Used on the "edit/add garage" page*/
function addNewLine() {
    var counter = $('#new-garage #lines .line').length + 1;

    html = '' +
        '<tr class="line line-' + counter + '"> \
            <input type="hidden" name="lines[lineID][' + counter + ']" value="" ** \\> ** \
            <input type="hidden" name="lines[remove][' + counter + ']" value="0" class="remove" ** \\> **\
            \
            <td class="text-center"></td> \
            <td><input type="text" name="lines[uniqueID][' + counter + ']" value="1" class="form-control text-center validate-uniqueID" placeholder="Unique ID (any, from 1 to 10)" required="true" maxlength="2" pattern="[1-9]|10" unique="true"></td> \
            <td class="text-center"><input type="checkbox" name="lines[canServeVansAndTrucks][' + counter + ']" value="1" checked /></td>\
            <td class="text-center"> \
                <a onclick="removeLine($(this).parent().parent()); return false;" title="Remove line"><span class="glyphicon glyphicon-remove fa-2x"></span></a> \
            </td> \
        </tr>';

    $(html).insertBefore('.add-new-line');
}

/* Used on the "edit/add garage" page*/
function removeLine(line) {
    $(line).hide();
    $(line).find('.remove').val(1);
}

$(document).ready(function() {
    // Validate registration form
    $("#new-booking, #new-garage").validate({
        debug: true,
        // rules: {
        //     "lines[uniqueID]": {
        //         required: true,
        //         minlength: 1
        //     }
        // },
        submitHandler: function(form) {
            form.submit();
        },
        invalidHandler: function(event, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                $("div.alert span").html(message);
                $("div.alert").show();
            } else {
                $("div.alert").hide();
            }
        }
    });

    // $(".uniqueID").rules("add", {
    //     required:true,
    //     minlength:3
    // });

    $("#garageID").on('change', function(){
        $.ajax({
            type: 'post',
            // cache: false,
            dataType: 'json',
            url: '/garage/ajaxLinesByGarageID',
            data: { garageID: $('#garageID option:selected').val() },
            'success': function(lines){
                var html = '<option value=""></option>';

                $.each(lines, function(key, line) {
                    html += '<option>' + line.uniqueID + '</option>';

                    $(html).insertAfter('#lineID option:last');
                });

                $("#lineID").html(html);
            },
            'beforeSend': function() {
                $('#lineID option').remove();
            }
        });
    });

    $('#hasTyreStorage, #needTyreStorage').on('click', function() {
        if ($(this).prop('checked') == true) {
            $('#tyreSlots-block').show(500);
        } else {
            $('#tyreSlots-block').hide(500);
        }
    });

    $('.datetime').datetimepicker({
        // mask:true,
        // allowTimes:['9:00','9:15','9:30','9:45','10:00','10:15','10:30','10:45','11:00','11:15','11:30','11:45','12:00','12:15','12:30','12:45','13:00','13:15','13:30','13:45','14:00','14:15','14:30','14:45','15:00','15:15','15:30','15:45','16:00','16:15','16:30','16:45','17:00','17:15','17:30','17:45','18:00','18:15','18:30','18:45','19:00','19:15','19:30','19:45','20:00','20:15','20:30','20:45'],
        // disabledDates: ['2016/03/06','2016/03/13'],
        minDate: 0,
        minTime: 0,
        lang:'en',
        step: 30,
        scrollMonth:false,
        scrollInput:false,
        // defaultTime:'09:00',
        dayOfWeekStart: 1,
        format:'d.m.Y H:i',
        onChangeDateTime: function(startDatetime){
            var minutesOffset = 0,
                vehicleType = $('#vehicleType').val();

            if ($.inArray(vehicleType, ['car', 'van']) !== -1){
                minutesOffset = 30
            } else if (vehicleType == 'truck') {
                minutesOffset = 60
            }

            var startDT = moment(startDatetime),
                endDT = moment(startDT).add(minutesOffset, 'minutes'),
                endDatetime = endDT.format('DD.MM.YYYY HH:mm');

            $('#endDatetime').val(endDatetime);
        },
    });    
});


$(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
    
    $('.week-picker').datepicker( {
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
            
            // var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            var dateFormat = "dd.mm.yy";
            $('#startDate').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').val($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            
            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    
    $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
});
