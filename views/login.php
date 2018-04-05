<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/floating-labels.css" crossorigin="anonymous">
</head>

<body>
    <form class="form-signin" method="post">
        <div class="text-center mb-4">
            <h1 class="h3 mb-3 font-weight-normal">Comments service</h1>
        </div>

        <div class="form-label-group">
            <input type="text" 
                   id="inputUsername" 
                   class="form-control" 
                   name="CreateUser[username]"
                   placeholder="Username" 
                   required="" 
                   autofocus="">
            <label for="inputUsername">Username</label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
    </div>

</body>
</html>