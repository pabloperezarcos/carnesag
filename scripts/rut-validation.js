function validarRut(rut) {
  var valor = rut.value.replace(".", "").replace("-", "");
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
  dvEsperado = dvEsperado === 11 ? 0 : dvEsperado === 10 ? "K" : dvEsperado;

  return parseInt(dv, 10) === parseInt(dvEsperado, 10);
}

var rutInput = document.getElementById("rut");
var rutValidationMessage = document.getElementById("rut-validation-message");

rutInput.addEventListener("input", function () {
  if (validarRut(this)) {
    this.setCustomValidity("");
    rutValidationMessage.textContent = "";
  } else {
    this.setCustomValidity("RUT inválido");
    rutValidationMessage.textContent = "El RUT ingresado es inválido";
  }
});
