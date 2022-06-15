$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: "hover",
    });

    // $('[data-toggle="tooltip"]').on("click", function() {
    //     $(this).tooltip("dispose");
    // });

    $(".list-group.list-group-flush a").click(function() {
        $(".list-group.list-group-flush a.active").removeClass("active");
        $(this).addClass("active");
    });

    $(".docs.list-group-item.list-group-item-action.list-group-item-light").click(
        function() {
            $(
                ".docs.list-group-item.list-group-item-action.list-group-item-light.active"
            ).removeClass("active");
            $(this).addClass("active");
        }
    );

    $("#doc_tab").click(function() {
        var document = "document";
        $.ajax({
            url: "../tabs/document_tab.php",
            method: "POST",
            data: {
                document: document,
            },
            success: function(data) {
                $("#content").load("../tabs/document_tab.php");
            },
        });
    });

    $("#report_tab").click(function() {
        var report = "report";
        $.ajax({
            url: "../tabs/document_tab.php",
            method: "POST",
            data: {
                report: report,
            },
            success: function(data) {
                $("#content").load("../tabs/document_tab.php");
            },
        });
    });

    $(".profile-settings").on("click", function() {
        $("#content").load("../tabs/user-profile-settings.php");
    });
    $("#user_tab").click(function() {
        $("#content").load("../tabs/user_tab.php");

        return false;
    });
    $("#dashboard_tab").click(function() {
        $("#content").load("../tabs/dashboard_tab.php");

        return false;
    });

    $("#semester").click(function() {
        $("#content").load("../tabs/semester_tab.php");

        return false;
    });

    $("#archive").click(function() {
        $("#content").load("../tabs/archive_tab.php");

        return false;
    });
    $("#shared").click(function() {
        $("#content").load("../tabs/shared_tab.php");

        return false;
    });
    $("#log_act").click(function() {
        $("#content").load("../tabs/login_activity.php");

        return false;
    });
    $("#trash").click(function() {
        $("#content").load("../tabs/trash_tab.php");

        return false;
    });
});