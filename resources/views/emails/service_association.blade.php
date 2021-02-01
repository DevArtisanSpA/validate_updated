<DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
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

      p {
        font-size: 0.9rem;
      }
    </style>
    <title>Información: Asociación de servicio
    </title>
  </head>

  <body class="body">
    <div>
      <div class="div-img-logo">
        <img src="https://validate.cl/img/logo-validate-3.png" width="150" class="img-logo" alt="validate Logo">
      </div>
      <hr class="m-y-1" />
      <div style="text-align: left; ">
        <h2>
          Asociación de servicio
        </h2>

        <!-- <p> 
          Se ha asociado como empresa principal a <strong>< business_name principal ></strong> de la empresa contratista 
        </p>-->
        <p><br>
          Se ha asociado el servicio <strong>{{ strtoupper($service->description) }}</strong> entregado por
          la empresa contratista <strong>
            {{ strtoupper($service->company->business_name)}}
          </strong> a la empresa principal <strong>
            {{ strtoupper($service->branchOffice->company->business_name) }}
          </strong> para la sucursal <strong>
            {{ strtoupper($service->branchOffice->name) }}</strong>.
        </p>

        <br>
        <p class="m-y-1">
          Empresa contratista <strong>{{ strtoupper($service->company->business_name)}}</strong>
          , por favor contactarse con la administración de validate.cl (contacto@validate.cl) para solicitar la creación de
          usuario de acceso a la plataforma.
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