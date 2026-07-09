
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to N1 RentalPanel</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #4e54c8, #8f94fb);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .welcome-container {
            text-align: center;
            animation: fadeIn 2s ease-in-out;
        }

        .welcome-container h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            animation: slideIn 1.5s ease-out;
        }

        .welcome-container p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            animation: slideIn 1.8s ease-out;
        }

        .welcome-container button {
            background-color: #fff;
            color: #4e54c8;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .welcome-container button:hover {
            transform: scale(1.1);
            background-color: #8f94fb;
            color: #fff;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .welcome-container h1 {
                font-size: 2rem;
            }

            .welcome-container p {
                font-size: 1rem;
            }

            .welcome-container button {
                font-size: 0.9rem;
                padding: 0.7rem 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to N1 RentalPanel Admin Panel!</h1>
        <p>We are delighted to have you onboard! Click the button below to proceed to your dashboard and explore the powerful features of N1 RentalPanel.</p>
       <a class="btn btn-warning" href="<?=site_url("admin")?>"> <button>Next</button> </a>
    </div>

    
</body>
</html>










































<script>
 function copyToClipboard(text) {
    if (window.clipboardData && window.clipboardData.setData) {
        // Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
        return window.clipboardData.setData("Text", text);

    }
    else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        var textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in Microsoft Edge.
        document.body.appendChild(textarea);
        textarea.select();
        try {
            return document.execCommand("copy");  // Security exception may be thrown by some browsers.
        }
        catch (ex) {
            console.warn("Copy to clipboard failed.", ex);
            return prompt("Copy to clipboard: Ctrl+C, Enter", text);
        }
        finally {
            document.body.removeChild(textarea);
        }
    }
}

$(document).ready(function(){

$("form").submit(function(e){
    e.preventDefault();
 var secret_key = $("#secret_key").val();
 var _2fa_code = $("#2FA_Code").val();
 var error = $(".error");
 $.ajax({
  url: "<?=site_url("admin/activate-google-2fa")?>",
  data:"secret_key="+secret_key+"&2FA_Code="+_2fa_code,
  type:"POST",
  success:function(response){
   var response = JSON.parse(response);
   
   
   if(response.success == false){
     error.html('<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>'+response.message+'</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
   }
   if(response.success == true){
     error.html('<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>'+response.message+'</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
     window.location.href = "/admin";
   }
  }
 });
});

});


</script>
  </body>
</html>


