<template>
  <div>
    <form @submit.prevent="checkForm">
      <div id="errors-div">
        <div v-if="this.errors.length" ref="errors-div">
          <b-alert variant="danger" show>
            <ul class="mb-0 mx-3">
              <li v-for="(error, index) in errors" v-bind:key="index">
                {{ error.message }}
              </li>
            </ul>
          </b-alert>
        </div>
      </div>
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title">
          <h5>IMPORTANTE</h5>
        </div>

        <div class="d-block text-center mt-2 mb-4">
          <span
            >¿Estás seguro de
            {{ !this.$truthty(this.$props.employee) ? "crear" : "modificar" }}
            este empleado?</span
          >
        </div>

        <div v-if="!sendStatus" class="float-right">
          <b-button @click.prevent="hideModal" variant="outline-secondary"
            >Cancelar</b-button
          >
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
        <div
          v-else
          class="d-flex justify-content-center"
          style="font-size: 36px"
        >
          <i class="el-icon-loading"></i>
        </div>
      </b-modal>
      <b-row>
        <b-col>
          <strong style="float: right">
            <span class="text-danger">*</span> Campos obligatorios
          </strong>
        </b-col>
      </b-row>
      <!-- <b-row>
        <b-col md="4">
          <label for="input-company">
            <span class="text-danger">*</span> Compañía
          </label>
          <b-form-select id="input-company">
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback
            id="input-live-feedback"
            >{{
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-parent"> Principal </label>
          <b-form-select id="input-parent">
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback
            id="input-live-feedback"
            >{{
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-branch"> Sucursal (Instalación) </label>
          <b-form-select id="input-branch">
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
          </b-form-select>
          <b-form-invalid-feedback
            id="input-live-feedback"
            >{{
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row> -->

      <b-row>
        <b-col md="3">
          <span class="text-danger">*</span>
          <label for="input-document-ci">
            <b-form-radio-group
              v-model="formData.identification_type"
              :disabled="disabledData"
            >
              <b-form-radio
                class="radio-document-type"
                v-bind:key="1"
                :value="1"
                selected
                >Rut</b-form-radio
              >
              <b-form-radio
                class="radio-document-type"
                v-bind:key="2"
                :value="2"
                >Pasaporte</b-form-radio
              >
            </b-form-radio-group>
          </label>
          <b-form-input
            id="input-document-id"
            type="text"
            maxlength="20"
            v-model="formData.identification_id"
            :state="states.identification_id"
            :disabled="disabledData"
            @change="
              formData.identification_type == 2
                ? isPassport(formData.identification_id)
                : checkRut(formData.identification_id)
            "
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.identification_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="3">
          <label for="input-valid-document">
            <span class="text-danger">*</span> Fecha de emisión
          </label>
          <b-form-input
            id="input-valid-start"
            type="date"
            v-model="document.start"
            :state="states.start"
            @change="isNotNull('start')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.start
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="3">
          <label for="input-valid-document">
            <span class="text-danger">*</span> Fecha de vencimiento
          </label>
          <b-form-input
            id="input-valid-finish"
            type="date"
            v-model="document.finish"
            :state="states.finish"
            @change="isNotNull('finish')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.finish
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col sm="3">
          <document-input
            @input="inputFile(...arguments)"
            :state="states.file"
            :fileExt="document"
          />
        </b-col>
      </b-row>

      <b-row>
        <b-col md="4">
          <label for="input-name">
            <span class="text-danger">*</span> Nombres
          </label>
          <b-form-input
            id="input-name"
            v-model="formData.name"
            type="text"
            :state="states.name"
            @input="checkName('name')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.name
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-surname">
            <span class="text-danger">*</span> Apellido Paterno
          </label>
          <b-form-input
            id="input-surname"
            v-model="formData.surname"
            type="text"
            :state="states.surname"
            @input="checkName('surname')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.surname
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-second-surname">Apellido Materno</label>
          <b-form-input
            id="input-second-surname"
            v-model="formData.second_surname"
            type="text"
            :state="states.second_surname"
            @input="checkName('second_surname')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.second_surname
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="6">
          <label for="input-email">
            <span class="text-danger">*</span> Email
          </label>
          <b-form-input
            id="input-email"
            v-model="formData.email"
            type="email"
            :state="states.email"
            @input="checkEmail()"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.email
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-phone">
            <span class="text-danger">*</span> Número de contacto
          </label>
          <b-form-input
            id="input-phone"
            v-model="formData.phone"
            type="number"
            :state="states.phone"
            @keypress="isNumber()"
            @input="isNotNull('phone')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.phone
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="6">
          <label for="input-nacionality">
            <span class="text-danger">*</span> País de Origen
          </label>
          <b-form-select
            id="input-nacionality"
            v-model="formData.nationality"
            :state="states.nationality"
            :disabled="disabledData"
            @change="isNotNull('nationality')"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="country in this.$props.dataList.countries"
              v-bind:key="country"
              :value="country"
            >
              {{ country }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.nationality
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-date-birth">
            <span class="text-danger">*</span> Fecha Nacimiento
          </label>
          <b-form-input
            id="input-date-birth"
            v-model="formData.birthday"
            type="date"
            :state="states.birthday"
            @change="isNotNull('birthday')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.birthday
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>

      <b-row>
        <b-col md="6">
          <label for="input-gender"
            ><span class="text-danger">*</span>Género</label
          >
          <b-form-radio-group
            id="input-gender"
            v-model="formData.gender"
            :disabled="disabledData"
            :state="states.gender"
            @change="isNotNull('gender')"
          >
            <b-form-radio v-bind:key="1" :value="1" selected
              >Masculino</b-form-radio
            >
            <b-form-radio v-bind:key="2" :value="2">Femenino</b-form-radio>
            <b-form-radio v-bind:key="3" :value="3">No responde</b-form-radio>
          </b-form-radio-group>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.gender
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6 pt-4">
          <b-form-checkbox
            :value="true"
            :unchecked-value="false"
            v-model="formData.disability"
            :disabled="disabledData"
            >Discapacidad</b-form-checkbox
          >
        </b-col>
      </b-row>

      <b-row>
        <b-col md="4">
          <label for="input-region">
            <span class="text-danger">*</span> Región
          </label>
          <b-form-select
            id="input-region"
            v-model="region"
            :state="states.region"
            @input="loadCommunes($event)"
            :disabled="disabledData"
            @change="isNotNull('region')"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="region in dataList.regions"
              v-bind:key="region.id"
              :value="region"
            >
              {{ region.name }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.region
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-comuna">
            <span class="text-danger">*</span> Comuna
          </label>
          <b-form-select
            id="input-comuna"
            v-model="formData.commune_id"
            :state="states.commune_id"
            @change="isNotNull('commune_id')"
            :disabled="disabledData"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="commune in communes"
              v-bind:key="commune.id"
              :value="commune.id"
            >
              {{ commune.name }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.commune_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-address">
            <span class="text-danger">*</span> Dirección
          </label>
          <b-form-input
            id="input-address"
            type="text"
            v-model="formData.address"
            :state="states.address"
            @change="isNotNull('address')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.address
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-row>
        <b-col sm="4">
          <label for="input-salary">
            <span class="text-danger">*</span> Sueldo Imponible
          </label>
          <b-form-input
            id="input-salary"
            v-model="formData.payment"
            type="number"
            @keypress="isNumber()"
            :state="states.payment"
            :disabled="disabledData"
            @change="isNotNull('payment')"
          ></b-form-input>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.payment
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-type">
            <span class="text-danger">*</span> Tipo de Trabajo
          </label>
          <b-form-select
            id="input-type"
            v-model="formData.job_type_id"
            :state="states.job_type_id"
            @change="isNotNull('job_type_id')"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="type in dataList.job_types"
              v-bind:key="type.id"
              :value="type.id"
            >
              {{ type.name }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.job_type_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-working-day">
            <span class="text-danger">*</span> Jornada
          </label>
          <b-form-input
            id="input-working-day"
            v-model="formData.working_day"
            type="text"
            :state="states.working_day"
            :disabled="disabledData"
            @change="isNotNull('working_day')"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.working_day
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
      <!-- <b-row>
        <b-col md="4">
          <label for="input-start">
            <span class="text-danger">*</span> Fecha Inicio de Contrato
          </label>
          <b-form-input
            id="input-start"
            v-model="formData.contract_start"
            type="date"
            :state="states.contract_start"
            @change="isNotNull('contract_start')"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.contract_start
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-finished">Fecha Termino de Contrato</label>
          <b-form-input
            id="input-finished"
            v-model="formData.contract_finished"
            type="date"
            :state="states.contract_finished"
            :disabled="disabledData"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.contract_finished
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row> -->
      <b-row>
        <b-col class="text-right">
          <b-button class="button-create" type="submit" variant="success">{{
            !this.$truthty(this.$props.employee) ? "Crear" : "Actualizar"
          }}</b-button>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
const idDocumentRequired = [3, 4, 7, 13, 15];
import * as utils from "../../utils";
export default {
  props: ["service", "employee", "data-list", "is_update", "auth"],

  data() {
    let formData = {
      name: null,
      surname: null,
      second_surname: null,
      birthday: null,
      address: null,
      email: null,
      phone: null,
      gender: null,
      nationality: null,
      working_day: null,
      disability: null,
      identification_type: 1,
      identification_id: null,
      payment: null,
      job_type_id: null,
      commune_id: null,
    };

    let document = {
      document_type_id: this.service.doc_cedula_id,
      service_id: this.service.id,
      employee_id: null,
      start: null,
      finish: null,
      path_data: null,
      validation_state_id: 2,
      id: null,
      file: null,
      name: null,
    };
    let region = null;
    let communes = [];
    if (this.$truthty(this.employee)) {
      let localDocument = this.employee.documents[0];
      formData = this.employee;
      region = formData.commune.region;
      region = this.dataList.regions.find((x) => {
        return region.id == x.id;
      });
      communes = region.communes;
      console.log(this.dataList);
      if (this.$truthty(document)) {
        document = localDocument;
        document.file = null;
      }
    }
    return {
      sendStatus: false,
      error: 0,
      errors: [],
      errorsData: [],
      region,
      disabledData: false,
      communes,
      document,
      formData,
      message: {
        region: null,
        job_type_id: null,
        commune_id: null,
        branch_office: null,
        identification_id: null,
        identification_type: null,
        name: null,
        surname: null,
        second_surname: null,
        birthday: null,
        address: null,
        email: null,
        phone: null,
        contract_start: null,
        contract_finished: null,
        gender: null,
        start: null,
        finish: null,
        nationality: null,
        working_day: null,
        payment: null,
        file: null,
      },
      states: {
        region: null,
        job_type_id: null,
        commune_id: null,
        identification_id: null,
        identification_type: null,
        name: null,
        surname: null,
        second_surname: null,
        birthday: null,
        address: null,
        email: null,
        phone: null,
        contract_start: null,
        contract_finished: null,
        gender: null,
        start: null,
        finish: null,
        nationality: null,
        working_day: null,
        payment: null,
        file: null,
      },
      validations: {
        // region: "La región es requerida",
        commune_id: "La comuna es requerida",
        job_type_id: "El tipo de trabajo es requerido",
        identification_id: "El número de documento es requerido",
        name: "El nombre es requerido",
        surname: "El apellido es requerido",
        birthday: "La fecha de nacimiento es requerida",
        address: "La dirección es requerida",
        email: "El email es requerido",
        phone: "El número de contacto es requerido",
        // contract_start: "La fecha de inicio de contrato es requerida",
        gender: "El género es requerido",
        // start: "La emision del documento es requerida",
        // finish: "El vencimiento del documento es requerido",
        nationality: "La nacionalidad es requerida",
        working_day: "La jornada laboral es requerida",
        payment: "El sueldo base es requerido",
      },
    };
  },
  methods: {
    submit() {
      let url = `${window.location.origin}/employees`;
      let config = { headers: { "Content-Type": "multipart/form-data" } };
      let formData = new FormData();
      formData.append("employee", JSON.stringify(this.formData));
      formData.append("document", JSON.stringify(this.document));
      formData.append("file", this.document.file);
      // console.log(this.document);
      this.sendStatus = true;
      if (this.$truthty(this.employee)) {
        url = url + "/update";
      }
      axios
        .post(url, formData)
        .then((res) => {
          window.location.href = `${window.location.origin}/employees`;
          // this.sendStatus = false;
          // this.$refs["modal-confirm"].hide();
        })
        .catch((err) => {
          this.sendStatus = false;
          this.$refs["modal-confirm"].hide();
          this.errors = [];
          this.errors.push({ message: err.response.data.message });
          this.error = 1;
        });
    },
    checkForm() {
      this.error = 0;
      this.errors = [];
      Object.keys(this.validations).forEach((param) => {
        if (!this.$truthty(this.formData[param])) {
          this.error = 1;
          console.log("error param " + param);
          this.message[param] = this.validations[param];
          this.states[param] = false;
        } else {
          this.message[param] = "";
          this.states[param] = true;
        }
      });
      if (!this.$truthty(this.region)) {
        this.error = 1;
        console.log("error param región");
        this.message.region = "La región es requerida";
        this.states.region = false;
      } else {
        this.message.region = "";
        this.states.region = true;
      }
      if (!this.$truthty(this.document.id)) {
        console.log(this.document);
        if (
          this.document.file == null &&
          !this.$truthty(this.document.path_data)
        ) {
          console.log("error param document");
          this.states.file = false;
          this.message.file = "El archivo es requerido";
          this.error = 1;
        } else {
          this.message.file = "";
          this.states.file = true;
        }
      }
      if (!this.$truthty(this.document.finish)) {
        this.states.finish = false;
        this.message.finish = "El vencimiento del documento es requerido";
        this.error = 1;
      } else {
        this.message.finish = "";
        this.states.finish = true;
      }

      if (!this.$truthty(this.document.start)) {
        this.states.start = false;
        this.message.start = "La emision del documento es requerida";
        this.error = 1;
      } else {
        this.message.start = "";
        this.states.start = true;
      }
      if (this.errors.length > 0) {
        this.error = 1;
      }
      if (this.error == 0) {
        this.$refs["modal-confirm"].show();
      } else {
        const element = document.getElementById("errors-div");
        element.scrollIntoView({ behavior: "smooth" });
        return false;
      }
    },
    loadCommunes(region) {
      if (this.$truthty(region)) {
        this.communes = region.communes;
      } else {
        this.communes = [];
      }
    },
    isNumber: function (evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    isNotNull(param) {
      if (this.formData[param] === null || this.formData[param] === "") {
        this.states[param] = false;
      } else {
        this.states[param] = true;
      }
    },
    formatter(label, value) {
      if (label === "phone") {
        if (label === "phone" && String(value).length < 6) {
          this.states[label] = false;
          this.message[label] = "Campo requerido";
        } else {
          this.states[label] = true;
        }
        return String(value).substring(0, 10);
      } else if (
        label === "name" ||
        label === "surname" ||
        label === "second_surname"
      ) {
        if (value.length < 2) {
          this.states[label] = false;
          this.message[label] = "Campo requerido";
        } else {
          this.states[label] = true;
        }
      } else if (label === "identification_type") {
        if (this.formData.identification_type == 1) {
          if (value.length < 9) {
            this.states[label] = false;
            this.message[label] = "El pasaporte es requerido";
          } else {
            this.checkRut(value);
          }
        } else {
          if (value.length < 5) {
            this.states[label] = false;
            this.message[label] = "El pasaporte es requerido";
          } else {
            this.states[label] = true;
          }
        }
      } else {
        if (value.length < 5) {
          this.states[label] = false;
          this.message[label] = "Campo requerido";
        } else {
          this.states[label] = true;
        }
      }
      return value;
    },
    checkRut(valor) {
      // Despejar Puntos y guion
      valor = valor.replace(/[.-]/g, "");

      // Aislar Cuerpo y Dígito Verificador
      let cuerpo = valor.slice(0, -1);
      let dv = valor.slice(-1).toUpperCase();

      // Calcular Dígito Verificador
      let suma = 0;
      let multiplo = 2;

      let arrayMultiplos = [2, 3, 4, 5, 6, 7];
      let index = 0;

      // Para cada dígito del Cuerpo
      for (let i = 1; i <= cuerpo.length; i++) {
        let valorCuerpo = Number(cuerpo[cuerpo.length - i]);
        let producto = valorCuerpo * arrayMultiplos[index];
        index++;
        if (index == arrayMultiplos.length) {
          index = 0;
        }
        suma = suma + producto;
      }

      // Calcular Dígito Verificador en base al Módulo 11
      let dvEsperado = 11 - (suma % 11);

      // Casos Especiales (0 y K)

      let dvAux_k = dv == "K" ? 10 : dv;
      let dvAux_0 = dv == 0 ? 11 : dv;

      // Validar que el Cuerpo coincide con su Dígito Verificador
      if (this.formData.identification_type === 1) {
        if (
          dvEsperado != dv &&
          dvEsperado != dvAux_k &&
          dvEsperado != dvAux_0
        ) {
          this.states.identification_id = false;
          this.message.identification_id = "El formato del rut no es correcto";
        } else {
          this.states.identification_id = true;
          this.message.identification_id = "";
        }
      } else {
        this.states.identification_id = true;
        this.message.identification_id = "";
      }
    },
    checkName(param) {
      if (this.formData[param] === null || this.formData[param] === "") {
        this.states[param] = false;
      } else if (!/^[a-zA-Z- ]+$/.test(this.formData[param])) {
        this.states[param] = false;
      } else {
        this.states[param] = true;
      }
    },
    checkEmail() {
      if (this.formData.email === null || this.formData.email === "") {
        this.states.email = false;
      } else if (
        !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(
          this.formData.email
        )
      ) {
        this.states.email = false;
      } else {
        this.states.email = true;
      }
    },
    isPassport: function (evt) {
      evt = evt ? evt : window.event;
      var value = evt.key;
      if (!/^[a-zA-Z0-9]/.test(value)) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    isRut: function (evt) {
      if (this.formData.document_type === 1) {
        evt = evt ? evt : window.event;
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          if (charCode === 75 || charCode === 107) {
            //k or K
            this.checkRut();
            return true;
          }
          evt.preventDefault();
        } else {
          this.checkRut();
          return true;
        }
      }
    },
    inputFile(file) {
      if (file == null) {
        this.document.path_data = null;
        this.document.validation_state_id = 1;
        this.states.file = false;
        this.message.file = "El archivo es requerido";
      } else {
        this.document.validation_state_id = 2;
        this.states.file = null;
        this.message.file = null;
        this.document.name = "Cédula de Identidad";
      }
      this.document.file = file;
      console.log(this.document.file);
    },
    init() {},
  },
  mounted() {
    this.init();
  },
};
</script>
