<template>
  <form @submit.prevent="checkForm" class="pt-2 pb-5">
    <!--<form @submit.prevent="submit">-->
    <b-modal ref="modal-confirm" hide-footer>
      <div slot="modal-title"><h5>IMPORTANTE</h5></div>

      <div class="d-block text-center mt-2 mb-4">
        <span>¿Estás seguro de {{this.$props.user === undefined ? 'crear' : 'modificar'}} este usuario?</span>
      </div>

      <div class="float-right">
        <b-button @click="hideModal">Cancelar</b-button>
        <b-button variant="primary" @click="submit">Aceptar</b-button>
      </div>
    </b-modal>

    <div v-if="errors.length">
      <b-alert variant="danger" show>
        <ul class="mb-0 mx-3">
          <li v-for="(error, index) in errors" v-bind:key="index">
            {{ error.message }}
          </li>
        </ul>
      </b-alert>
    </div>
    <b-row>
      <b-col>
        <strong class="float-right">
          <span class="text-danger">*</span> Campos obligatorios
        </strong>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4">
        <label for="input-name">
          <span class="text-danger">*</span>Nombre
        </label>
        <b-form-input
          id="input-name"
          type="text"
          v-model="formdata.name"
          :maxlength="50"
          :state="name_state"
          @input="checkName"
        ></b-form-input>
      </b-col>
      <b-col md="4">
        <label for="input-email">
          <span class="text-danger">*</span>Email
        </label>
        <b-form-input
          id="input-email"
          type="text"
          v-model="formdata.email"
          :state="email_state"
          :maxlength="50"
          @input="checkEmail"
        ></b-form-input>
      </b-col>
      <b-col md="4">
        <label for="input-company"><span class="text-danger">*</span>Empresa</label>
        <b-form-select
          id="input-company"
          type="text"
          v-model="formdata.company_id"
          :state="company_state"
          @input="checkCompany"
        >
          <template v-slot:first>
            <option :value="null" disabled>Selecciona una opción</option>
          </template>
          <option
            v-for="company in this.$props.create.companies"
            v-bind:key="company.id"
            :value="company.id"
          >{{ company.business_name.toUpperCase() }}</option>
        </b-form-select>
      </b-col>
    </b-row>
    <b-row >
      <b-col class="text-right">
      <b-button
        class="m-3"
        type="submit"
        variant="success"
      >{{this.$props.user === undefined ? 'Crear' : 'Actualizar'}}</b-button>
    </b-col></b-row>
  </form>
</template>

<script>
export default {
  props: ["user", "create", "is_update"],
  data() {
    return {
      error: 0,
      errors: [],
      name_state: null,
      company_state: null,
      email_state: null,
      formdata: {
        id: this.$props.user === undefined ? null : this.$props.user.id,
        name: this.$props.user === undefined ? null : this.$props.user.name,
        email: this.$props.user === undefined ? null : this.$props.user.email,
        company_id: this.$props.user === undefined ? null : this.$props.user.company_id,
        user_type_id: 2
      },
    };
  },
  methods: {
    checkName() {
      if (this.formdata.name === null || this.formdata.name === "") {
        this.name_state = false;
      } else {
        this.name_state = true;
      }
    },
    checkEmail() {
      if (this.formdata.email === null || this.formdata.email === "") {
        this.email_state = false;
      } else {
        this.email_state = true;
      }
    },
    checkCompany() {
      if (this.formdata.company_id === null) {
        this.user_type_state = false;
      } else {
        this.user_type_state = true;
      }
    },
    checkForm() {
      this.error = 0;
      this.errors = [];
      if (this.formdata.name === null || this.formdata.name === "") {
        this.errors.push({ message: "Nombre requerido." });
        this.error = 1;
        this.name_state = false;
      }

      if (this.formdata.email === null || this.formdata.email === "") {
        this.errors.push({ message: "Email requerido.", });
        this.error = 1;
        this.email_state = false;
      }

      if (this.formdata.company_id === null) {
        this.errors.push({ message: "Empresa asociada requerida." });
        this.error = 1;
        this.user_type_state = false;
      }

      if(this.error === 0){
          this.$refs['modal-confirm'].show();
      }
      else{
          return false;
      }
    },
    submit() {
      if (this.$props.is_update) {

        const url = `${window.location.origin}/users/${this.formdata.id}`;
        axios.patch(url, this.formdata)
          .then(res => {
            if(res.status === 200){
              window.location.href = window.location.origin + '/users';
            } else {
              this.$refs["modal-confirm"].hide();
              this.errors = [];
              res.data.forEach( row => this.errors.push({message: row}));
              this.error = 1;
            }
          })
          .catch(err => {
            this.$refs["modal-confirm"].hide();
            this.errors = [];
            this.errors.push({ message: err.response.data.message });
            this.error = 1;
        });
      } else {
        const url = `${window.location.origin}/users`;
        axios
          .post(url, this.formdata )
          .then((res) => {
            if(res.status === 200){
              window.location.href = window.location.origin + '/users';
            } else {
              this.$refs["modal-confirm"].hide();
              this.errors = [];
              res.data.forEach( row => this.errors.push({message: row}));
              this.error = 1;
            }
          })
          .catch((err) => {
            this.errors = [{message: "Ha ocurrido un error creando el usuario. Inténtelo más tarde."}];
            this.error = 1;
          });
      }
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    init() {
    },
  },
  mounted() {
    this.init();
  },
};
</script>
