@extends('layouts.app')
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

    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
@include('pdfs.style')

<body>
    <div class="row">
        <div class="col-11 mx-auto">
            <div class="row my-0">
                <!-- logo folio -->
                <div class="col-md-6 text-left">
                    <div class="div-img-logo">
                        <img src="https://validate.cl/img/logo-validate-3.png" width="200" alt="validate Logo">
                    </div>
                </div>
                <div class="col-md-6 pt-3 text-right">
                    <b>FOLIO</b> Nº {{$folio}}
                </div>
            </div>
            <h5 class="text-center mt-1 mb-3"><u><b>INFORME VALIDACIÓN EMPRESA
                        {{ strtoupper('') }}</b></u></h5>
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
                            <p class="my-0">{{ strtoupper($contractor->branchOffice['commune']['region']['name']) }}
                            </p>
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
                            <p class="my-0">{{ strtoupper($principal->branchOffice['commune']['region']['name']) }}
                            </p>
                        </td>
                    </tr>
                    </tr>
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                1.- VALIDACIÓN DOCUMENTACIÓN MENSUAL EMPRESA</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary">
                        <th> </th>
                        <th>ESTADO</th>
                        <th>OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>F30</td>
                        <td class="text-center">{{ $form->F30['state'] }}</td>
                        <td class="text-right">{{ $form->F30['obs'] }}</td>
                    </tr>
                    <tr>
                        <td>CONSULTA MULTAS DT</td>
                        <td class="text-center">{{ $form->query['state'] }}</td>
                        <td class="text-right">{{ $form->query['obs'] }}</td>
                    </tr>
                    <tr>
                        <td>CERTIFICADO SINIESTRALIDAD</td>
                        <td class="text-center">{{ $form->accident['state'] }}</td>
                        <td class="text-right">{{ $form->accident['obs'] }}</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                2.- VALIDACIÓN REMUNERACIONES TRABAJADORES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary">
                        <th>NOMBRE</th>
                        <th>DÍAS</th>
                        <th>HHEE</th>
                        <th>IMPONIBLE</th>
                        <th>AFP</th>
                        <th>SALUD</th>
                        <th>PRÉSTAMO</th>
                        <th>LIQUIDO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="text-right">
                        <td class="text-justify">{{ $employee['name'] }} {{ $employee['surname'] }} {{ $employee['second_surname'] }}</td>
                        <td>{{ $employee['days'] }}</td>
                        <td>{{ $employee['hhee'] }}</td>
                        <td>{{ $employee['payment'] }}</td>
                        <td>{{ $employee['afp'] }}</td>
                        <td>{{ $employee['health'] }}</td>
                        <td>{{ $employee['loan'] }}</td>
                        <td>{{ $employee['liquid'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center"><b>VALIDACIÓN IMPONIBLE</b></td>
                        <td class="text-right"><b>{{$form->payment_employee}}</b></td>
                        <td colspan="3" class="text-center"><b>VALIDACIÓN LIQUIDO</b></td>
                        <td class="text-right"><b>{{ $form->liquid_employee }}</b></td>
                    </tr>
                </tbody>
            </table>
             <p class="text-justify mt-3 mb-2 b">
                3.- VALIDACIÓN MONTOS F30-1/LIBRO DE REMUNERACIONES</p>
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
            <p class="text-justify mt-3 mb-2 b">
                4.- MOVIMIENTOS DE PAGO</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>PERIODO </th>
                        <th>ADELANTOS</th>
                        <th>SUELDOS</th>
                        <th>COTIZACIONES PREV</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td>{{$form->payment['period']}}</td>
                        <td>{{$form->payment['adv']}}</td>
                        <td>{{$form->payment['salary']}}</td>
                        <td>{{$form->payment['prev']}}</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                5.- VALIDACIÓN LIBRO DE ASISTENCIA</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary ">
                        <th>NOMBRE</th>
                        <th>N°FOLIO</th>
                        <th>INASISTENCIA</th>
                        <th>HHEE</th>
                        <th>OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="text-right">
                        <td>{{ $employee['name'] }} {{ $employee['surname'] }} {{ $employee['second_surname'] }}</td>
                        <td>{{  $employee['nFolio'] }}</td>
                        <td>{{  $employee['absence'] }}</td>
                        <td>{{  $employee['hheeBook'] }}</td>
                        <td>{{  $employee['obs'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
           <p class="text-justify mt-3 my-0 b">6.- MOVIMIENTOS PERSONAL</p>
            <p class="text-justify mt-1 mb-2 b pl-0" style="font-size:0.9em">6.1 FINIQUITOS</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>NOMBRE</th>
                        <th>MOTIVO</th>
                        <th>NOTIFICACIÓN DT/CARTA</th>
                        <th>PREVIRED</th>
                        <th>FINIQUITO LEGALIZADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->settlements as $settlement)
                    <tr class="text-center">
                        <td class="text-justify">{{ $settlement['employee']['name'] }} {{ $settlement['employee']['surname'] }}
                            {{ $settlement['employee']['second_surname'] }}
                        </td>
                        <td>{{ $settlement['reason'] }}</td>
                        <td>{{ $settlement['letter'] }}</td>
                        <td>{{ $settlement['previred'] }}</td>
                        <td>{{ $settlement['legalized'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
             <p class="text-justify mt-3 mb-2 b pl-0" style="font-size:0.9em">6.2 CONTRATOS</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>NOMBRE</th>
                        <th>INGRESO</th>
                        <th>TERMINO</th>
                        <th>CI</th>
                        <th>RI</th>
                        <th>EPP</th>
                        <th>DAS</th>
                        <th>OTROS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->contracts as $contract)
                    <tr class="text-center">
                        <td class="text-justify">{{ $contract['employee']['name'] }} {{ $contract['employee']['surname'] }}
                            {{ $contract['employee']['second_surname'] }}
                        </td>
                        <td>{{ $contract['start'] }}</td>
                        <td>{{ $contract['finish'] }}</td>
                        <td>{{ $contract['ci'] }}</td>
                        <td>{{ $contract['ri'] }}</td>
                        <td>{{ $contract['epp'] }}</td>
                        <td>{{ $contract['das'] }}</td>
                        <td>{{ $contract['other'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b pl-0" style="font-size:0.9em">6.3 TRASLADOS/REEMPLAZOS</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>NOMBRE</th>
                        <th>INGRESO</th>
                        <th>TERMINO</th>
                        <th>CI</th>
                        <th>RI</th>
                        <th>EPP</th>
                        <th>DAS</th>
                        <th>OTROS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->transfers as $transfer)
                    <tr class="text-center">
                        <td class="text-justify">{{ $transfer['employee']['name'] }} {{ $transfer['employee']['surname'] }}
                            {{ $transfer['employee']['second_surname'] }}
                        </td>
                        <td>{{ $transfer['start'] }}</td>
                        <td>{{ $transfer['finish'] }}</td>
                        <td>{{ $transfer['ci'] }}</td>
                        <td>{{ $transfer['ri'] }}</td>
                        <td>{{ $transfer['epp'] }}</td>
                        <td>{{ $transfer['das'] }}</td>
                        <td>{{ $transfer['other'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b pl-0" style="font-size:0.9em">6.4 LICENCIAS</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>NOMBRE</th>
                        <th>FECHA LICENCIA</th>
                        <th>N° LICENCIA</th>
                        <th>N° DÍAS</th>
                    </tr>
                </thead>
                @foreach ($form->licenses as $license)
                <tr class="text-center">
                    <td class="text-justify">{{ $license['employee']['name'] }} {{ $license['employee']['surname'] }}
                        {{ $license['employee']['second_surname'] }}
                    </td>
                    <td>{{ $license['start'] }}</td>
                    <td>{{ $license['license'] }}</td>
                    <td>{{ $license['days'] }}</td>
                </tr>
                @endforeach
                <tbody>
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">7.- RESUMEN MOVIMIENTOS PERSONAL</p>
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
                        <td>{{ $form->summary['settlements'] }}</td>
                        <td>{{ $form->summary['contracts'] }}</td>
                        <td>{{ $form->summary['transfers'] }}</td>
                        <td>{{ $form->summary['licenses'] }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm table-primary" style="border-color: white">
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
            <p class="text-justify mt-3 mb-2 b">8.- OBSERVACIONES DEL PERIODO</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th>DOCUMENTOS/PROCESOS</th>
                        <th>OBSERVACIONES</th>
                        <th>CONDICIONAL DE PAGO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($form->obs as $obs)

                    <tr class="text-justify">
                        <td>{{$obs['doc']}}</td>
                        <td>{{$obs['obs']}}</td>
                        <td>{{$obs['if']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>
</body>

</html>
