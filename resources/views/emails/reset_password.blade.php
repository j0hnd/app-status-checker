<html lang="en">
<head>
    <title>IXU - Reset Password</title>
    <style>
        .wrapper p {
            font-size: 14px;
            font-family: "Verdana, Helvetica, Sans Serif";
        }

        .wrapper span {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <p>Hi {{ ucwords($firstname) }},</p>
        <p>Temporary Password: <span>{{ $temporary_password }}</span></p>
        <p>Immediately change your temporary password by logging in to the system.</p>
    </div>
</body>
</html>
