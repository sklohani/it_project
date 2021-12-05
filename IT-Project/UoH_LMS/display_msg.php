<?php
    function show_error_msg($msg)
    {
        return '<script>
            document.getElementById("error").innerHTML = "'.$msg.'";
            document.getElementById("error-message").style.display = "block";
            document.getElementById("error").style.color = "red";
        </script>';
    }

    function show_success_msg($msg)
    {
        return '<script>
            document.getElementById("error").innerHTML = "'.$msg.'";
            document.getElementById("error-message").style.display = "block";
            document.getElementById("error").style.color = "green";
        </script>';
    }
?>