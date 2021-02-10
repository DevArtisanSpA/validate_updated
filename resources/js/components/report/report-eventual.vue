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
          <input placeholder="0" class="text-center" v-model="folio" />
        </div>
      </div>
      <h5 class="text-center mt-1 mb-3">
        <u><b>INFORME DE VALIDACION EMPRESA TRABAJOS EVENTUALES </b></u>
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
        1.- VALIDACIÓN DOCUMENTACIÓN MENSUAL EMPRESA
        <text style="font-size: 0.9em">{{
          data.contractor.business_name.toUpperCase()
        }}</text>
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
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.contract.stateSpecific"
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
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.affiliation.stateSpecific"
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
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.policy.stateSpecific"
              />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.policy.stateObs" />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.policy.obs" />
            </td>
            <td>
              <input class="text-center w-100" v-model="form.policy.obsFecha" />
            </td>
          </tr>
          <tr>
            <td>POLÍTICAS DE SEGURIDAD</td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.regulation.stateValidity"
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.regulation.stateSpecific"
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
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.emergency.stateSpecific"
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
              />
            </td>
            <td>
              <input
                class="text-center w-100"
                v-model="form.training.stateSpecific"
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
                v-model="form.training.obsFecha"
              />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        2.- VALIDACIÓN DOCUMENTACIÓN TRABAJADORES
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
            <td><input class="w-100" v-model="employee.start" /></td>
            <td><input class="w-100" v-model="employee.finish" /></td>
            <td><input class="w-100" v-model="employee.ci" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        2.1.- VALIDACIÓN DOCUMENTACIÓN EHS TRABAJADORES
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
            <td><input class="w-100" v-model="employee.riohs" /></td>
            <td><input class="w-100" v-model="employee.epp" /></td>
            <td><input class="w-100" v-model="employee.das" /></td>
            <td><input class="w-100" v-model="employee.other" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        3.- VALIDACIÓN EXÁMENES OCUPACIONALES
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
            <td><input class="w-100" v-model="employee.basicState" /></td>
            <td><input class="w-100" v-model="employee.basicValidity" /></td>
            <td><input class="w-100" v-model="employee.confinedState" /></td>
            <td><input class="w-100" v-model="employee.confinedValidity" /></td>
            <td><input class="w-100" v-model="employee.heightState" /></td>
            <td><input class="w-100" v-model="employee.heightValidity" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        4.- VALIDACION LICENCIAS /ACREDITACIONES
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
            <td><input class="w-100" v-model="employee.licenseState" /></td>
            <td><input class="w-100" v-model="employee.licenseValidity" /></td>
            <td><input class="w-100" v-model="employee.licenseOther" /></td>
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
                  placeholder="0"
                  class="text-right"
                  v-model="form.end.periodo"
              /></b>
            </th>
            <th>
              <input
                placeholder="0"
                class="text-right"
                type="number"
                v-model="form.end.total"
              />
            </th>
          </tr>
        </thead>
      </table>

      <p class="text-justify mt-3 mb-2 b">
        5.- VALIDACION DOCUMENTOS MENSUALES EMPRESA
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
              <input class="text-center w-100" v-model="form.F30.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="text-right w-100" v-model="form.F30.obs" />
            </td>
          </tr>
          <tr>
            <td>CERTIFICADO SINIESTRALIDAD</td>
            <td>
              <input class="text-center w-100" v-model="form.accident.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="text-right w-100" v-model="form.accident.obs" />
            </td>
          </tr>
          <tr>
            <td>CONSULTA DE MULTAS DT</td>
            <td>
              <input class="text-center w-100" v-model="form.query.state" />
            </td>
            <td>AL</td>
            <td>
              <input class="text-right w-100" v-model="form.query.obs" />
            </td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">
        6.- VALIDACIÓN DE COTIZACIONES PREVISIONALES
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
            <td><input class="w-100" v-model="employee.quotationAmount" /></td>
            <td><input class="w-100" v-model="employee.quotationPeriod" /></td>
            <td><input class="w-100" v-model="employee.quotationState" /></td>
            <td><input class="w-100" v-model="employee.quotationObs" /></td>
          </tr>
        </tbody>
      </table>
      <p class="text-justify mt-3 mb-2 b">7.- OBSERVACIONES</p>
      <table class="table table-sm table-bordered">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary text-center">
            <th>OBSERVACIONES</th>
            <th style="width: 1em;">
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
        <b-button @click="download" variant="primary"
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
    let k = moment().format("MMM-DD");
    let employees = [];
    this.data.employees.map((employee) => {
      employee.riohs = "";
      employee.epp = "";
      employee.das = "";
      employee.other = "";
      employee.ci = "";
      employee.start = "";
      employee.finish = "";
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
      folio: "",
      form: {
        obs: [],
        end: {
          periodo: k,
          total: employees.length,
        },
        employees,
        contract: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        affiliation: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        policy: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        regulation: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        emergency: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        training: {
          stateValidity: "",
          stateSpecific: "",
          stateObs: "",
          obs: "",
          obsFecha: "",
        },
        F30: { state: "", obs: "" },
        accident: { state: "", obs: "" },
        query: { state: "", obs: "" },
      },
    };
  },
  methods: {
    init() {
      console.log(this.data);
    },
    download() {
      axios({
        url: `${window.location.origin}/pdf/download/report/eventual`,
        method: "POST",
        data: {
          contractor: this.data.contractor,
          principal: this.data.principal,
          expirationDate: this.data.expirationDate,
          today: this.data.today,
          folio: this.folio,
          employees: this.data.employees,
          form:this.form,
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
    this.init();
  },
};
</script>