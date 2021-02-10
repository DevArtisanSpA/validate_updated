<template>
  <div class="row container" style="font-size: 80%; background: ghostwhite">
    <div class="col-11 mx-auto">
      <div class="row my-0">
        <div class="col-md-6 text-left">
          <div class="div-img-logo">
            <img
              width="200"
              alt="validate Logo"
              src="https://validate.cl/img/logo-validate-3.png"
            />
          </div>
        </div>
        <div class="col-md-6 pt-3 text-right">
          <b>FOLIO</b> Nº
          <input placeholder="0" class="text-center" v-model="folio" />
        </div>
      </div>
      <h5 class="text-center mt-1 mb-3">
        <u><b>CERTIFICADO VALIDACION EMPRESA CONTRATISTA TRABAJOS </b></u>
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
            <td colspan="1"><b>DIRECCIÓN</b></td>
            <td colspan="11">
              <p class="ml-1 my-0">
                {{ data.contractor.branchOffice.address.toUpperCase() }}
              </p>
            </td>
          </tr>
          <tr style="border-color: white">
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
            <td colspan="1"><b>EMPRESA PRINCIPAL</b></td>
            <td colspan="11">
              <p class="ml-1 my-0 text-left">
                {{ data.principal.business_name.toUpperCase() }}
              </p>
            </td>
          </tr>
          <tr style="border-color: white">
            <td colspan="1"><b>DIRECCIÓN INSTALACIÓN</b></td>
            <td colspan="11">
              <p class="ml-1 my-0 text-left">
                {{ data.principal.branchOffice.address.toUpperCase() }}
              </p>
            </td>
          </tr>

          <tr style="border-color: white">
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
        <b
          >VALIDACIÓN COTIZACIONES PREVISIONALES /F30-1 /LIBRO DE REMUNERACIONES
        </b>
      </p>
      <table class="table table-sm table-bordered">
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

      <p class="text-center mt-3 mb-2"><b>RESUMEN MOVIMIENTOS PERSONAL</b></p>
      <table class="table table-sm table-bordered">
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
              <input
                placeholder="0"
                class="text-center w-100"
                type="number"
                v-model="form.summary.settlement"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-center w-100"
                type="number"
                v-model="form.summary.contracts"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-center w-100"
                type="number"
                v-model="form.summary.transfers"
              />
            </td>
            <td>
              <input
                placeholder="0"
                class="text-center w-100"
                type="number"
                v-model="form.summary.licenses"
              />
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
              <b>
                <input
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
      <div class="row my-0 mt-2">
        <div class="col-12">
          <p class="mb-1">
            CON FECHA: <b>{{ data.today.toUpperCase() }}</b>
          </p>

          <p class="my-0 text-justify">
            <b>VALIDATE &#174;</b> CERTIFICA QUE LOS MONTOS CANCELADOS POR
            CONCEPTO DE REMUNERACIONES CONCUERDAN CON LOS DECLARADOS EN EL PAGO
            DE IMPOSICIONES.
          </p>
          <p class="my-0 text-justify">
            <b>VALIDATE &#174;</b> CERTIFICA QUE LA DOCUMENTACIÓN RECIBIDA FUE
            VALIDADA EN COMPARATIVA CON CERTIFICADO F30-1, REGISTRO DE
            ASISTENCIA, DEL MES CUMPLIENDO TODOS LOS PARÁMETROS DE LA LEY DE
            SUBCONTRATACIÓN.
          </p>
        </div>
      </div>
      <div class="row">
        <table class="table mb-0 ml-0 mt-2 table-end">
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
    console.log(k);
    return {
      form: {
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
        summary: {
          settlement: this.data.resumen.settlement,
          contracts: this.data.resumen.contracts,
          transfers: this.data.resumen.transfers,
          licenses: this.data.resumen.licenses,
        },
        end: {
          periodo: k,
          total: 0,
        },
      },
      folio: "",
    };
  },
  methods: {
    init() {
      console.log(this.$props);
    },
    download() {
      console.log(this.form);

      axios({
        url: `${window.location.origin}/pdf/download/certificate/fixed`,
        method: "POST",
        data: {
          form: this.form,
          contractor: this.data.contractor,
          principal: this.data.principal,
          expirationDate: this.data.expirationDate,
          today: this.data.today,
          folio: this.folio,
        },
        responseType: "blob",
      })
        .then((response) => {
          console.log(response);
          let fileURL = window.URL.createObjectURL(new Blob([response.data]));
          console.log(fileURL);
          let fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", "certificado.pdf");
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