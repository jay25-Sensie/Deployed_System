
function confirmLogout(event) {
    // Prevent the default action of the link
    event.preventDefault();
    
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
    }
}
