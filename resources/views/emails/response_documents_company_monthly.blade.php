<DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <title>Actualización de estados de los documentos</title>
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

            p {
                font-size: 0.9rem;
            }
        </style>
    </head>

    <body class="body">
        <div>
            <div class="div-img-logo">
                <img src="https://validate.cl/img/logo-validate-3.png" width="150" class="img-logo" alt="validate Logo">
            </div>
            <hr class="m-y-1" />
            <div style="text-align: left; ">
                <h2>
                    Se han actualizado los estados de los documentos para el periodo <strong>{{$periodo}}</strong>..
                </h2>
                <p><br>
                    Estimados, les informamos que los documentos base del servicio
                    <strong>{{ strtoupper($service->description) }}</strong>
                    prestado por la empresa contratista <strong>{{ strtoupper($service->company->business_name) }},</strong>
                    para la empresa principal <strong>
                        {{ strtoupper($service->branchOffice->company->business_name) }}</strong>, sucursal <strong>
                        {{ strtoupper($service->branchOffice->name) }}</strong> han sido revisados y actualizados en sus estados.
                </p>

                @if($wrong)
                <h4 class="alert alert-danger">{{strtoupper('Existen documentos rechazados')}}</h4>
                @else
                <h4 class="alert alert-success">{{strtoupper('Todos los documentos han sido aprobados') }}</h4>
                @endif
                <p class="m-y-1">
                <table class="egt" cellspacing="5" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th class="text-center">Estatus</th>
                            <th>Comentarios</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                        <tr>
                            <td>{{$document['type']['name']}}</td>
                            <td class="text-center">
                                @if($document['validation_state_id']==3)
                                <span class="icon-success" style="font-size: 1.5rem">&#10004;</span>
                                @elseif($document['validation_state_id']==4)
                                <span class="icon-danger" style="font-size: 1.5rem">&#10007;</span>
                                @endif
                            </td>
                            <td>
                                @if($document['validation_state_id']==3|| strlen(trim($document['observations']))==0)
                                -
                                @elseif($document['validation_state_id']==4)
                                {{$document['observations']}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
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