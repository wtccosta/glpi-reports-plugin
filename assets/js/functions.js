function salvar() {
  var dados = $("#salvarEmpresa").serialize();
  $.ajax({
    type: "GET",
    dataType: "json",
    url: "../ajax/saveconfig.php",
    data: dados,
    beforeSend: function () {
      $(".success").html(
        '<div class="alert alert-info text-center">Salvando informações...</div>'
      );
    },
    success: function (res) {
      if (res.success == true) {
        console.log("Salvo com sucesso!");
        setTimeout(function () {
          $(".success").html(
            '<div class="alert alert-success text-center">Informações salvas com sucesso!</div>'
          );
        }, 1000);
        setTimeout(function () {
          location.reload();
        }, 1500);
      } else {
        console.log("Não foi possivel salvar!");
        setTimeout(function () {
          $(".success").html(
            '<div class="alert alert-danger text-center">Erro ao salvar informações!</div>'
          );
        }, 1000);
      }
    },
  });
  return false;
}

function saveReportData() {
  const dados = $("#report_content").serialize();
  fetch(
    window.location.origin+"/plugins/reports/ajax/savereport.php?" +
      new URLSearchParams(dados)
  ).then(() => {
    setTimeout(function () {
      $(".success").html(
        '<div class="alert alert-success text-center">Informações salvas com sucesso!</div>'
      );
    }, 1000);
    setTimeout(function () {
     location.reload();
    }, 1500);
  });
}

function printDiv(divName) {
  var printContents = document.getElementById(divName).innerHTML;
  console.log(printContents);
  var originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;

  window.print();

  document.body.innerHTML = originalContents;
}
