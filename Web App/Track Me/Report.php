<?php session_start() ?>
<?php
    if (empty($_SESSION["UserID"])){
        session_unset();
 session_destroy  ();
    header('location:index.html');

       }

       ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
   	<meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Track Me &mdash; Tracking System</title>
    <link rel="stylesheet" type="text/css" href="css\b.css">
    <link rel="stylesheet" type="text/css" href="css\my-login.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" >

 <style type="text/css">
.logo{

       padding: 0.01em 14px;
          font-family: fontawesome;
    text-decoration: none;
    line-height: 1;

    font-size: 36px;
    letter-spacing: 3px;
    color: #555555;
    display: block;

    top: 14px;


}
   .logo .green{

        color: #4CAF50;
    }
.sub-logo{
    width: 100%;
    font-size: 10px;
    text-align: center;
    float: right!important;
    letter-spacing: 4px;
    display: block;
    }
</style>
</head>
<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100" style="display: flex;justify-content: space-around; ">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper"  style="width: 95%;">



                    <div class="card fat" style="margin-top: 35px;border-color: greenyellow;box-shadow: 0 0 20px 1px #A8A8A8">
                         <a class="logo mt-4 text-center">
                          <img src="Image\Main icon.png" width="50" height="50" style="border: 0" alt="">Track  <span class="green">Me</span></a>

  <div class="sub-logo mb-4" style="font-family:'Segoe UI',Arial,sans-serif;margin-bottom: 0.1rem!important;">Realtime Tracking System</div>

                        <div class="card-body">
                            <h4 class="card-title">Send Feedback</h4>
                            <form name="myform">

                                          <p>If you're having any problem with Track Me, or want to tell us your thoughts about how it's working, you can send a feedback report to us. </p>

                                 <div class="form-group">

                                    <textarea id="" type="text" class="form-control" rows="2" name="feedback"
                                     placeholder="Describe your issue or share your ideas" ></textarea>
                                </div>
                                         <hr style="width: 100%; height: 5px">

                                 <input hidden="hidden" value="<?php echo $_SESSION["UserID"] ?>" name="UserID">
                                  <h6> What happen with your feedback</h6>

<p>We can review the feedback you send and use it to improve the Track Me Application for everyone.
<b>Important:</b> We can't always respond to your feedback, but it doesn't mean that. It hasn't been reviewed.</p>
                          <hr style="width: 100%; height: 5px">
                             <div class="form-group no-margin">

                                    <button type="submit" class="btn btn-primary btn-block" id="reg"
                                     >
                                        Submit
                                    </button>



                                </div>

                            </form>
                        </div>
                    </div>
                   <!-- <button class="btn btn-primary btn-block" id="reg" onclick="loading()"      >
                                        Register
                                    </button>-->
                    <div class="footer">
                       	Copyright &copy 2019 by Lasitha. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>
<script src="js/jq.js"></script>
    <script src="js/b.js"></script>
    <script src="js/my-login.js"></script>

<script>





 $(function() {

    /*  "return validation()" */


  $('form').on('submit', function (e) {

          e.preventDefault();

   $.ajax({

   dataType: 'json',
    cache: false,
  type:'POST',
  beforeSend: function(){
    loading(true);
  },

  url:'Add_Report.php',
  data:  $('form').serialize(),

   success:function(data){



 if(data.success=="Submitted successfully"){
      alert("Submitted successfully! Thank You for your feedback.");
      window.location.href = "View.php";
   exit();
  }else{
      alert("Sorry ! Something Wrong ! .");
  }


  } , error: function(xhr,stat , error){
       alert("An error occured: " + xhr.status + " " + error);


    }



  ,

  complete: function(){

    loading(false);
  }
});


});

});


 function loading(b){

   if (b==true){
    $("#reg").prop('disabled', true);
    $('.form-control').prop('disabled', true);
 $("#reg").html("<i class='fa fa-circle-o-notch fa-spin'></i>Sending Data..");

/* setTimeout(function() {
    $("#reg").prop('disabled', false);
    $('.form-control').prop('disabled', false);
 $("#reg").html("Register");
    }, 15000);*/


   }else{
    $("#reg").prop('disabled', false);
    $('.form-control').prop('disabled', false);
    $("#reg").html("Submit");
   }
 }





</script>

</html>