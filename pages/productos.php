<?php
include '../scripts/config.php';

// Función para obtener el número de productos en el carrito de cotización
function obtenerNumeroProductosCarrito()
{
  if (isset($_SESSION['carrito'])) {
    return count($_SESSION['carrito']);
  }
  return 0;
}

// Verificar si se ha enviado una solicitud para agregar un producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productoId'])) {
  // Obtener el ID del producto enviado
  $productoId = $_POST['productoId'];

  // Verificar si el producto ya está en el carrito
  $productoEnCarrito = false;
  if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
      if ($producto['id'] === $productoId) {
        $productoEnCarrito = true;
        break;
      }
    }
  }

  if (!$productoEnCarrito) {
    // Agregar el producto al carrito
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
      die("Error al conectar a la base de datos: " . $conn->connect_error);
    }

    $sql_producto = "SELECT id, nombre, descripcion, imagen FROM productos WHERE id = $productoId";
    $result_producto = $conn->query($sql_producto);

    if ($result_producto->num_rows > 0) {
      $row = $result_producto->fetch_assoc();
      $producto = [
        'id' => $row['id'],
        'nombre' => $row['nombre'],
        'descripcion' => $row['descripcion'],
        'imagen' => $row['imagen']
      ];

      // Agregar el producto al carrito de cotización
      if (isset($_SESSION['carrito'])) {
        $_SESSION['carrito'][] = $producto;
      } else {
        $_SESSION['carrito'] = [$producto];
      }

      $response = [
        'success' => true,
        'numProductos' => obtenerNumeroProductosCarrito()
      ];
      echo json_encode($response);
    } else {
      $response = [
        'success' => false,
        'error' => 'No se encontró el producto'
      ];
      echo json_encode($response);
    }

    $conn->close();
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carnes AG - Productos</title>
  <link rel="icon" href="../assets/logoBarra.png" type="image/png">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/productos.css">
  <!-- Agrega el enlace a Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Enlace a fuentes de Google -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  <header id="header">
    <div id="logo">
      <img src="../assets/logoAG.png" alt="Logo Carnes AG">
    </div>
    <nav>
      <ul>
        <li><a href="../index.html">INICIO</a></li>
        <li><a href="nosotros.html">NOSOTROS</a></li>
        <li><a href="productos.php">PRODUCTOS</a></li>
        <li><a href="sucursal.html">SUCURSAL</a></li>
        <li><a href="contacto.html">CONTACTO</a></li>
      </ul>
    </nav>
    <!-- Mostrar el número de productos en el carrito de cotización -->
    <div id="carrito-container">
      <a href="carrito.php">
        <i class="fas fa-shopping-cart"></i>
        <span id="carrito-num-productos"><?php echo obtenerNumeroProductosCarrito(); ?></span>
      </a>
    </div>
  </header>

  <main>

    <div class="categorias-container">
      <h2>Categorías</h2>
      <nav>
        <ul class="categorias">
          <?php
          // Conexión a la base de datos
          $conn = new mysqli($servername, $username, $password, $database);

          if ($conn->connect_error) {
            die("Error al conectar a la base de datos: " . $conn->connect_error);
          }

          // Consulta a la base de datos para obtener las categorías de productos
          $sql_categorias = "SELECT id, nombre FROM categorias";
          $result_categorias = $conn->query($sql_categorias);

          // Mostrar las categorías de productos
          if ($result_categorias->num_rows > 0) {
            while ($row_categoria = $result_categorias->fetch_assoc()) {
              echo "<li><a href='productos.php?categoria=" . $row_categoria['id'] . "'>" . ucwords($row_categoria['nombre']) . "</a></li>";
            }
          }

          $conn->close();
          ?>
        </ul>
      </nav>
    </div>

    <div class="productos-container">
      <?php
      // Conexión a la base de datos
      $conn = new mysqli($servername, $username, $password, $database);

      if ($conn->connect_error) {
        die("Error al conectar a la base de datos: " . $conn->connect_error);
      }

      // Verificar si se ha seleccionado una categoría
      if (isset($_GET['categoria'])) {
        // Obtener el ID de la categoría seleccionada desde la URL
        $categoria_seleccionada = $_GET['categoria'];

        // Consulta a la base de datos para obtener los productos de la categoría seleccionada
        $sql_productos = "SELECT id, nombre, descripcion, imagen FROM productos WHERE categoria_id = $categoria_seleccionada";
        $result_productos = $conn->query($sql_productos);

        if ($result_productos->num_rows > 0) {
          echo "<h1>Nuestros Productos</h1>";
          echo "<div class='productos-grid'>"; // Aplicar la clase directamente aquí
          while ($row = $result_productos->fetch_assoc()) {
            echo "<div class='producto'>";
            echo "<h2>" . ucwords($row['nombre']) . "</h2>";
            echo "<img src=\"../" . $row['imagen'] . "\" alt=\"" . $row['nombre'] . "\">";
            echo "<p>" . $row['descripcion'] . "</p>";
            echo "<button class='btn-agregar-carrito' data-producto-id='" . $row['id'] . "'>Agregar al carrito</button>";
            echo "</div>";
          }
          echo "</div>";
        } else {
          echo "<p>No se encontraron productos en esta categoría.</p>";
        }
      } else {
        echo "<h1>Seleccione una categoría</h1>";
      }

      $conn->close();
      ?>
    </div>

  </main>

  <footer>
    <p>&copy; 2023 Carnes AG. Todos los derechos reservados.</p>
  </footer>
  <div class="footer-secondary">
    <div class="footer-column">
      <nav>
        <ul>
          <li><a href="../index.html">INICIO</a></li>
          <li><a href="nosotros.html">NOSOTROS</a></li>
          <li><a href="productos.php">PRODUCTOS</a></li>
          <li><a href="sucursal.html">SUCURSAL</a></li>
          <li><a href="contacto.html">CONTACTO</a></li>
        </ul>
      </nav>
    </div>
    <div class="footer-column center-column">
      <div class="footer-logo">
        <img src="../assets/logoAG.png" alt="Logo Carnes AG">
      </div>
    </div>

    <div class="footer-column">
      <div class="footer-info">
        <h3>INFORMACIONES</h3>
        <p>contacto@carnesag.cl</p>
        <p>Teléfono: 32 2 213124</p>
        <p>Juana Ross 155, Valparaíso</p>
        <div class="gps-icon">
          <a href="https://goo.gl/maps/FPyb7idvQ7upPeX79" target="_blank" rel="noopener noreferrer">
            <i class="fas fa-map-marker-alt"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Manejar el clic del botón "Agregar al carrito"
    $(document).on('click', '.btn-agregar-carrito', function() {
      var productoId = $(this).data('producto-id');
      $.ajax({
        url: 'productos.php',
        method: 'POST',
        data: {
          productoId: productoId
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            $('#carrito-num-productos').text(response.numProductos);
            alert('El producto se ha agregado al carrito.');
          } else {
            alert(response.error);
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
        }
      });
    });
  </script>
</body>

</html>