
function confirmLogout(event) {
    event.preventDefault();
    if (confirm("Are you sure you want to log out?")) {
        // Make an AJAX request to update the logout time
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_logout_time.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    window.location.href = "Patient_login.php";
                } else {
                    alert("Error: " + response.message);
                }
            }
        };
        xhr.send();
    }
}