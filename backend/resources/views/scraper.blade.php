<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>DSE | Dhaka Stock Exchange</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col my-5">
                <h1 class="mb-4">Dhaka Stock Exchange</h1>
                <table class="table table-striped table-hover">
                    @foreach($data as $row)
                    <tr>
                        @foreach ($row as $col)
                        <{{ $loop->parent->iteration == 1 ? "th" :"td"}}>
                            {{ $col }}
                        </{{ $loop->parent->iteration == 1 ? "th" :"td"}}>
                        @endforeach
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
