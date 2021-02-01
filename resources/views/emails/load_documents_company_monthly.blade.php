<DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Envio de documentos</title>
    <style>
      .footerright {
        margin-bottom: 10px;
        width: 100%;
        text-align: center;
      }

      .titulofooter {
        text-align: center;
        width: 100%;
      }

      .body {
        width: 60%;
        padding: 2rem 10%;
        font-family: "Roboto", "Arial";
      }

      h3,
      p {
        font-family: "Roboto", "Arial";
        color: '#202124';
      }

      .img-logo {
        background: white;
        border-radius: 1.2vw;
        margin-bottom: 15px;
      }

      .div-img-logo {
        text-align: center;
        margin-left: 2rem;
        margin-right: 2rem;
      }

      .m-y-2 {
        margin-top: 2rem;
        margin-bottom: 2rem;
      }

      .m-y-1 {
        margin-top: 1rem;
        margin-bottom: 1rem;
      }

      ul {
        list-style-type: disc;
        margin-bottom: 0.3em;
        margin-top: 0.3em;
      }

      h4 {
        margin-bottom: 0em;
      }
      p{ font-size: 0.9rem;}

    </style>
  </head>

  <body class="body">
    <div>
      <div class="div-img-logo">
        <img src="https://validate.cl/img/logo-validate-3.png" width="150" class="img-logo" alt="validate Logo">
      </div>
      <hr  class="m-y-1"  />
      <div style="text-align: left; ">
        <h2>
          Se han cargado los documentos mensuales para el periodo <strong>{{$periodo}}</strong>.
        </h2>                
        <p><br>
          Para el servicio  <strong>{{ strtoupper($service->description) }}</strong> de la empresa principal <strong>{{ strtoupper($service->branchOffice->company->business_name) }}</strong>, sucursal <strong>
            {{ strtoupper($service->branchOffice->name) }}</strong>, se han cargado los siguientes documentos fijos de
          la empresa contratista <strong>
            {{ strtoupper($service->company->business_name) }}
          </strong>que serán revisados por validate.cl:
        </p>

        <p class="m-y-1">
          <ul>
            @foreach ($documents as $document)
            <li>{{$document['name']}}</li>
            @endforeach
          </ul>
        </p>

        <hr class="m-y-2" />
        <div class="div-img-logo">
          <div class="titulofooter">
            <p style="font-size: smaller;">Copyright, 2020. validate.cl. Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
    </div>
  </body>

  </html>