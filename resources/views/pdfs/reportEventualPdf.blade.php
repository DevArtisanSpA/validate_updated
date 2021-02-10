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

</head>
@include('pdfs.style')

<body style=" font-size: 0.8rem;">
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
                    <b>FOLIO</b> Nº 123456789
                </div>
            </div>
            <h5 class="text-center mt-1 mb-3"><u><b>INFORME DE VALIDACION EMPRESA TRABAJOS EVENTUALES
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
                1.- VALIDACIÓN DOCUMENTACIÓN MENSUAL EMPRESA <text
                    style="font-size:0.9em">{{ strtoupper($contractor->business_name) }}</text></p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead class="text-center">
                    <tr class="table-primary">
                        <th> </th>
                        <th colspan="3">ESTADO</th>
                        <th colspan="2">OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="text-left">CONTRATO COMERCIAL</td>
                        <td>{{ $form->contract['stateValidity'] }}</td>
                        <td>{{ $form->contract['stateSpecific'] }}</td>
                        <td>{{ $form->contract['stateObs'] }}</td>
                        <td>{{ $form->contract['obs'] }}</td>
                        <td>{{ $form->contract['obsFecha'] }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">AFILIACIÓN MUTUALIDAD</td>
                        <td>{{ $form->affiliation['stateValidity'] }}</td>
                        <td>{{ $form->affiliation['stateSpecific'] }}</td>
                        <td>{{ $form->affiliation['stateObs'] }}</td>
                        <td>{{ $form->affiliation['obs'] }}</td>
                        <td>{{ $form->affiliation['obsFecha'] }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">REGLAMENTO INTERNO</td>
                        <td>{{ $form->policy['stateValidity'] }}</td>
                        <td>{{ $form->policy['stateSpecific'] }}</td>
                        <td>{{ $form->policy['stateObs'] }}</td>
                        <td>{{ $form->policy['obs'] }}</td>
                        <td>{{ $form->policy['obsFecha'] }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">POLÍTICAS DE SEGURIDAD</td>
                        <td>{{ $form->regulation['stateValidity'] }}</td>
                        <td>{{ $form->regulation['stateSpecific'] }}</td>
                        <td>{{ $form->regulation['stateObs'] }}</td>
                        <td>{{ $form->regulation['obs'] }}</td>
                        <td>{{ $form->regulation['obsFecha'] }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">PLAN EN CASO DE EMERGENCIA</td>
                        <td>{{ $form->emergency['stateValidity'] }}</td>
                        <td>{{ $form->emergency['stateSpecific'] }}</td>
                        <td>{{ $form->emergency['stateObs'] }}</td>
                        <td>{{ $form->emergency['obs'] }}</td>
                        <td>{{ $form->emergency['obsFecha'] }}</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">PROGRAMA DE CAPACITACION</td>
                        <td>{{ $form->training['stateValidity'] }}</td>
                        <td>{{ $form->training['stateSpecific'] }}</td>
                        <td>{{ $form->training['stateObs'] }}</td>
                        <td>{{ $form->training['obs'] }}</td>
                        <td>{{ $form->training['obsFecha'] }}</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                2.- VALIDACIÓN DOCUMENTACIÓN TRABAJADORES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr>
                        <th colspan="3" style="border:unset"></th>
                        <th colspan="2" class="table-primary text-center">FECHA CONTRATO</th>
                        <th></th>
                    </tr>
                    <tr class="table-primary text-center">
                        <th class="text-left">NOMBRE TRABAJADOR</th>
                        <th>CARGO</th>
                        <th>RUT</th>
                        <th>INICIO</th>
                        <th>TERMINO</th>
                        <th>CI</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee['name'] }} {{ $employee['surname'] }}
                                {{ $employee['second_surname'] }}</td>
                            <td>{{ $employee['job_type']['name'] }}</td>
                            <td>{{ $employee['identification_id'] }}</td>
                            <td>{{ $employee['start'] }}</td>
                            <td>{{ $employee['finish'] }}</td>
                            <td>{{ $employee['ci'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                2.1.- VALIDACIÓN DOCUMENTACIÓN EHS TRABAJADORES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="table-primary text-center">
                        <th class="text-left">NOMBRE TRABAJADOR</th>
                        <th>RIOHS</th>
                        <th>EPP</th>
                        <th>DAS</th>
                        <th>OTROS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee['name'] }} {{ $employee['surname'] }}
                                {{ $employee['second_surname'] }}</td>
                            <td>{{ $employee['riohs'] }}</td>
                            <td>{{ $employee['epp'] }}</td>
                            <td>{{ $employee['das'] }}</td>
                            <td>{{ $employee['other'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                3.- VALIDACIÓN EXÁMENES OCUPACIONALES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr class="text-center">
                        <th style="border:unset"></th>
                        <th colspan="2" class="table-primary">EXAMEN BÁSICO</th>
                        <th colspan="2" class="table-primary">EX. ESP. CONFINADOS</th>
                        <th colspan="2" class="table-primary">EXAMEN ALTURA</th>
                    </tr>
                    <tr class="table-primary text-center">
                        <th class="text-left">NOMBRE TRABAJADOR</th>
                        <th>ESTADO</th>
                        <th>VIGENCIA</th>
                        <th>ESTADO</th>
                        <th>VIGENCIA</th>
                        <th>ESTADO</th>
                        <th>VIGENCIA</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee['name'] }} {{ $employee['surname'] }}
                                {{ $employee['second_surname'] }}</td>
                            <td>{{ $employee['basicState'] }}</td>
                            <td>{{ $employee['basicValidity'] }}</td>
                            <td>{{ $employee['confinedState'] }}</td>
                            <td>{{ $employee['confinedValidity'] }}</td>
                            <td>{{ $employee['heightState'] }}</td>
                            <td>{{ $employee['heightValidity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                4.- VALIDACION LICENCIAS /ACREDITACIONES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead>
                    <tr>
                        <th style="border:unset"></th>
                        <th colspan="2" class="table-primary text-center">LICENCIA</th>
                        <th></th>
                    </tr>
                    <tr class="table-primary text-center">
                        <th class="text-left">NOMBRE TRABAJADOR</th>
                        <th>ESTADO</th>
                        <th>VIGENCIA</th>
                        <th>OTROS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee['name'] }} {{ $employee['surname'] }}
                                {{ $employee['second_surname'] }}</td>
                            <td>{{ $employee['licenseState'] }}</td>
                            <td>{{ $employee['licenseValidity'] }}</td>
                            <td>{{ $employee['licenseOther'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-sm table-primary table-bordered" style="border-color: white">
                <thead>
                    <tr>
                        <th class="table-primary text-center">
                            TOTAL TRABAJADORES VALIDADOS</th>
                        <th class="text-right">
                            <b>{{ $form->end['periodo'] }}</b>
                        </th>
                        <th class="text-right">
                            {{ $form->end['total'] }}</th>
                    </tr>
                </thead>

            </table>
            <p class="text-justify mt-3 mb-2 b">
                5.- VALIDACION DOCUMENTOS MENSUALES EMPRESA</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead class="text-center">
                    <tr class="table-primary">
                        <th> </th>
                        <th>ESTADO</th>
                        <th colspan="2">OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>F30</td>
                        <td class="text-right">{{ $form->F30['state'] }}</td>
                        <td>AL</td>
                        <td class="text-right">{{ $form->F30['obs'] }}</td>
                    </tr>
                    <tr>
                        <td>CERTIFICADO SINIESTRALIDAD</td>
                        <td class="text-right">{{ $form->accident['state'] }}</td>
                        <td>AL</td>
                        <td class="text-right">{{ $form->accident['obs'] }}</td>
                    </tr>
                    <tr>
                        <td>CONSULTA DE MULTAS DT</td>
                        <td class="text-right">{{ $form->query['state'] }}</td>
                        <td>AL</td>
                        <td class="text-right">{{ $form->query['obs'] }}</td>
                    </tr>

                </tbody>
            </table>
            <p class="text-justify mt-3 mb-2 b">
                6.- VALIDACIÓN DE COTIZACIONES PREVISIONALES</p>
            <table class="table table-sm table-bordered">
                <!-- LISTA DE TRABAJADORES VALIDADOS -->
                <thead class="text-center">
                    <tr class="table-primary">
                        <th class="text-left">NOMBRE TRABAJADOR</th>
                        <th>MONTO</th>
                        <th>PERIODO</th>
                        <th>ESTADO</th>
                        <th>OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee['name'] }} {{ $employee['surname'] }}
                                {{ $employee['second_surname'] }}</td>
                            <td>{{ $employee['quotationAmount'] }}</td>
                            <td>{{ $employee['quotationPeriod'] }}</td>
                            <td>{{ $employee['quotationState'] }}</td>
                            <td>{{ $employee['quotationObs'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($form->obs) > 0)
                 <p class="text-justify mt-3 mb-2 b">
                    7.- OBSERVACIONES</p>
                <table class="table table-sm table-bordered">
                    <!-- LISTA DE TRABAJADORES VALIDADOS -->
                    <thead>
                        <tr class="table-primary text-center">
                            <th>OBSERVACIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($form->obs as $obs)
                            <tr class="text-justify">
                                <td>{{ $obs['text'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
