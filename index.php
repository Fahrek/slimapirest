<!DOCTYPE html>

<html lang="es">

<head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>Cliente API Rest</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
              crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
              integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
              crossorigin="anonymous">

        <script
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
                integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
                crossorigin="anonymous"></script>

        <script>
            JSON.parse = JSON.parse || function (str) {
                if (str === "")
                    str = '""';
                eval("var p=" + str + ";");
                return p;
            };

            // READ
            $(document).ready(function () {
                function getProducts() {
                    $.ajax({
                        url: 'http://localhost/projects/slimapirest/api.php/products',
                        type: 'GET',
                        success: function (res) {
                            $.each(JSON.parse(res), function (i, index) {
                                if (index.id.length) {
                                    $(".table").append("<tr><td>" + index.id + "</td>" +
                                        "<td>" + index.name + "</td>" +
                                        "<td>" + index.des + "</td>" +
                                        "<td>" + index.price + "</td>" +
                                        "<td><span class='update btn btn-warning' data-producto='" + index.id + "'>Editar</span></td>" +
                                        "<td><span class='delete btn btn-danger'  data-producto='" + index.id + "'>Borrar</span></td></tr>"
                                    );
                                }
                            });

                            //DELETE
                            $('.delete').unbind('click').click(function () {
                                $.ajax({
                                    url: 'http://localhost/projects/slimapirest/api.php/products/' + $(this).data('producto'),
                                    type: 'DELETE',
                                    success: function () {
                                        $(".table").html(`<tr><th>ID</th><th>NOMBRE</th><th>DESC</th><th>PRECIO</th><th>EDITAR</th><th>ELIMINAR</th></tr>`);
                                        getProducts();
                                        $('#form').attr("data-producto", "0");
                                        $('#form')[0].reset();
                                    }
                                })
                            });

                            //UPDATE
                            $(".update").unbind("click").click(function () {
                                //alert($(this).parents("tr").find("td")[1].innerHTML);
                                //Tomo los elementos de la fila y los pongo en el formulario
                                $("#name_form").val($(this).parents("tr").find("td")[1].innerHTML);
                                $("#des_form").val($(this).parents("tr").find("td")[2].innerHTML);
                                $("#price_form").val($(this).parents("tr").find("td")[3].innerHTML);
                                $("#form").submit(function (e) {
                                    e.preventDefault();
                                    $.ajax({
                                        url: 'http://localhost/projects/slimapirest/api.php/products/' + $(this).data('producto'),
                                        type: 'PUT',
                                        data: {
                                            name: $("#name_form").val(),
                                            des: $("#des_form").val(),
                                            price: $("#price_form").val()
                                        },
                                        success: function () {
                                            $(".table").html(`<tr><th>ID</th><th>NOMBRE</th><th>DESC</th><th>PRECIO</th><th>EDITAR</th><th>ELIMINAR</th></tr>`);
                                            getProducts();
                                            $("#form")[0].reset();
                                        }
                                    });
                                    return false;
                                });
                            });
                        }
                    });
                }

                getProducts();
            });

            //CREATE
            if ($('#form').data('producto') === 0) {
                $('#form').submit(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'http://localhost/projects/slimapirest/api.php/products/',
                        type: 'POST',
                        data: {
                            name: $("#name_form").val(),
                            des: $("#des_form").val(),
                            price: $("#price_form").val()
                        },
                        success: function () {
                            $(".table").html(`<tr><th>ID</th><th>NOMBRE</th><th>DESC</th><th>PRECIO</th><th>EDITAR</th><th>ELIMINAR</th></tr>`);
                            getProducts();
                            $('#form')[0].reset();
                        }
                    });
                    return false;
                });
            } else {
                editProducts();
            }
        </script>
</head>


<body>

<h3>Cliente WEB API REST Slim</h3>

<div class="col-lg-7">
        <table class="table">
                <tr>
                        <td>ID</td>
                        <td>NOMBRE</td>
                        <td>DESC</td>
                        <td>PRECIO</td>
                        <td>EDITAR</td>
                        <td>ELIMINAR</td>
                </tr>
        </table>
</div>

<div class="col-lg-5 pull-right">
        <form id="form" action="http://localhost/projects/slimapirest/api.php/products" method="POST" data-producto="0">
                Nombre:
                <input type="text" id="name_form" name="name" class="form-control"><br>

                Descripcion:
                <textarea id="des_form" name="des" class="form-control"></textarea><br>

                Precio:
                <input type="text" id="price_form" name="price" class="form-control"><br>

                <input type="submit" value="guardar">
        </form>
</div>


</body>
</html>


<?php //require 'vendor/autoload.php';
//
//$app = new \Slim\Slim();
//
//$app->get("/hola/:nombre", function($nombre) use ($app) {
//    echo "Hola " . $nombre;
//    var_dump($app->request->params("hola"));
//});
//
//function pruebaMiddle() {
//    echo "Soy un middle";
//};
//
//function pruebaWare() {
//    echo "ware - ";
//};
//
//$app->get("/pruebas(/:uno(/:dos))", 'pruebaMiddle', 'pruebaWare', function($uno=NULL, $dos=NULL) {
//    echo $uno . "<br>";
//    echo $dos . "<br>";
//})->conditions(array(
//    "uno" => "[a-zA-Z]*",
//    "dos" => "[0-9]*"
//));
//
//$uri = "/slimapirest/index.php/api/ejemplo/";
//
//$app -> group("/api", function() use ($app, $uri) {
//
//    $app -> group("/ejemplo", function() use ($app, $uri) {
//
//        $app->get("/hola/:nombre", function($nombre) {
//            echo "Hola " . $nombre;
//        })->name("hola");
//
//        $app->get("/apellido/:apellido", function($apellido) {
//            echo "Tu apellido es: " . $apellido;
//        });
//
//        $app->get("/mandame-a-hola", function() use ($app, $uri) {
////          $app->redirect($uri . "hola/Andrés");
//            $app->redirect($app->urlFor("hola", [
//                "nombre" => "Andrés"
//            ]));
//        });
//
//    });
//
//});
//
//$app->run();
//?>
