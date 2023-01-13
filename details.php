<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la peticion';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT nombre, descripcion, precio, upc, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $pro_row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $pro_row['nombre'];
            $precio = $pro_row['precio'];
            $descripcion = $pro_row['descripcion'];
            $upc = $pro_row['upc'];
            $descuento = $pro_row['descuento'];
            $desc_precio = $precio - (($precio * $descuento) / 100);
            $dir_images = 'resource/images/productos/' . $id . '/';
            $rutaImg = $dir_images . 'principal.jpg';

            if (!file_exists($rutaImg)) {
                $rutaImg = "resource/images/not-image.jpg";
            }

            $imagenes = array();

            if (file_exists($dir_images)) {

                $dir = dir($dir_images);

                while (($archivo = $dir->read()) != false) {

                    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }
        }
    } else {
        echo 'Error al procesar la peticion';
        exit;
    }
}




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

    <title>Tienda en linea</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <!-- Menu de navegación -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">Tienda online</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarTop" aria-controls="navBarTop" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navBarTop">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Catálogo</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="https://codigosdeprogramacion.com/contacto" target="_blank">Contacto</a>
                        </li>
                    </ul>

                   

                    <a href="checkout.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Carrito <span id="num_cart" class="badge bg-secondary"><?php echo $num_carrito;?></span>
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

                    <div class="row">
                        <div class="col-md-6 order-md-1">
                            <!--Carrusel-->
                            <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!--Imagenes-->
                                    <div class="carousel-item active">

                                        <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
                                    </div>
                                    <?php foreach ($imagenes as $img) { ?>
                                        <div class="carousel-item">
                                            <img src="<?php echo $img; ?>" class="d-block w-100">
                                        </div>
                                    <?php } ?>



                                    <!--Imagenes-->
                                </div>

                                <!--Controles-->
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                                <!--Controles carrusel-->
                            </div>
                            <!--Carrusel-->
                        </div>

                        <div class="col-md-6 order-md-2">
                            <h2><?php echo $nombre; ?></h2>
                            <input type="hidden" name="token" value="026a8089ff9ce1c296464138aad4080fe96bd25e"></h2>

                            <?php if ($descuento > 0) { ?>
                                <p>DE: <del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                                <h2>A <?php echo MONEDA . number_format($desc_precio, 2, '.', ','); ?> CON EL: <small class=" text-success"><?php echo $descuento; ?>% de descuento</small></h2>
                            <?php  } else { ?>
                                <h2>A <?php echo MONEDA . number_format($precio, 2, '.', ','); ?> </h2>
                            <?php  } ?>


                            <p><?php echo $descripcion; ?></p>

                            <div class="col-3 my-3">
                                <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                            </div>

                            <div class="d-grid gap-3 col-7">
                                <button class="btn btn-outline-primary" type="button" onClick="addProducto('<?php echo $id;?>','<?php echo $token_tmp;?>')">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
            <script>
                
                function addProducto(id, token){
                    let url = 'clases/carrito.php';
                    let formData = new FormData();
                    formData.append('id', id)
                    
                    console.log(formData.append('token', token))

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors'
                    }).then(response => response.json()).then(data =>{
                        if(data.ok){
                            let elemento = document.getElementById('num_cart')
                            elemento.innerHTML = data.numero
                        }
                    })
                }
            
            </script>
</body>

</html>