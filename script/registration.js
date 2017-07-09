/* ------------------------------------------------*/
/*         registration form validation            */
/* ------------------------------------------------*/

$(document).on("keyup change", "#password,#password-repeat", function(){passwordMatch()});

$(document).ready(function(){
        $("#password").on("keyup change", function() {
            var password = this.value;
            checkStrength(password);
            $('#pass-strength').show();
            $('#pass-strength').text(result);
    });
});

function checkStrength(password){
    var totStrength=0;
    if (password.length>4){
        totStrength++;
    }
    if (password.length>10){
        totStrength++;
    }
    if (password.match(/\d/)){ //numbers
        totStrength++;
    }
    if (password.match(/\s/)){//whitespaces
        totStrength++;
    }
    if (password.match(/[A-Z]/)){//uppercase letters
        totStrength++;
    }
    
    if (password.length <= 4){
        result="Password must be longer than 4 characters";
        $('#pass-strength').css({"border-color":"#FF0000","background-color":"#F75D59"});
    }
    else if (totStrength===1){
        result="Password security: very low";
        $('#pass-strength').css({"border-color":"#FF0000","background-color":"#F75D59"});
    }
    else if (totStrength===2){
        result="Password security: low";
        $('#pass-strength').css({"border-color":"#F88017","background-color":"#FF7F50"});
    }
    else if (totStrength===3){
        result="Password security: medium";
        $('#pass-strength').css({"border-color":"#F88017","background-color":"#FF7F50"});
    }
    else if (totStrength===4){
        result="Password security: high";
        $('#pass-strength').css({"border-color":"#00FF00","background-color":"#BCE954"});
    }
    else if (totStrength===5){
        result="Password security: very high";
        $('#pass-strength').css({"border-color":"#00FF00","background-color":"#BCE954"});
    }
}

function passwordMatch(){
    var pass1=$('#password').val();
    var pass2=$('#password-repeat').val();
    
    if (pass1!=="" && pass2!=="" && pass1===pass2){
        $('#pass-match').hide();
        $("#password").addClass("input-field-ok");
        $("#password-repeat").addClass("input-field-ok");
    }
    else {
        $("#password").removeClass("input-field-ok");
        $("#password-repeat").removeClass("input-field-ok");
        $('#pass-match').show();
        $('#pass-match').text("Passwords do not match");
        $('#pass-match').addClass("input-message-error");
    }
}

$(document).ready(function(){ //resets all fields 
    $('#reset').click(function(){
        $('*').removeClass("input-field-ok"); //removes green borders
        $('.no-input,#pass-strength,#pass-match').hide(); //hides info/error messages
    });
});

$(document).on('blur',"#nameReg",function(){validateName()});

function validateName(){
    var name=$('#nameReg').val();
    if (name===""){
        var message="Insert your name";
        $("#nameReg").removeClass("input-field-ok");
        $('#name-err').addClass("input-message-error");
        $('#name-err').text(message);
        $('#name-err').show();
    }
    else if(name.match(/[^A-Za-z\s\']/)){ //letters, whitespace, apostrophe
        var message="Name can only contain letters and whitespaces";
        $("#nameReg").removeClass("input-field-ok");
        $('#name-err').addClass("input-message-error");
        $('#name-err').text(message);
        $('#name-err').show();
    }
    else {
        $("#nameReg").addClass("input-field-ok");
        $('#name-err').hide();
    }
}

$(document).on('blur',"#surnameReg",function(){validateSurname()});

function validateSurname(){
    var surname=$('#surnameReg').val();
    if (surname===""){
        var message="Insert your surname";
        $("#surnameReg").removeClass("input-field-ok");
        $('#surname-err').addClass("input-message-error");
        $('#surname-err').text(message);
        $('#surname-err').show();
    }
    else if(surname.match(/[^A-Za-z\s\']/)){ //letters, whitespace, apostrophe
        var message="Surname can only contain letters and whitespaces";
        $("#surnameReg").removeClass("input-field-ok");
        $('#surname-err').addClass("input-message-error");
        $('#surname-err').text(message);
        $('#surname-err').show();
    }
    else {
        $("#surnameReg").addClass("input-field-ok");
        $('#surname-err').hide();
    }
}

$(document).on('blur',"#email",function(){validateEmail()});

function validateEmail(){
    var email=$('#email').val();
    re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var result = re.test(email);
    if (email===""){
        var message="Insert your email";
        $('#email').removeClass("input-field-ok");
        $('#email-err').addClass("input-message-error");
        $('#email-err').text(message);
        $('#email-err').show();
    }
    else {
        if (result === false){
            var message = "Invalid email";
            $('#email').removeClass("input-field-ok");
            $('#email-err').addClass("input-message-error");
            $('#email-err').text(message);
            $('#email-err').show();
        }
        else {
            $('#email-err').hide();
            $('#email').addClass("input-field-ok");
        }
    }
}

$(document).on('keyup change',':text,:password',function(){activateButtonReg()});
$(document).on('blur','.input-field',function(){activateButtonReg()});

function activateButtonReg(){
    if (isFormRegCompleted()) {
        $('#registration-button').removeAttr("disabled");
        $('#registration-button').removeClass("disabled");        
    }
    else {
        $('#registration-button').prop("disabled", true);
        $('#registration-button').addClass("disabled");
    }
}

function isFormRegCompleted(){
    var completed = false;
    if($("#nameReg").val().length > 0 && $('#nameReg').attr('class').split(" ").length == 2 &&
       $("#surnameReg").val().length > 0 && $('#surnameReg').attr('class').split(" ").length == 2 &&
       $("#email").val().length > 0 && $('#email').attr('class').split(" ").length == 2 &&
       $("#password").val().length > 0 && $('#password').attr('class').split(" ").length == 2 &&
       $("#password-repeat").val().length > 0 && $('#password-repeat').attr('class').split(" ").length == 2)
    {
        completed = true;
    }
    return completed;
}