<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Style.css"> 
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Health Record Management</title>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="../img/logo.png" alt="Clinic Logo">
                <div class="logo-text">IMMACULATE<br>MEDICO-SURGICAL CLINIC</div>
            </div>
            <div class="login-buttons">
                <a href="Admin_Staff_login.php">Admin Staff's Login</a>
                <a href="Doctor_login.php">Doctor's Login</a>
                <a href="Patient_login.php">Patient's Login</a>
            </div>
        </div>

        <div class="title">
            Health Record Management<br> System with Medication Alert <br>Notification
        </div>

        <div class="scroll-container">
            <div class="image-grid">
                <div class="image-box" id="image-box-1">
                    <img src="..//img/image2.jpg" alt="Additional Image">
                    <img src="..//img/image.jpg" alt="Additional Image">
                    <img src="..//img/image1.jpg" alt="Additional Image">
                    <img src="..//img/image3.jpg" alt="Additional Image">
                </div>
                <div class="image-box" id="image-box-2">
                    <img src="..//img/image.jpg" alt="Additional Image">
                    <img src="..//img/image2.jpg" alt="Additional Image">
                    <img src="..//img/image3.jpg" alt="Additional Image">
                    <img src="..//img/image1.jpg" alt="Additional Image">
                </div>
                <div class="image-box" id="image-box-3">
                    <img src="..//img/image3.jpg" alt="Additional Image">
                    <img src="..//img/image1.jpg" alt="Additional Image">
                    <img src="..//img/image.jpg" alt="Additional Image">
                    <img src="..//img/image2.jpg" alt="Additional Image">
                </div>
                <div class="image-box" id="image-box-4">
                    <img src="..//img/image1.jpg" alt="Additional Image">
                    <img src="..//img/image3.jpg" alt="Additional Image">
                    <img src="..//img/image2.jpg" alt="Additional Image">
                    <img src="..//img/image.jpg" alt="Additional Image">
                </div>
            </div>
        </div>

        <div class="description">
            Welcome to the Health Record Management System. Our system provides a efficient way to manage your health records and receive medication alerts. Stay organized and informed with our user-friendly platform.
        </div>
        
        <div class="policies">
            <p>For more information, please read our <span style="color: #007bff;">Privacy Policy</span> and <span style="color: #007bff;">Terms of Service</span>.</p>
            <p>Contact us at <span style="color: #007bff;"><a href="@imsclinic.com">@imsclinic.com</a></span> for any inquiries.</p>
        </div>
    </div>

    <script>
        function rotateImages(imageBoxId) {
            const imageBox = document.getElementById(imageBoxId);
            const images = imageBox.getElementsByTagName('img');
            let currentIndex = 0;

            setInterval(() => {
                images[currentIndex].style.opacity = 0;
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].style.opacity = 1;
            }, 3000); // Change interval to 3 seconds
        }

        rotateImages('image-box-1');
        rotateImages('image-box-2');
        rotateImages('image-box-3');
        rotateImages('image-box-4');

    </script>
</body>
</html>