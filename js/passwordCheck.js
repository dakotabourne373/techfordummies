//Dakota Bourne and Matthew Reid
function passwordCheck(num) {
    let pw = $("#password");
    let submit = $("#submit");
    let pwhelp = $("#pwhelp");

    if (pw.val().length < num) {
        pwhelp.text("Password must be " + num + " characters or longer");
        pw.addClass("is-invalid");
        submit.prop("disabled", true);
    } else {
        pwhelp.text("");
        pw.removeClass("is-invalid");
        submit.prop("disabled", false);
    }
}

$("#password").keyup(function () {
    passwordCheck(8);
})