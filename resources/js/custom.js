$(document).ready(function() {
    $(function() {
        $.cookit({
            messageText: "This site uses only essential cookies.",
            linkText: "Learn more",
            linkUrl: "https://www.redcar-cleveland.gov.uk/site-terms/Pages/cookies.aspx",
            buttonText: "<b>I accept</b>",
            backgroundColor: "#008D97",
            messageColor: "#fff",
            linkColor: "#fad04c",
            buttonColor: "#ffffff",
        });
    });

    $("#approve").click(function() {
        event.preventDefault();
        console.log("Clicked Approve");
        $.ajax({
            type: "POST",
            url: "server.petitionapprove.php",
        }).done(function(msg) {
            window.location.replace("/epetition/admin.petitionapproved.php");
        });
    });

    $("#decline").click(function() {
        event.preventDefault();
        console.log("Clicked Decline");
        $("#detailsModal").modal("show");
    });

    $("#declinePetition").click(function() {
        event.preventDefault();
        var category = $("#category option:selected").index();
        var comments = $("#comments").val();
        if (category == 0 || comments == "") {
            alert(
                "Please select a category and enter comments \n (these will be emailed to the user)"
            );
            $("#declinePetition").prop("disabled", false);
            return;
        }
        $.ajax({
            type: "POST",
            url: "server.petitiondecline.php",
            data: $("#declinepetition").serialize(),
        }).done(function(msg) {
            window.location.replace("/epetition/admin.petitiondeclined.php");
        });
    });

    $("#seesignatures").click(function() {
        event.preventDefault();
        console.log("Clicked See Signatures");
        $.ajax({
            type: "POST",
            url: "admin.showsignatures.php",
        }).done(function(msg) {
            $("#seesignatures").hide();
            $("#signaturetable").html(msg);
        });
    });

    $("#removeSignature").click(function() {
        event.preventDefault();
        var reason = $("#reason option:selected").index();
        if (reason == 0) {
            alert("Please select a reason");
            return;
        }
        $.ajax({
            type: "POST",
            url: "server.removesignature.php",
            data: $("#removesignature").serialize(),
        }).done(function(msg) {
            window.location.replace("/epetition/admin.signatureremoved.php");
        });
    });

    // Password check
    $("#password").on("input propertychange paste", function() {
        test = $("#password").val();
        result = zxcvbn(test);
        score = result.score;
        if (score < 3) {
            $("#passwordadvice").text(result.feedback.suggestions);
            $("#passwordwarning").text(result.feedback.warning);
            $(".passwordcheck").prop("disabled", true);
        } else {
            $("#passwordadvice").text("Password meets minimum requirements");
            $("#passwordwarning").text("");
            $(".passwordcheck").prop("disabled", false);
        }
        $("#passwordstrength").text("Password Strength: " + score + "/4");
        console.log(result);
    });

    $("#search").click(function() {
        console.log("Search Clicked");
        window.location.replace("/epetition/?search=" + $("#searchtext").val());
    });

    $("#searchtext").keypress(function(event) {
        var keycode = event.keyCode ? event.keyCode : event.which;
        if (keycode == "13") {
            window.location.replace("/epetition/?search=" + $("#searchtext").val());
        }
    });
});