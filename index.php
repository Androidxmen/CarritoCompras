<?php

    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();

    $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
    $sql->execute();
    $restult = $sql->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="C&oacute;digos de Programaci&oacute;n">
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://sistemarv.com/demo/tienda_online" />
    <meta property="og:title" content="Tienda Online CDP" />
    <meta property="og:description" content="Demo de Tienda Online desarrollada en PHP y MySQL." />
    <!--<meta property="og:image" content="https://store.codigosdeprogramacion.com/images/18/1.jpg">-->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4G5KB2ZVC2"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-4G5KB2ZVC2');
    </script>

    <title>Tienda en linea</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <!-- Menu de navegación -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">Tienda online</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarTop"
                    aria-controls="navBarTop" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navBarTop">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Catálogo</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="https://codigosdeprogramacion.com/contacto"
                                target="_blank">Contacto</a>
                        </li>
                    </ul>

                    <a class="btn btn-success me-3" href="https://store.codigosdeprogramacion.com/details.php?id=18"
                        target="_blank">
                        <i class="fas fa-arrow-circle-down"></i> Descarga proyecto
                    </a>

                    <a href="checkout.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Carrito <span id="num_cart"
                            class="badge bg-secondary">1</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">

            <!-- Contenido -->
            <main>
                <div class="container">

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                        <?php 
                            foreach($restult as $pro_row){ ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <?php
                            $id = $pro_row['id'];
                            $imagen = "resource/images/productos/". $id . "/principal.jpg";

                            if(!file_exists($imagen)){
                                $imagen = "resource/images/not-image.jpg";
                            }
                            ?>
                                <img src="<?php echo $imagen?>" class="card-img-top img-thumbnail">

                                <div class="card-body">
                                    <h6 class="card-title"><?php echo $pro_row['nombre']?></h6>
                                    <p class="card-text"><strong>$
                                            <?php echo number_format($pro_row['precio'], 2, '.', ',')?></strong></p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="" class="btn btn-primary">Detalles</a>
                                        </div>
                                        <a class="btn btn-success">Agregar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>


                    </div>
                </div>
            </main>

                    <!--Pagination-->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                crossorigin="anonymous"></script>
            <script>
            function addProducto(id, token) {
                var url = 'clases/carrito.php';
                var formData = new FormData();
                formData.append('id', id);
                formData.append('token', token);

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors',
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            let elemento = document.getElementById("num_cart")
                            elemento.innerHTML = data.numero;
                        }
                    })
            }
            </script>
</body>

</html>