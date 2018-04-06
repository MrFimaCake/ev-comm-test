<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/floating-labels.css" crossorigin="anonymous">
</head>

<body>
    <div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Error</h1>
        <?php if(isset($errorMessage)): ?>
        <h5><?php echo $errorMessage; ?></h5>
        <a href="/">Main</a>
        <?php endif; ?>
    </div>
</body>
</html>