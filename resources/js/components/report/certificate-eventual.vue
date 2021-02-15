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
          <u><b>CERTIFICADO VALIDACION EMPRESA CONTRATISTA</b></u>
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
      <p class="text-center mt-3 mb-2">
        <b>
          TRABAJADORES VALIDADOS PARA INGRESAR A
          {{ data.principal.business_name.toUpperCase() }}</b
        >
      </p>
      <table class="table table-sm table-bordered pb-1">
        <!-- LISTA DE TRABAJADORES VALIDADOS -->
        <thead>
          <tr class="table-primary">
            <th class="text-left">NOMBRE TRABAJADOR</th>
            <th>CARGO</th>
            <th>RUT</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in data.employees" v-bind:key="employee.id">
            <td>
              {{ employee.name }} {{ employee.surname }}
              {{ employee.second_surname }}
            </td>
            <td>{{ employee.job_type.name }}</td>
            <td>{{ employee.identification_id }}</td>
          </tr>
        </tbody>
      </table>
      <div class="row my-0 mt-2">
        <!-- MENSAJE -->
        <div class="col-12">
          <p class="mb-1">
            CON FECHA: <b class="text-primary">{{ data.today }}</b>
          </p>

          <p class="my-0 text-justify">
            <b>VALIDATE &#174;</b> CERTIFICA QUE LA DOCUMENTACIÓN PRESENTADA POR
            LA EMPRESA.
          </p>
          <p class="mt-0 text-justify mb-1">
            <b class="text-primary" style="width: 70%; float: left"
              >{{ data.contractor.business_name.toUpperCase() }}
            </b>
            <b class="text-primary" style="width: 30%; float: left"
              >RUT: {{ data.contractor.rut.toUpperCase() }}
            </b>
          </p>
          <p class="my-0 text-justify">
            Empresa Contratista Cumple con los requisitos de ingreso. Para
            realizar trabajo eventual
          </p>
        </div>
      </div>
      <div class="row">
        <table class="table mb-0 ml-0 mt-2 table-end">
          <!-- FECHA DE EXPIRACIÓN -->
          <tbody>
            <tr>
              <td><b class="p-1 m-0">PLAZO CERTIFICADO HASTA</b></td>
              <td class="text-right">
                <b class="p-1 m-0">{{ data.expirationDate }}</b>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center mt-5">
        <!-- FIRMA -->
        <div class="text-center">
          <img
            src="https://validate.cl/img/logo-validate-3.png"
            width="200"
            class="img-logo"
            alt="validate Logo"
          />
          <p class="m-0">Equipo VALIDATE &#174;</p>
          <p class="m-0">Responsable Revisión</p>
          <p class="m-0">Karina González</p>
        </div>
      </div>
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
    let k = moment().format("DD-MMM");
    return {
      folio: "",
    };
  },
  methods: {
    init() {
      console.log(this.data);
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
        url: `${window.location.origin}/pdf/download/certificate/eventual`,
        method: "POST",
        data: {
          document:documentlocal,
          contractor: this.data.contractor,
          principal: this.data.principal,
          expirationDate: this.data.expirationDate,
          today: this.data.today,
          folio: this.data.folio,
          employees: this.data.employees,
        },
        responseType: "blob",
      })
        .then((response) => {
          console.log(response);
          let fileURL = window.URL.createObjectURL(new Blob([response.data]));
          console.log(fileURL);
          let fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", "certificado eventual.pdf");
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