<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Validate</title>
    <link rel="shortcut icon" href={{ asset('img/favicon.png') }}>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Styles -->

    {{--
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
@include('pdfs.style')

<body>
    <div class="row">
        <div class="col-11 mx-auto">
            <div class="row my-0">
                <!-- logo folio -->
                <div class="col-md-6 text-left">
                    <div class="div-img-logo">
                        <img src="{{ asset('img/logo.jpg') }}" width="200" alt="validate Logo">
                    </div>
                </div>
                <div class="col-md-6 pt-3 text-right">
                    <b>FOLIO</b> Nº {{$folio}}
                </div>
            </div>
            <h5 class="text-center mt-1 mb-3"><u><b>CERTIFICADO VALIDACIÓN EMPRESA
                    </b></u></h5>
            <table class="table table-sm" style="border-color: white">
                <thead>
                    <tr>
                        <th colspan="12" class="text-left bg-primary text-white m-0 py-1 px-2">DATOS EMPRESA
                            CONTRATISTA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-color: white">
                        <!-- NOMBRE Y RUT DE CONTRATISTA -->
                        <td colspan="1"><b>EMPRESA</b></td>
                        <td colspan="9">
                            <p class="ml-1 my-0">{{ strtoupper($contractor->business_name) }}</p>
                        </td>
                        <td colspan="1"><b>RUT</b></td>
                        <td>
                            <p class="my-0">{{ strtoupper($contractor->rut) }}</p>
                        </td>
                    </tr>
                    <tr style="border-color: white">
                        <!-- DIRECCION -->
                        <td colspan="1"><b>DIRECCIÓN</b></td>
                        <td colspan="11">
                            <p class="ml-1 my-0">{{ strtoupper($contractor->branchOffice['address']) }}</p>
                        </td>
                    </tr>
                    <tr style="border-color: white">
                        <!-- COMUNA/REGION/CONTACTO -->
                        <td colspan="1"><b>COMUNA</b></td>
                        <td colspan="5">
                            <p class="ml-1 my-0">{{ strtoupper($contractor->branchOffice['commune']['name']) }}</p>
                        </td>
                        <td colspan="1"><b>REGION</b></td>
                        <td colspan="5">
                            <p class="my-0">{{ strtoupper($contractor->branchOffice['commune']['region']['name']) }}</p>
                        </td>
                    </tr>
                    <tr style="border-color: white">
                        <!-- GIRO CONTACTO -->
                        <td colspan="1"><b>GIRO</b></td>
                        <td colspan="5">
                            <p class="ml-1 my-0">{{ strtoupper($contractor->commercial_business['name']) }}</p>
                        </td>
                        <td colspan="1"><b>CONTACTO</b></td>
                        <td colspan="5">
                            <p class="my-0">{{ strtoupper($contractor->contact_name) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm my-0" style="border-color: white">
                <tbody>
                    <tr>
                        <th colspan="12" class="text-left bg-primary text-white m-0 py-1 px-2">DATOS EMPRESA
                            PRINCIPAL</th>
                    </tr>
                    <tr style="border-color: white">
                        <!-- NOMBRE Y RUT DE CONTRATISTA -->
                        <td colspan="1"><b>EMPRESA PRINCIPAL</b></td>
                        <td colspan="11">
                            <p class="ml-1 my-0 text-left">{{ strtoupper($principal->business_name) }}
                            </p>
                        </td>
                    </tr>
                    <tr style="border-color: white">
                        <!-- DIRECCION -->
                        <td colspan="1"><b>DIRECCIÓN INSTALACIÓN</b></td>
                        <td colspan="11">
                            <p class="ml-1 my-0 text-left">{{ strtoupper($principal->branchOffice['address']) }}</p>
                        </td>
                    <tr style="border-color: white">
                        <!-- COMUNA/REGION/CONTACTO -->
                        <td colspan="1"><b>COMUNA</b></td>
                        <td colspan="5">
                            <p class="ml-1 my-0">{{ strtoupper($principal->branchOffice['commune']['name']) }}</p>
                        </td>
                        <td colspan="1"><b>REGION</b></td>
                        <td colspan="5">
                            <p class="my-0">{{ strtoupper($principal->branchOffice['commune']['region']['name']) }}</p>
                        </td>
                    </tr>
                    </tr>
                </tbody>
            </table>
            <p class="text-center mt-3 mb-2"><b>VALIDACIÓN COTIZACIONES PREVISIONALES /F30-1
                    /LIBRO DE REMUNERACIONES</b></p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary">
                        <th> </th>
                        <th>Nº TRABAJADORES</th>
                        <th>TOTAL IMPONIBLE</th>
                        <th>TOTAL LIQUIDO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>F30-1</td>
                        <td class="text-right">{{ $form->F30_1['workers'] }}</td>
                        <td class="text-right">{{ $form->F30_1['total_ini'] }}</td>
                        <td class="text-right">{{ $form->F30_1['total_end'] }}</td>
                    </tr>
                    <tr>
                        <td >L. DE REMUNERACIONES</td>
                        <td class="text-right">{{ $form->book['workers'] }}</td>
                        <td class="text-right">{{ $form->book['total_ini'] }}</td>
                        <td class="text-right">{{ $form->book['total_end'] }}</td>
                    </tr>
                    <tr>
                        <td>COTIZACIONES PREVIAS</td>
                        <td class="text-right">{{ $form->prev['workers'] }}</td>
                        <td class="text-right">{{ $form->prev['total_ini'] }}</td>
                        <td class="text-right">{{ $form->prev['total_end'] }}</td>
                    </tr>
                    <tr>
                        <td ><b>VALIDATE</b> </td>
                        <td class="text-right">{{ $form->validate['workers'] }}</td>
                        <td class="text-right">{{ $form->validate['total_ini'] }}</td>
                        <td class="text-right">{{ $form->validate['total_end'] }}</td>
                    </tr>
                </tbody>
            </table>

            <p class="text-center mt-3 mb-2"><b>RESUMEN MOVIMIENTOS PERSONAL</b></p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>FINIQUITOS</th>
                        <th>CONTRATOS</th>
                        <th>TRASLADOS/REEMPLAZOS</th>
                        <th>LICENCIAS</th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="text-center">
                        <td>{{ $form->summary['settlement'] }}</td>
                        <td>{{ $form->summary['contracts'] }}</td>
                        <td>{{ $form->summary['transfers'] }}</td>
                        <td>{{ $form->summary['licenses'] }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm table-primary pb-1" style="border-color: white">
                <thead>
                    <tr>
                        <th  class="table-primary text-center">
                            TOTAL TRABAJADORES VALIDADOS</th>
                        <th class="text-right">
                            <b>{{$form->end['periodo']}}</b>
                        </th>
                        <th class="text-right">
                            {{$form->end['total']}}</th>
                    </tr>
                </thead>
            </table>
            <div class="row my-0 mt-2">
                <!-- MENSAJE -->
                <div class="col-12">
                    <p class="mb-1">
                        CON FECHA: <b class="text-primary">{{ $today }}</b>
                    <p class="my-0 text-justify"><b>VALIDATE &#174;</b> CERTIFICA QUE LOS MONTOS CANCELADOS
                        POR CONCEPTO DE REMUNERACIONES CONCUERDAN CON LOS DECLARADOS
                        EN EL PAGO DE IMPOSICIONES.</p>
                    <p class="my-0 text-justify"><b>VALIDATE &#174;</b> CERTIFICA QUE LA DOCUMENTACIÓN
                        RECIBIDA FUE VALIDADA EN COMPARATIVA CON CERTIFICADO F30-1, REGISTRO DE ASISTENCIA, DEL MES
                        CUMPLIENDO TODOS LOS PARÁMETROS DE LA LEY DE SUBCONTRATACIÓN.
                    </p>
                </div>
            </div>
            {{--<div class="row">
                <table class="table mb-0 ml-0 mt-2 table-end">
                    <!-- FECHA DE EXPIRACIÓN -->
                    <tbody>
                        <tr>
                            <td><b class="p-1 m-0">PLAZO CERTIFICADO HASTA</b></td>
                            <td class="text-right"><b class="p-1 m-0">{{$expirationDate }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>--}}
            <div class="d-flex justify-content-center mt-5">
                <!-- FIRMA -->
                <div class="text-center">
                    <img src="https://validate.cl/img/logo-validate-3.png" width="200" class="img-logo"
                        alt="validate Logo">
                    <p class="m-0">Equipo VALIDATE &#174;</p>
                    <p class="m-0">Responsable Revisión</p>
                    <p class="m-0">Karina González</p>
                </div>
            </div>
        </div>
</body>

</html>
