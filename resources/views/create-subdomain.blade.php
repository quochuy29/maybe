<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container-sm">
        <h2 class="text-center">Create Subdomain</h2>
        <form action="{{route('store-subdomain')}}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label for="subdomain" class="form-label">Subdomain</label>
                <input type="text" name="subdomain" class="form-control" id="subdomain" placeholder="Please enter subdomain .....">
            </div>
            <div class="col-md-6">
                <label for="database" class="form-label">Database</label>
                <input type="text" name="db_name" class="form-control" id="database" placeholder="Please enter database .....">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</body>

</html>