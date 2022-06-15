$(document).ready(function() {
    $(".profile-button").on("click", function() {
        var name = $(".profile-name").val();
        var username = $(".profile-username").val();
        var lastname = $(".profile-lastname").val();
        var email = $(".profile-email").val();
        var phoneProfile = $(".profile-phone").val();
        var addressProfile = $(".profile-address").val();
        $.ajax({
            url: "../tabs/user_process.php",
            method: "POST",
            data: {
                editprofile: 1,
                name: name,
                username: username,
                lastname: lastname,
                email: email,
                phoneProfile: phoneProfile,
                addressProfile: addressProfile,
            },
            success: function(data) {
                alert(data);
                if (data.indexOf("updated") >= 0) {
                    window.location.href = "../logout.php";
                }
            },
        });
    });

    $(".profile-settings").on("click", function() {
        $("#content").load("../tabs/user-profile-settings.php");
    });

    $("#upload_photo").on("submit", function(event) {
        event.preventDefault();
        var form_data = new FormData(this);
        var file = $("#file")[0].files[0];
        var ext1 = $("#file").val();
        if (ext1 != "") {
            var totalSize = file.size;
        } else {
            var totalSize = 0;
        }

        var maxFilesize = 300000;
        form_data.append("uploadphoto", 1);
        var ext = ext1.split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var fileExtension = ["png"];

        if (ext1 == "") {
            // check if file input is empty
            $("#message").html("Please choose a file to upload");
        } else if (fileExtension.lastIndexOf(ext) == -1) {
            // check if the file has a valid extension
            $("#message").html("Invalid file type");
            $("#file").val("");
        } else if (totalSize > maxFilesize) {
            $("#message").html("Photo too large! maximum of 300kb");
            $("#file").val("");
        } else {
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener(
                        "progress",
                        function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $(".progress-bar").width(percentComplete + "%");
                                $(".progress-bar").html(percentComplete + "%");
                            }
                        },
                        false
                    );
                    return xhr;
                },
                url: "../tabs/document_addfile.php", // <-- point to server-side PHP script
                method: "POST",
                data: form_data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    // $('#message').html("Uploading...");
                    $(".progress-bar").width("0%");
                },
                success: function(data) {
                    $("#message").html(data);
                    if (data.indexOf("successfully") >= 0) {
                        alert(data);
                        $("#modelId").modal("hide");
                        $("#content").load("../tabs/user-profile-settings.php");
                        $(".modal-backdrop").hide();
                    } else {
                        $("#file").val("");
                        $(".progress-bar").width("0%");
                    }
                },
            });
        }
    });
});