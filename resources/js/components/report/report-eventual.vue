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
        <p>
          <u><b>INFORME DE VALIDACION EMPRESA </b></u>
        </p>
        <p>
          <u><b>TRABAJOS EVENTUALES</b></u>
        </p>
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
      <p class="text-justify mt-3 mb-2 b">
        <b>
          1.- VALIDACIÓN DOCUMENTACIÓN MENSUAL EMPRESA
          <text style="font-size: 0.9em">{{
            data.contractor.business_name.toUpperCase()
          }}</text></b
        >
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead class="text-center">
          <tr class="table-primary">
            <th></th>
            <th colspan="3">ESTADO</th>
            <th colspan="2">OBSERVACIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>CONTRATO COMERCIAL</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.contract.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.contract.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.contract.stateObs"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.contract.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                v-model="form.contract.obsFecha"
              />
            </td>
          </tr>
          <tr>
            <td>AFILIACIÓN MUTUALIDAD</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.affiliation.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.affiliation.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.affiliation.stateObs"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.affiliation.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                v-model="form.affiliation.obsFecha"
              />
            </td>
          </tr>
          <tr>
            <td>REGLAMENTO INTERNO</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.policy.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.policy.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.policy.stateObs" />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.policy.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.policy.obsFecha"
                type="date"
              />
            </td>
          </tr>
          <tr>
            <td>POLÍTICAS DE SEGURIDAD</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.regulation.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.regulation.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.regulation.stateObs"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.regulation.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                v-model="form.regulation.obsFecha"
              />
            </td>
          </tr>
          <tr>
            <td>PLAN EN CASO DE EMERGENCIA</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.emergency.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.emergency.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.emergency.stateObs"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.emergency.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                v-model="form.emergency.obsFecha"
              />
            </td>
          </tr>
          <tr>
            <td>PROGRAMA DE CAPACITACION</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.training.stateValidity"
                placeholder="Estado"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.training.stateSpecific" placeholder="Específico"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.training.stateObs"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.training.obs" />
            </td>
            <td>
              <input
                class="text-center w-100"
                type="date"
                v-model="form.training.obsFecha"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        <b>2.- VALIDACIÓN DOCUMENTACIÓN TRABAJADORES</b>
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr>
            <th colspan="3" style="border: unset"></th>
            <th colspan="2" class="table-primary text-center">
              FECHA CONTRATO
            </th>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'worker'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td>{{ employee.job_type.name }}</td>
            <td>{{ employee.identification_id }}</td>
            <td>
              <input class="w-100 text-center" v-model="employee.start" />
            </td>
            <td>
              <input class="w-100 text-center" v-model="employee.finish" />
            </td>
            <td><input class="w-100 text-center" v-model="employee.ci" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        <b>2.1.- VALIDACIÓN DOCUMENTACIÓN EHS TRABAJADORES</b>
      </p>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'EHS'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td>
              <input class="w-100 text-center" placeholder="RIOHS" v-model="employee.riohs" />
            </td>
            <td><input class="w-100 text-center" placeholder="EPP" v-model="employee.epp" /></td>
            <td><input class="w-100 text-center" placeholder="DAS" v-model="employee.das" /></td>
            <td>
              <input class="w-100 text-center" placeholder="Otros" v-model="employee.other" />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        <b>3.- VALIDACIÓN EXÁMENES OCUPACIONALES</b>
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="text-center">
            <th style="border: unset"></th>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'exam'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td><input class="w-100 text-center" placeholder="Estado" v-model="employee.basicState" /></td>
            <td><input class="w-100 text-center" placeholder="Fecha vigencia" v-model="employee.basicValidity" /></td>
            <td><input class="w-100 text-center" placeholder="Estado" v-model="employee.confinedState" /></td>
            <td><input class="w-100 text-center" placeholder="Fecha vigencia" v-model="employee.confinedValidity" /></td>
            <td><input class="w-100 text-center" placeholder="Estado" v-model="employee.heightState" /></td>
            <td><input class="w-100 text-center" placeholder="Fecha vigencia" v-model="employee.heightValidity" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        <b>4.- VALIDACION LICENCIAS /ACREDITACIONES</b>
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr>
            <th style="border: unset"></th>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'license'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td><input class="w-100 text-center" placeholder="Estado" v-model="employee.licenseState" /></td>
            <td><input class="w-100 text-center" placeholder="Fecha vigencia" v-model="employee.licenseValidity" /></td>
            <td><input class="w-100 text-center" placeholder="Otros" v-model="employee.licenseOther" /></td>
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

      <p class="text-justify mt-3 mb-2 b">
        <b>5.- VALIDACION DOCUMENTOS MENSUALES EMPRESA</b>
      </p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead class="text-center">
          <tr class="table-primary">
            <th></th>
            <th>ESTADO</th>
            <th colspan="2">OBSERVACIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>F30</td>
            <td>
              <input class="text-center w-100" placeholder="Estado" v-model="form.F30.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="w-100" placeholder="Observaciones" v-model="form.F30.obs" />
            </td>
          </tr>
          <tr>
            <td>CERTIFICADO SINIESTRALIDAD</td>
            <td>
              <input class="text-center w-100" placeholder="Estado" v-model="form.accident.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="w-100" placeholder="Observaciones" v-model="form.accident.obs" />
            </td>
          </tr>
          <tr>
            <td>CONSULTA DE MULTAS DT</td>
            <td>
              <input class="text-center w-100" placeholder="Estado" v-model="form.query.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="w-100" placeholder="Observaciones" v-model="form.query.obs" />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        <b>6.- VALIDACIÓN DE COTIZACIONES PREVISIONALES</b>
      </p>
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
          <tr
            v-for="employee in form.employees"
            v-bind:key="employee.id + 'quotation'"
          >
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td><input class="w-100 text-center" placeholder="Monto" v-model="employee.quotationAmount" /></td>
            <td><input class="w-100 text-center" placeholder="Periodo" v-model="employee.quotationPeriod" /></td>
            <td><input class="w-100 text-center"  placeholder="Estado" v-model="employee.quotationState" /></td>
            <td><input class="w-100" placeholder="Observaciones" v-model="employee.quotationObs" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">7.- OBSERVACIONES</p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary text-center">
            <th>OBSERVACIONES</th>
            <th style="width: 1em">
              <el-button
                icon="el-icon-plus"
                class="text-white bg-success p-1"
                circle
                @click="add('obs', { text: '' })"
              ></el-button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(ob, index) in form.obs" v-bind:key="index">
            <th><textarea class="w-100" v-model="ob.text" /></th>
            <th class="text-center">
              <el-button
                icon="el-icon-minus"
                class="text-white bg-danger p-1"
                circle
                @click="minus('obs', index)"
              ></el-button>
            </th>
          </tr>
        </tbody>
      </table>
      <div class="d-flex justify-content-end pb-3">
        <b-button
          v-if="data.employees.length > 0"
          @click="download"
          variant="primary"
          >Descargar <i class="el-icon-download"
        /></b-button>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: ["data"],
  data() {
    return this.init();
  },
  methods: {
    init() {
      let k = moment().format("MMM-DD");
      let employees = [];
      this.data.employees.map((employee) => {
        let contract = employee.documents.find((x) => {
          return x.type.name == "Contrato de Trabajo";
        });
        let ci = employee.documents.find((x) => {
          return x.type.name == "Cédula de Identidad";
        });
        let epp = employee.documents.find((x) => {
          return x.type.name == "Recepción de EPP";
        });
        let das = employee.documents.find((x) => {
          return x.type.name == "DAS/ODIS/Políticas";
        });
        let riohs = employee.documents.find((x) => {
          return x.type.name == "Toma de Conocimiento Reglamento Interno";
        });
        employee.riohs =
          this.$truthty(riohs) && this.$truthty(riohs.finish)
            ? moment().diff(ci.finish, "days") < 0
              ? "vigente"
              : "caducado"
            : this.$truthty(riohs.start)
            ? "del " + riohs.start
            : "";
        employee.epp =
          this.$truthty(epp) && this.$truthty(epp.finish)
            ? moment().diff(ci.finish, "days") < 0
              ? "vigente"
              : "caducado"
            : this.$truthty(epp.start)
            ? "del " + epp.start
            : "";
        employee.das =
          this.$truthty(das) && this.$truthty(das.finish)
            ? moment().diff(ci.finish, "days") < 0
              ? "vigente"
              : "caducado"
            : this.$truthty(das.start)
            ? "del " + das.start
            : "";
        employee.other = "";
        employee.ci = this.$truthty(ci)
          ? moment().diff(ci.finish, "days") < 0
            ? "vigente"
            : "caducado"
          : "";
        employee.start = this.$truthty(contract) ? contract.start : "";
        employee.finish = this.$truthty(contract)
          ? this.$truthty(contract.finish)
            ? contract.finish
            : "Indefinido"
          : "";
        employee.basicState = "";
        employee.basicValidity = "";
        employee.confinedState = "";
        employee.confinedValidity = "";
        employee.heightState = "";
        employee.heightValidity = "";
        employee.licenseState = "";
        employee.licenseValidity = "";
        employee.licenseOther = "";
        employee.quotationAmount = "";
        employee.quotationPeriod = "";
        employee.quotationState = "";
        employee.quotationObs = "";
        employees.push(employee);
      });
      return {
        folio: this.data.folio,
        form: {
          obs: [],
          end: {
            periodo: k,
            total: employees.length,
          },
          employees,
          contract: {
            stateValidity: this.$truthty(this.data.monthly.comercialContract)
              ? moment().diff(
                  this.data.monthly.comercialContract.finish,
                  "days"
                ) < 0
                ? "vigente"
                : "caducado"
              : "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: this.$truthty(this.data.monthly.comercialContract)
              ? this.data.monthly.comercialContract.start
              : "",
          },
          affiliation: {
            stateValidity: this.$truthty(this.data.monthly.affiliation)
              ? moment().diff(this.data.monthly.affiliation.finish, "days") < 0
                ? "vigente"
                : "caducado"
              : "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: this.$truthty(this.data.monthly.affiliation)
              ? this.data.monthly.affiliation.start
              : "",
          },
          policy: {
            stateValidity: this.$truthty(this.data.monthly.politics)
              ? moment().diff(this.data.monthly.politics.finish, "days") < 0
                ? "vigente"
                : "caducado"
              : "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: this.$truthty(this.data.monthly.politics)
              ? this.data.monthly.politics.start
              : "",
          },
          regulation: {
            stateValidity: this.$truthty(this.data.monthly.regulation)
              ? moment().diff(this.data.monthly.regulation.finish, "days") < 0
                ? "vigente"
                : "caducado"
              : "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: this.$truthty(this.data.monthly.regulation)
              ? this.data.monthly.regulation.start
              : "",
          },
          emergency: {
            stateValidity: "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: "",
          },
          training: {
            stateValidity: "",
            stateSpecific: "",
            stateObs: "",
            obs: "Del",
            obsFecha: "",
          },
          F30: { state: "", obs: "" },
          accident: { state: "", obs: "" },
          query: { state: "", obs: "" },
        },
      };
    },
    download() {
      let documentlocal = {
        document_type_id: this.data.service.type_id,
        service_id: this.data.service.id,
        start: moment().format("YYYY-MM-DD"),
        finish: moment().clone().endOf("month").format("YYYY-MM-DD"),
        month_year_registry: moment().format("YYYY-MM"),
        validation_state_id: 3,
      };
      axios({
        url: `${window.location.origin}/pdf/download/report/eventual`,
        method: "POST",
        data: {
          document: documentlocal,
          contractor: this.data.contractor,
          principal: this.data.principal,
          expirationDate: this.data.expirationDate,
          today: this.data.today,
          folio: this.data.folio,
          employees: this.data.employees,
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
          fileLink.setAttribute("download", "report eventual.pdf");
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error);
          console.log(error.response);
        });
    },
    add(name, atributes) {
      this.form[name].push(atributes);
    },
    minus(name, index) {
      this.form[name].splice(index, 1);
    },
  },
  mounted() {
    console.log(this.data);
  },
  watch: {
    data(newValue, oldValue) {
      this.folio = newValue.folio;
      this.form = this.init().form;
    },
  },
};
</script>