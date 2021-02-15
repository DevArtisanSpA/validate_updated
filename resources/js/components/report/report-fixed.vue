<template>
  <div class="row container" style="font-size: 80%; background: ghostwhite">
    <div class="col-11 mx-auto">
      <div class="row my-0">
        <!-- logo folio -->
        <div class="col-md-6 text-left">
          <div class="div-img-logo">
            <img
              src="https://validate.cl/img/logo-validate-3.png"
              width="200"
              alt="validate Logo"
            />
          </div>
        </div>
        <div class="col-md-6 pt-3 text-right">
          <b>FOLIO</b> Nº
          <input
            placeholder="0"
            class="text-center"
            v-model="data.folio"
            disabled
          />
        </div>
      </div>
      <h5 class="text-center mt-1 mb-3">
        <u><b>INFORME VALIDACIÓN EMPRESA</b></u>
      </h5>
      <table class="table table-sm" style="border-color: white">
        <thead>
          <tr>
            <th
              colspan="12"
              class="text-left bg-primary text-white m-0 py-1 px-2"
            >
              DATOS EMPRESA CONTRATISTA
            </th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-color: white">
            <!-- NOMBRE Y RUT DE CONTRATISTA -->
            <td colspan="1"><b>EMPRESA</b></td>
            <td colspan="9">
              <p class="ml-1 my-0">
                {{ data.contractor.business_name.toUpperCase() }}
              </p>
            </td>
            <td colspan="1"><b>RUT</b></td>
            <td>
              <p class="my-0">{{ data.contractor.rut.toUpperCase() }}</p>
            </td>
          </tr>
          <tr style="border-color: white">
            <!-- DIRECCION -->
            <td colspan="1"><b>DIRECCIÓN</b></td>
            <td colspan="11">
              <p class="ml-1 my-0">
                {{ data.contractor.branchOffice.address.toUpperCase() }}
              </p>
            </td>
          </tr>
          <tr style="border-color: white">
            <!-- COMUNA/REGION/CONTACTO -->
            <td colspan="1"><b>COMUNA</b></td>
            <td colspan="5">
              <p class="ml-1 my-0">
                {{ data.contractor.branchOffice.commune.name.toUpperCase() }}
              </p>
            </td>
            <td colspan="1"><b>REGION</b></td>
            <td colspan="5">
              <p class="my-0">
                {{
                  data.contractor.branchOffice.commune.region.name.toUpperCase()
                }}
              </p>
            </td>
          </tr>
          <tr style="border-color: white">
            <!-- GIRO CONTACTO -->
            <td colspan="1"><b>GIRO</b></td>
            <td colspan="5">
              <p class="ml-1 my-0">
                {{ data.contractor.commercial_business.name.toUpperCase() }}
              </p>
            </td>
            <td colspan="1"><b>CONTACTO</b></td>
            <td colspan="5">
              <p class="my-0">
                {{ data.contractor.contact_name.toUpperCase() }}
              </p>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-sm my-0" style="border-color: white">
        <tbody>
          <tr>
            <th
              colspan="12"
              class="text-left bg-primary text-white m-0 py-1 px-2"
            >
              DATOS EMPRESA PRINCIPAL
            </th>
          </tr>
          <tr style="border-color: white">
            <!-- NOMBRE Y RUT DE CONTRATISTA -->
            <td colspan="1"><b>EMPRESA PRINCIPAL</b></td>
            <td colspan="11">
              <p class="ml-1 my-0 text-left">
                {{ data.principal.business_name.toUpperCase() }}
              </p>
            </td>
          </tr>
          <tr style="border-color: white">
            <!-- DIRECCION -->
            <td colspan="1"><b>DIRECCIÓN INSTALACIÓN</b></td>
            <td colspan="11">
              <p class="ml-1 my-0 text-left">
                {{ data.principal.branchOffice.address.toUpperCase() }}
              </p>
            </td>
          </tr>

          <tr style="border-color: white">
            <!-- COMUNA/REGION/CONTACTO -->
            <td colspan="1"><b>COMUNA</b></td>
            <td colspan="5">
              <p class="ml-1 my-0">
                {{ data.principal.branchOffice.commune.name.toUpperCase() }}
              </p>
            </td>
            <td colspan="1"><b>REGION</b></td>
            <td colspan="5">
              <p class="my-0">
                {{
                  data.principal.branchOffice.commune.region.name.toUpperCase()
                }}
              </p>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        1.- VALIDACIÓN DOCUMENTACIÓN MENSUAL EMPRESA
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary">
            <th></th>
            <th>ESTADO</th>
            <th>OBSERVACIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>F30</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.F30.state"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-right w-100"
                v-model="form.F30.obs"
                placeholder="Observaciones"
              />
            </td>
          </tr>
          <tr>
            <td>CONSULTA MULTAS DT</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.query.state"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-right w-100"
                v-model="form.query.obs"
                placeholder="Observaciones"
              />
            </td>
          </tr>
          <tr>
            <td>CERTIFICADO SINIESTRALIDAD</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.accident.state"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-right w-100"
                v-model="form.accident.obs"
                placeholder="Observaciones"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        2.- VALIDACIÓN REMUNERACIONES TRABAJADORES
      </p>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'employee'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td>
              <input
                placeholder="Días"
                class="w-100 text-right"
                type="number"
                v-model="employee.days"
              />
            </td>
            <td>
              <input
                placeholder="HHEE"
                class="w-100 text-right"
                type="number"
                v-model="employee.hhee"
              />
            </td>
            <td>
              <input
                placeholder="Sueldo Imponible"
                class="w-100 text-right"
                type="number"
                @input="changePayment"
                v-model="employee.payment"
              />
            </td>
            <td>
              <input
                placeholder="AFP"
                class="w-100 text-right"
                type="number"
                v-model="employee.afp"
              />
            </td>
            <td>
              <input
                placeholder="Salud"
                class="w-100 text-right"
                type="number"
                v-model="employee.health"
              />
            </td>
            <td>
              <input
                placeholder="Préstamo"
                class="w-100 text-right"
                type="number"
                v-model="employee.loan"
              />
            </td>
            <td>
              <input
                placeholder="Sueldo Liquido"
                class="w-100 text-right"
                type="number"
                @input="changeLiquid"
                v-model="employee.liquid"
              />
            </td>
          </tr>
          <tr>
            <td colspan="3" class="text-center"><b>VALIDACIÓN IMPONIBLE</b></td>
            <td class="text-right">
              <b>
                {{ form.payment_employee }}
              </b>
            </td>
            <td colspan="3" class="text-center"><b>VALIDACIÓN LIQUIDO</b></td>
            <td class="text-right">
              <b>
                {{ form.liquid_employee }}
              </b>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        3.- VALIDACIÓN MONTOS F30-1/LIBRO DE REMUNERACIONES
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary">
            <th></th>
            <th>Nº TRABAJADORES</th>
            <th>TOTAL IMPONIBLE</th>
            <th>TOTAL LIQUIDO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>F30-1</td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.F30_1.workers"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.F30_1.total_ini"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.F30_1.total_end"
              />
            </td>
          </tr>
          <tr>
            <td>L. DE REMUNERACIONES</td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.book.workers"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.book.total_ini"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.book.total_end"
              />
            </td>
          </tr>
          <tr>
            <td>COTIZACIONES PREVIAS</td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.prev.workers"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.prev.total_ini"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.prev.total_end"
              />
            </td>
          </tr>
          <tr>
            <td><b>VALIDATE</b></td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.validate.workers"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.validate.total_ini"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-right w-100"
                type="number"
                v-model="form.validate.total_end"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        4.- MOVIMIENTOS DE PAGO
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary text-center">
            <th>PERIODO</th>
            <th>ADELANTOS</th>
            <th>SUELDOS</th>
            <th>COTIZACIONES PREV</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input
                class="text-center w-100"
                placeholder="Periodo"
                v-model="form.payment.period"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                placeholder="Adelanto"
                v-model="form.payment.adv"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                placeholder="Fecha de sueldo"
                v-model="form.payment.salary"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                placeholder="Fecha de cotizaciones"
                v-model="form.payment.prev"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        5.- VALIDACIÓN LIBRO DE ASISTENCIA
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary">
            <th>NOMBRE</th>
            <th>N° FOLIO</th>
            <th>INASISTENCIA</th>
            <th>HHEE</th>
            <th>OBSERVACIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'book'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td>
              <input
                class="w-100 text-right"
                placeholder="Folio"
                v-model="employee.nFolio"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="w-100 text-right"
                type="number"
                v-model="employee.absence"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="w-100 text-right"
                type="number"
                v-model="employee.hheeBook"
              />
            </td>
            <td>
              <input
                class="w-100 text-right"
                v-model="employee.obs"
                placeholder="Observaciones"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 my-0 b">
        6.- MOVIMIENTOS PERSONAL
      </p>
      <p
        class="text-justify font-weight-bold mt-1 mb-2 b pl-1"
        style="font-size: 0.9em"
      >
        6.1 FINIQUITOS
      </p>
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
          <tr
            v-for="settlement in form.settlements"
            v-bind:key="settlement.id + 'settlement'"
          >
            <td>
              {{ settlement.employee.name }}
              {{ settlement.employee.surname }}
              {{ settlement.employee.second_surname }}
            </td>
            <td>
              <input
                placeholder="Motivo"
                class="w-100 text-center"
                v-model="settlement.reason"
              />
            </td>
            <td>
              <input
                placeholder="Fecha de notificación"
                class="w-100 text-center"
                v-model="settlement.letter"
              />
            </td>
            <td>
              <input
                placeholder="Previred"
                class="w-100 text-center"
                v-model="settlement.previred"
              />
            </td>
            <td>
              <input
                placeholder="Fecha de legalizado"
                class="w-100 text-center"
                v-model="settlement.legalized"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p
        class="text-justify font-weight-bold mt-3 mb-2 b pl-1"
        style="font-size: 0.9em"
      >
        6.2 CONTRATOS
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr>
            <th style="border: unset"></th>
            <th colspan="2" class="table-primary text-center">FECHA</th>
            <th colspan="5" style="border: unset"></th>
          </tr>
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
          <tr
            v-for="contract in form.contracts"
            v-bind:key="contract.id + 'contract'"
          >
            <td>
              {{ contract.employee.name }}
              {{ contract.employee.surname }}
              {{ contract.employee.second_surname }}
            </td>
            <td>{{ contract.start }}</td>
            <td>{{ contract.finish }}</td>
            <td>
              <input
                class="w-100 text-center"
                v-model="contract.ci"
                placeholder="CI"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="contract.ri"
                placeholder="RI"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="contract.epp"
                placeholder="EPP"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="contract.das"
                placeholder="DAS"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="contract.other"
                placeholder="Otros"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p
        class="text-justify font-weight-bold mt-3 mb-2 b pl-1"
        style="font-size: 0.9em"
      >
        6.3 TRASLADOS/REEMPLAZOS
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr>
            <th style="border: unset"></th>
            <th colspan="2" class="table-primary text-center">FECHA</th>
            <th colspan="5" style="border: unset"></th>
          </tr>
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
          <tr
            v-for="transfer in form.transfers"
            v-bind:key="transfer.id + 'transfer'"
          >
            <td>
              {{ transfer.employee.name }}
              {{ transfer.employee.surname }}
              {{ transfer.employee.second_surname }}
            </td>
            <td>{{ transfer.start }}</td>
            <td>{{ transfer.finish }}</td>
            <td>
              <input
                class="w-100 text-center"
                v-model="transfer.ci"
                placeholder="CI"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="transfer.ri"
                placeholder="RI"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="transfer.epp"
                placeholder="EPP"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="transfer.das"
                placeholder="DAS"
              />
            </td>
            <td>
              <input
                class="w-100 text-center"
                v-model="transfer.other"
                placeholder="Otros"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p
        class="text-justify font-weight-bold mt-3 mb-2 b pl-1"
        style="font-size: 0.9em"
      >
        6.4 LICENCIAS
      </p>
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
        <tbody>
          <tr
            v-for="license in data.resumen.licenses"
            v-bind:key="license.id + 'license'"
          >
            <td>
              {{ license.employee.name }} {{ license.employee.surname }}
              {{ license.employee.second_surname }}
            </td>
            <td class="text-center">{{ license.start }}</td>
            <td>
              <input
                placeholder="Licencia"
                class="w-100 text-center"
                v-model="license.license"
              />
            </td>
            <td>
              <input
                placeholder="Días"
                class="w-100 text-center"
                type="number"
                v-model="license.days"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        7.- RESUMEN MOVIMIENTOS PERSONAL
      </p>
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
            <td>
              {{ form.summary.settlements }}
            </td>
            <td>
              {{ form.summary.contracts }}
            </td>
            <td>
              {{ form.summary.transfers }}
            </td>
            <td>
              {{ form.summary.licenses }}
            </td>
          </tr>
        </tbody>
      </table>
      <table
        class="table table-sm table-primary table-bordered"
        style="border-color: white"
      >
        <thead>
          <tr class="table-primary text-center">
            <th>TOTAL TRABAJADORES VALIDADOS</th>
            <th>
              <b
                ><input
                  placeholder="Periodo"
                  class="text-right"
                  v-model="form.end.periodo"
              /></b>
            </th>
            <th>
              <input
                placeholder="Total"
                class="text-right"
                type="number"
                v-model="form.end.total"
              />
            </th>
          </tr>
        </thead>
      </table>

      <p class="text-justify font-weight-bold mt-3 mb-2 b">
        8.- OBSERVACIONES DEL PERIODO
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary text-center">
            <th>DOCUMENTOS/PROCESOS</th>
            <th>OBSERVACIONES</th>
            <th>CONDICIONAL DE PAGO</th>
            <th>
              <el-button
                icon="el-icon-plus"
                class="text-white bg-success p-1"
                circle
                @click="add('obs', { doc: '', obs: '', if: '' })"
              ></el-button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ob in form.obs" v-bind:key="ob.obs">
            <th><textarea class="w-100" v-model="ob.doc" /></th>
            <th><textarea class="w-100" v-model="ob.obs" /></th>
            <th><textarea class="w-100" v-model="ob.if" /></th>
            <th class="text-center">
              <el-button
                icon="el-icon-minus"
                class="text-white bg-danger p-1"
                circle
                @click="minus('obs')"
              ></el-button>
            </th>
          </tr>
        </tbody>
      </table>
      <div class="d-flex justify-content-end pb-3">
        <b-button @click="download" variant="primary"
          >Descargar <i class="el-icon-download"
        /></b-button>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import input from "../document/input.vue";
export default {
  components: { input },
  props: ["data"],
  data() {
    let k = moment().format("MMM-DD");
    let employees = [];
    this.data.employees.map((employee) => {
      employee.days = 0;
      employee.hhee = 0;
      employee.afp = 0;
      employee.health = 0;
      employee.loan = 0;
      employee.liquid = 0;
      employee.nFolio = "";
      employee.absence = 0;
      employee.hheeBook = 0;
      employee.obs = "";
      employees.push(employee);
    });
    let settlements = [];
    this.data.resumen.settlements.map((settlement) => {
      settlement.reason = "";
      settlement.letter = "";
      settlement.previred = "";
      settlement.legalized = "";
      settlements.push(settlement);
    });
    let contracts = [];
    this.data.resumen.contracts.map((contract) => {
      contract.ci = "";
      contract.ri = "";
      contract.epp = "";
      contract.das = "";
      contract.other = "";
      contracts.push(contract);
    });
    let transfers = [];
    this.data.resumen.transfers.map((transfer) => {
      transfer.ci = "";
      transfer.ri = "";
      transfer.epp = "";
      transfer.das = "";
      transfer.other = "";
      transfers.push(transfer);
    });
    let licenses = [];
    this.data.resumen.licenses.map((license) => {
      license.license = "";
      license.days = 0;
      licenses.push(license);
    });

    return {
      folio: "",
      form: {
        obs: [],
        F30: {
          state: this.$truthty(this.data.monthly.F30)
            ? this.data.monthly.F30.validation_state.name
            : "",
          obs: this.$truthty(this.data.monthly.F30)
            ? this.data.monthly.F30.observations
            : "",
        },
        query: {
          state: "",
          obs: "",
        },
        accident: {
          state: this.$truthty(this.data.monthly.accident)
            ? this.data.monthly.accident.validation_state.name
            : "",
          obs: this.$truthty(this.data.monthly.accident)
            ? this.data.monthly.accident.observations
            : "",
        },
        F30_1: {
          workers: 0,
          total_ini: 0,
          total_end: 0,
        },
        book: {
          workers: 0,
          total_ini: 0,
          total_end: 0,
        },
        prev: {
          workers: 0,
          total_ini: 0,
          total_end: 0,
        },
        validate: {
          workers: 0,
          total_ini: 0,
          total_end: 0,
        },
        end: {
          periodo: k,
          total: employees.length,
        },
        payment: {
          period: "",
          adv: "",
          salary: "",
          prev: "",
        },
        employees,
        settlements,
        contracts,
        transfers,
        licenses,
        payment_employee: employees.reduce((valorAnterior, employee) => {
          return valorAnterior.payment + employee.payment;
        }),
        liquid_employee: employees.reduce((valorAnterior, employee) => {
          return valorAnterior.liquid + employee.liquid;
        }),
        summary: {
          settlements: this.data.resumen.settlements.length,
          contracts: this.data.resumen.contracts.length,
          transfers: this.data.resumen.transfers.length,
          licenses: this.data.resumen.licenses.length,
        },
      },
    };
  },
  methods: {
    init() {
      console.log(this.data);
    },
    changePayment() {
      console.log("HOLA");
      this.form.payment_employee = this.form.employees.reduce(
        (valorAnterior, employee) => {
          let paymentNew = valorAnterior.payment,
            paymentOld = employee.payment;
          if (!this.$truthty(paymentNew)) {
            paymentNew = 0;
          }
          if (!this.$truthty(paymentOld)) {
            paymentOld = 0;
          }
          return parseInt(paymentNew) + parseInt(paymentOld);
        }
      );
    },
    changeLiquid() {
      this.form.liquid_employee = this.form.employees.reduce(
        (valorAnterior, employee) => {
          let liquidNew = valorAnterior.liquid,
            liquidOld = employee.liquid;

          if (!this.$truthty(liquidNew)) {
            liquidNew = 0;
          }
          if (!this.$truthty(liquidOld)) {
            liquidOld = 0;
          }
          return parseInt(liquidNew) + parseInt(liquidOld);
        }
      );
    },
    add(name, atributes) {
      this.form[name].push(atributes);
    },
    minus(name) {
      this.form[name].splice(index, 1);
    },
    download() {
      let document = {
        document_type_id: this.data.service.type_id,
        service_id: this.data.service.id,
        start: moment().format("YYYY-MM-DD"),
        finish: moment().clone().endOf("month").format("YYYY-MM-DD"),
        month_year_registry: moment().format("YYYY-MM"),
        validation_state_id: 3,
      };
      axios({
        url: `${window.location.origin}/pdf/download/report/fixed`,
        method: "POST",
        data: {
          document,
          contractor: this.data.contractor,
          principal: this.data.principal,
          expirationDate: this.data.expirationDate,
          today: this.data.today,
          folio: this.data.folio,
          employees: this.form.employees,
          form: this.form,
        },
        responseType: "blob",
      })
        .then((response) => {
          console.log(response);
          let fileURL = window.URL.createObjectURL(new Blob([response.data]));
          console.log(fileURL);
          let fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", "report.pdf");
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
  },
  mounted() {
    this.init();
  },
};
</script>