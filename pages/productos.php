<?php
include '../scripts/config.php';
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
            echo "<p>" . $row['descripcion'] . "</p>";
            /*             echo "<p>ID: " . $row['id'] . "</p>"; */
            echo "<img src=\"../" . $row['imagen'] . "\" alt=\"" . $row['nombre'] . "\">";
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
</body>

</html>