<script>
  function validarRut(rut) {
    var valor = rut.replace(".", "").replace("-", "");
    var cuerpo = valor.slice(0, -1);
    var dv = valor.slice(-1).toUpperCase();
    var suma = 0;
    var multiplo = 2;

    for (var i = 1; i <= cuerpo.length; i++) {
      var index = multiplo * valor.charAt(cuerpo.length - i);
      suma += index;
      if (multiplo < 7) {
        multiplo += 1;
      } else {
        multiplo = 2;
      }
    }

    var dvEsperado = 11 - (suma % 11);
    dv = dv === "K" ? 10 : dv;
    dv = dv === 0 ? 11 : dv;

    if (parseInt(dv, 10) !== parseInt(dvEsperado, 10)) {
      return false;
    }

    return true;
  }

  var rutInput = document.getElementById("rut");
  var rutValidationMessage = document.getElementById("rut-validation-message");

  rutInput.addEventListener("input", function () {
    var rut = rutInput.value;
    if (validarRut(rut)) {
      rutInput.setCustomValidity("");
      rutValidationMessage.textContent = "";
    } else {
      rutInput.setCustomValidity("RUT inválido");
      rutValidationMessage.textContent = "El RUT ingresado es inválido";
    }
  });
</script>
