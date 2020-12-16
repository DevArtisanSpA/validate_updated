<template>
  <div>
    <form @submit.prevent="checkForm">
      <!--<form @submit.prevent="submit">-->
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title"><h5>IMPORTANTE</h5></div>

        <div class="d-block text-center mt-2 mb-4">
          <span>¿Estás seguro de {{!is_update ? 'crear' : 'modificar'}} esta oficina?</span>
        </div>
        <div v-if="!send" class="float-right">
          <b-button @click.prevent="hideModal" variant="outline-secondary">Cancelar</b-button>
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
        <div v-else class="d-flex justify-content-center" style="font-size:36px">
          <i class="el-icon-loading"></i>
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
        <b-col class="text-right">
          <strong>
            <span class="text-danger">*</span> Campos obligatorios
          </strong>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6">
          <label for="input-company">
            <span class="text-danger">*</span> Empresa
          </label>
          <b-form-select 
            id="input-company" 
            v-model="formData.company_id"
            :disabled="disabledCompany"
            :state="states.company">
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="company in this.$props.companies"
              v-bind:key="company.id"
              :value="company.id"
            >{{ company.business_name.toUpperCase()  }}</option>
          </b-form-select>
        </b-col>
        <b-col md="6">
          <label for="input-name">
            <span class="text-danger">*</span> Nombre Sucursal
          </label>
          <b-form-input 
            id="input-name" 
            type="text" 
            v-model="formData.name"
            :formatter="(e) => formatter('name', e)"
            :state="this.states.name"
          />
          <b-form-invalid-feedback id="input-live-feedback">
            {{this.message.name}}
          </b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <label for="input-region">
            <span class="text-danger">*</span> Región
          </label>
          <b-form-select
            id="input-region"
            v-model="formData.region_id"
            @input="loadCommunes(formData.region_id, !disabled)"
            :disabled="disabled"
            :state="this.states.region_id"
            :formatter="(e) => formatter('region_id', e)"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="region in this.$props.regions"
              v-bind:key="region.id"
              :value="region.id"
            >
              {{ region.name }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.region_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-comuna">
            <span class="text-danger">*</span> Comuna
          </label>
          <b-form-select
            id="input-comuna"
            v-model="formData.commune_id"
            :disabled="disabled"
            :state="this.states.commune_id"
            :formatter="(e) => formatter('commune_id', e)"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="commune in communes"
              v-bind:key="commune.id"
              :value="commune.id"
            >
              {{ commune.name.toCamelCase() }}
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
            :disabled="disabled"
            :state="this.states.address"
            :formatter="(e) => formatter('address', e)"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.address
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <label for="input-phone1">
            <span class="text-danger">*</span> Teléfono 1
          </label>
          <b-form-input
            id="input-phone1"
            type="text"
            v-model="formData.phone1"
            :state="this.states.phone1"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.phone1
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-phone2">Teléfono 2</label>
          <b-form-input
            id="input-phone2"
            type="text"
            v-model="formData.phone2"
            :state="this.states.phone2"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.phone2
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-button
        class="my-4"
        type="submit"
        variant="success"
      >{{!is_update ? 'Crear' : 'Actualizar'}}</b-button>
    </form>
  </div>
</template>

<script>
export default {
  props: ["branch", "companies", "is_update", "auth", "regions"],
  data() {
    const { regions } = this.$props;
    return {
      send: false,
      error: 0,
      errors: [],
      communes: [],
      formData: {
        id: this.$props.branch === undefined ? null : this.$props.branch.id,
        company_id:
          this.$props.branch === undefined
            ? this.$props.auth.company_id
            : this.$props.branch.company_id,
        name: this.$props.branch === undefined ? null : this.$props.branch.name,
        region_id: this.$props.branch === undefined ? null : this.$props.branch.commune.region_id,
        commune_id: this.$props.branch === undefined ? null : this.$props.branch.commune.id,
        address: this.$props.branch === undefined ? null : this.$props.branch.address,
        phone1: this.$props.branch === undefined ? null : this.$props.branch.phone1,
        phone2: this.$props.branch === undefined ? null : this.$props.branch.phone2,
      },
      states: {
        name: null,
        company_id: null,
        commune_id: null,
        region_id: null,
        address: null,
        phone1: null,
        phone2: null
      },
      message: {
        name: '',
        company_id: '',
        commune_id: '',
        region_id: '',
        address: '',
        phone1: '',
        phone2: ''
      },
      disabledCompany: this.$props.is_update || this.$props.auth.user_type_id > 1 ? true: false
    };
  },
  methods: {
    checkForm(){
        this.error = 0;
        this.errors = [];
        this.checkCompany();
        this.checkBranchName();
        this.checkPhone1();
        if(this.error === 0){
            this.$refs['modal-confirm'].show();
        }
        else{
            return false;
        }
    },
    loadCommunes($region_id, reset = true) {
      console.log($region_id)
      if (this.$truthty($region_id)) {
        let regions = this.$props.regions;
        let communesLocal = regions.find((region) => {
          return region.id == $region_id;
        }).communes;
        this.communes = communesLocal;
      }
      if (reset) {
        this.formData.branchOffice.commune_id = null;
      }
    },    
    checkPhone1(){
      this.states.phone1 = true;

      switch(this.formData.phone1) {
        case null:
        case '':
          this.error = 1;
          this.states.phone1 = false;
          this.message.phone1 = "Teléfono de sucursal requerido.";
          break;
        default:
          if(this.formData.phone1.length < 8){
            this.error = 1;
            this.states.phone1 = false;
            this.message.phone1 = "Teléfono de sucursal no cumple con el mínimo de números";
          }
      }
    },
    checkBranchName(){
      this.states.name = true;

      switch(this.formData.name) {
        case null:
        case '':
          this.error = 1;
          this.states.name = false;
          this.message.name = "Nombre de sucursal requerido.";
          break;
        default:
          if(this.formData.name.length < 3){
            this.error = 1;
            this.states.name = false;
            this.message.name = "Nombre de sucursal requiere mínimo de 3 letras.";
          }
      }
    },
    checkCompany(){
      if(this.formData.company_id === null && this.disabledCompany) {
          this.error = 1;
          this.states.company_id = false;
          this.message.company_id = "Se debe seleccionar una compañía";
      }
    },
    formatter(label, value) {
        if(value.length < 3){
          this.error = 1;
          if(label === 'name'){
            this.states.name = false;
            this.message.name = "Nombre de sucursal requiere mínimo de 3 letras.";
          }
          if(label === 'address'){
            this.states.address = false;
            this.message.address = "Dirección requiere mínimo de 3 letras.";
          }
        } else {
          if(label === 'name'){
            this.states.name = true;
            this.message.name = "";
          }
          if(label === 'address'){
            this.states.address = true;
            this.message.address = "";
          }
        }
        return value;
    },
    submit() {
      this.send = true;
      if (this.$props.is_update) {
        const url = `${window.location.origin}/branch_offices/${this.formData.id}`;
        axios.patch(url, this.formData)
          .then(res => {
            if(res.status === 200){
              window.location.href = window.location.origin + '/branch_offices';
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
        const url = `${window.location.origin}/branch_offices`;
        axios.post(url, this.formData)
          .then(res => {
            if(res.status === 200){
              window.location.href = window.location.origin + '/branch_offices';
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
            if(err.response.status !== 500){
              this.errors.push({ message: err.response.data.message });
            } else {
              this.errors.push({ message: "Error de base de datos. Por favor contactar con el equipo de soporte." });
            }
            this.error = 1;
        });
      }
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    init() {
      if (this.$props.branch !== undefined) {
        let region_id = this.$props.branch.commune.region_id;
        this.loadCommunes(region_id);
        this.formData.commune_id = this.$props.branch.commune_id;
      }
    },
  },

  mounted() {
    this.init();
  },
};
</script>
