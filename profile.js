// Ajax call to update username
//Once form is submitted
$("#updateUsernameForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#updateUsernameMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to signup.php using ajax
    $.ajax({
        url: "updateusername.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful: show error or success message
        success: function(data){
            $("#spinner").hide();
            if(data){
                $("#updateUsernameMessage").html(data);
                $("#updateUsernameMessage").slideDown();
            }else{
                location.reload();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#updateUsernameMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#updateUsernameMessage").slideDown();
        }
    });
});

// Ajax call to update email
//Once form is submitted
$("#updateEmailForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#updateEmailMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to signup.php using ajax
    $.ajax({
        url: "updateemail.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful: show error or success message
        success: function(data){
            $("#spinner").hide();
            if(data){
                $("#updateEmailMessage").html(data);
                $("#updateEmailMessage").slideDown();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#updateEmailMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#updateEmailMessage").slideDown();
        }
    });
});

// Ajax call to update password
//Once form is submitted
$("#updatePasswordForm").submit(function(event){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#updatePasswordMessage").hide();
//    prevent default php processing
    event.preventDefault();
//    collect user inputs
    var datatopost = $(this).serializeArray();
//    send them to signup.php using ajax
    $.ajax({
        url: "updatepassword.php",
        type: "POST",
        data: datatopost,
//        Ajax call successful: show error or success message
        success: function(data){
            $("#spinner").hide();
            if(data){
                $("#updatePasswordMessage").html(data);
                $("#updatePasswordMessage").slideDown();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#updatePasswordMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#updatePasswordMessage").slideDown();
        }
    });
});

//Update Picture Preview
var file; var imageType; var imageSize; var wrongType;
$("#picture").change(function(){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#updatePictureMessage").hide();
    
    file = this.files[0];
    imageType = file.type;
    imageSize = file.size;
    
//    Check Type
    var acceptableTypes = ["image/jpeg", "image/png", "image/jpg"];
    wrongType = ($.inArray(imageType, acceptableTypes) == -1);
    if(wrongType){
        $("#spinner").hide();
        $("#updatePictureMessage").html("<div class='alert alert-danger'>Only jpeg, png or jpg images are accepted!</div>");
        $("#updatePictureMessage").slideDown();
        return false;
    }
    
//    Check Size
    if(imageSize>3*1024*1024){
        $("#spinner").hide();
        $("#updatePictureMessage").html("<div class='alert alert-danger'>Please upload an image of less than 3mb</div>");
        $("#updatePictureMessage").slideDown();
        return false;
    }
    
//    The FileReader object will be used to convert our image to a binary string
    var reader = new FileReader();
//    callback
    reader.onload = updatePreview;
//    Start Read -> convert contents into data URL
    reader.readAsDataURL(file);
});

function updatePreview(event){
    $("#profilePic").attr("src", event.target.result);
}

//Update the Picture
$("#updatePictureForm").submit(function(){
//    Show Spinner
    $("#spinner").show();
//    Hide Results
    $("#updatePictureMessage").hide();
    event.preventDefault();
    //File Missing
    if(!file){
        $("#spinner").hide();
        $("#updatePictureMessage").html("<div class='alert alert-danger'>Please upload an image!</div>");
        $("#updatePictureMessage").slideDown();
        return false;
    }
    //Wrong Type
    if(wrongType){
        $("#spinner").hide();
        $("#updatePictureMessage").html("<div class='alert alert-danger'>Only jpeg, png or jpg images are accepted!</div>");
        $("#updatePictureMessage").slideDown();
        return false;
    }
    //File Too Big
    if(imageSize>3*1024*1024){
        $("#spinner").hide();
        $("#updatePictureMessage").html("<div class='alert alert-danger'>Please upload an image of less than 3mb</div>");
        $("#updatePictureMessage").slideDown();
        return false;
    }
    
    $.ajax({
        url: "updatepicture.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
//        Ajax call successful: show error or success message
        success: function(data){
            $("#spinner").hide();
            if(data){
                $("#updatePictureMessage").html(data);
                $("#updatePictureMessage").slideDown();
            }else{
                location.reload();
            }
        },
//        Ajax Call fails: show ajax call error
        error: function(){
            $("#spinner").hide();
            $("#updatePictureMessage").html("<div class='alert alert-danger'>There was an error with the Ajax call. Please try again</div>");
            $("#updatePictureMessage").slideDown();
        }
    });
    
});







