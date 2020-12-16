<DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Te damos la bienvenida</title>
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
    </style>
  </head>

  <body class="body">
    <div>
      <div class="div-img-logo">
        <img src="https://validate.cl/img/logo-validate-3.png" width="150" class="img-logo" alt="validate Logo">
      </div>
      <hr class="m-y-1"  />
      <div style="text-align: left; ">
        <h2>
          ¡Hola {{ ucwords(strtolower($user->name)) }}!
        </h2>

        <p><br>
          Gracias por confiar en validate.cl. Para finalizar con el proceso de registro, por favor ingresar
          <a target="_blank" href="{{$url}}">aquí</a>.
        </p>
        <p class="m-y-1">
          También puedes copiar y pegar el siguiente enlace en su navegador de preferencia: {{$url}}.
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
