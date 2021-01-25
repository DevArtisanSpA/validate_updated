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
          Se modificarán datos y se agregará un nuevo documento
        </div>

        <div class="d-block text-center" style="margin-bottom: 20px">
          <span>¿Estás seguro de finiquitar este empleado?</span>
        </div>

        <div v-if="!send" class="float-right">
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
      <b-row>
        <b-col md="6">
          <label for="input-service">
            <span class="text-danger">*</span> Servicio
          </label>
          <b-form-select
            id="input-service"
            v-model="service"
            @input="loadEmployees($event)"
            :state="states.service"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="service in datas_list.services"
              v-bind:key="'service-' + service.id"
              :value="service"
            >
              {{ service.description }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            message.service
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-branch">
            <span class="text-danger">*</span> Empleado
          </label>
          <b-form-select
            id="input-branch"
            @input="changeEmployee"
            v-model="employee"
            :state="states.employee"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="employee in employees"
              v-bind:key="'employee-' + employee.id"
              :value="employee"
            >
              {{ `${employee.name} ${employee.surname}` }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            message.employee
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-finished">
            <span class="text-danger">*</span> Fecha
          </label>
          <b-form-input
            id="input-finished"
            v-model="contract_finished"
            type="date"
            @input="changeDateContrac"
            :state="states.contract_finished"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            message.contract_finished
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-type">
            <span class="text-danger">*</span> Finiquito
          </label>
          <document-input
            @input="inputFile(...arguments)"
            :state="states.file"
            :fileExt="document"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            message.file
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="d-flex justify-content-end">
          <b-button class="button-create" type="submit" variant="success"
            >Finiquitar</b-button
          >
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
export default {
  props: ["datas_list", "auth"],
  data() {
    let employeesLocal = [];

    if (this.auth.id_type > 1) {
      var companies = this.datas_list.companies;
      const id_company = this.auth.id_company;
      employeesLocal = companies.find(function (company) {
        return company.id == id_company;
      }).employees;
    }

    return {
      send: false,
      error: 0,
      errors: [],
      errorsData: [],
      employees: employeesLocal,
      contract_finished: null,
      service: null,
      employee: null,
      file: null,
      document: {
        document_type_id: 65,
        service_id: null,
        employee_id: null,
        start: null,
        finish: null,
        path_data: null,
        validation_state_id: 2,
        id: null,
        file: null,
        name: null,
      },
      validations: {
        service: "El servicio es requerida",
        employee: "El empleado es requerido",
        contract_finished: "La fecha es requerida",
      },
      message: {
        service: null,
        employee: null,
        contract_finished: null,
        file: null,
      },
      states: {
        service: null,
        employee: null,
        contract_finished: null,
        file: null,
      },
    };
  },
  methods: {
    checkForm() {
      this.error = 0;
      this.errors = [];
      Object.keys(this.validations).forEach((param) => {
        if (!this.$truthty(this[param])) {
          this.error = 1;
          console.log(param);
          this.message[param] = this.validations[param];
          this.states[param] = false;
        } else {
          this.message[param] = "";
          this.states[param] = true;
        }
      });
      if (this.document.file == null) {
        // this.errors.push({ message: this.validations.file });
        this.states.file = false;
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
    loadEmployees(service) {
      this.employees = service.employees;
      this.document.service_id = service.id;
    },
    changeDateContrac(value) {
      this.document.start = value;
      this.document.finish = value;
    },
    changeEmployee(value) {
      this.document.employee_id = value.id;
    },
    submit() {
      const urlEmployee = `${window.location.origin}/employee/terminate/${this.employee.id}`;
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      let formData = new FormData();
      formData.append("document", JSON.stringify(this.document));
      formData.append("file", this.document.file);
      this.send = true;
      axios
        .post(urlEmployee, formData)
        .then((res) => {
          // this.send = false;
          // this.$refs["modal-confirm"].hide();
          window.location.href = `${window.location.origin}/employees`;
        })
        .catch((err) => {
          this.send = false;
          this.$refs["modal-confirm"].hide();
          this.errors = [];
          this.errors.push({ message: err.response.data.message });
          this.error = 1;
        });
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    formatDate(additionalDays) {
      let d = new Date();
      if (additionalDays) {
        d.setDate(d.getDate() + additionalDays);
      }
      let month = "" + (d.getMonth() + 1);
      let day = "" + d.getDate();
      let year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;
      return [year, month, day].join("-");
    },
    inputFile(file) {
      if (file == null) {
        this.document.path_data = null;
        this.document.validation_state_id = 1;
        this.states.file = false;
        this.message.file = "El finiquito es requerido";
      } else {
        this.document.validation_state_id = 2;
        this.states.file = null;
        this.message.file = null;
        this.document.name = "Finiquito";
      }
      this.document.file = file;
      this.file = file;
    },
  },
};
</script>
