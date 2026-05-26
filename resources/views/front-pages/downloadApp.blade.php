<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <script>
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        if (/android/i.test(userAgent)) {
            window.location.href = "https://play.google.com/store/apps/details?id=com.hariri.app";
        } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            window.location.href = "https://play.google.com/store/apps/details?id=com.hariri.app";
        } else {
            window.location.href = "https://play.google.com/store/apps/details?id=com.hariri.app"; // رابط افتراضي
        }
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
