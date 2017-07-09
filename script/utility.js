/* ------------------------------------------------*/
/*           delete reservation check              */
/* ------------------------------------------------*/

$(document).ready(function(){
    $(".reservCheck").change(function() {
        var atLeastOne = false;
        var checkboxes = $('input:checkbox');
        for (var i = 0; i < checkboxes.length; i++){
            if (checkboxes[i].checked == true){
                atLeastOne = true;
            }
        }
        if (atLeastOne){
            //enable button
            $('#delSelected').removeAttr('disabled')
            $('#delSelected').removeClass('disabled')
        }
        else {
            //disable button
            $('#delSelected').prop("disabled", true);
            $('#delSelected').addClass('disabled')
        }
    });
});

/* ------------------------------------------------*/
/*        realt-time duration computation          */
/* ------------------------------------------------*/

$(document).ready(function(){
    $(".timeField").change(function() {
        var fields = checkAllTimesInserted();
        if (fields){
            if (fields[2].value < fields[0].value || (fields[0].value == fields[2].value && fields[3].value <= fields[1].value)) {
                var error = "Invalid end time";
                $('td:last div').removeClass("infomsg");
                $('td:last div').addClass("errormsg");
                $('td:last div').text(error);
                $('#showDuration').show();
            }
            else {
                var duration = 0;
                if (fields[2].value > fields[0].value){
                    duration += (fields[2].value-fields[0].value)*60;
                }
                duration += (fields[3].value-fields[1].value);
                var message = "Duration (minutes): "+duration;
                $('td:last div').removeClass("errormsg"); 
                $('td:last div').addClass("infomsg");
                $('td:last div').text(message);
                $('#showDuration').show();
            }
        }
        else {
            $('td:last div').removeClass("infomsg");
            $('td:last div').removeClass("errormsg");
            $('#showDuration').hide();
        }
    });
});

function checkAllTimesInserted(){
    var fields = $('.timeField');
    for (var i=0; i<fields.length; i++){
        if (fields[i].value == ""){
            return false;
            break;
        }
    }
    return fields;
}

$(document).ready(function(){
    $("#resetTime").on("click", function() {
        $('td:last div').empty();
        $('#showDuration').hide();
    });
});



/* ------------------------------------------------*/
/*         restore registration data after         */
/*               invalid validation                */
/* ------------------------------------------------*/

$(document).on('click',"#errorBack", function(){window.history.back()});