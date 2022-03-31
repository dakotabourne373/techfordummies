//Dakota Bourne and Matthew Reid

function emailCheck() {
    let e = $("#email");
    let submit = $("#submit");
    let ehelp = $("#ehelp");

    if (e.val().match(/[a-zA-Z0-9\.]+@[a-z]+\.(com|edu|net|org|gov)/) === null) {
        ehelp.text("Email must be valid with a com, net, edu, org, or gov ending");
        e.addClass("is-invalid");
        submit.prop("disabled", true);
    } else {
        ehelp.text("");
        e.removeClass("is-invalid");
        submit.prop("disabled", false);
    }
}

$("#email").keyup(function () {
    emailCheck();
})