<template>
  <form @submit.prevent="checkForm" class="pt-2 pb-5">
    <div v-if="errors.length">
      <b-alert variant="danger" show>
        <ul class="mb-0 mx-3">
          <li v-for="(error, index) in errors" v-bind:key="index">
            {{ error }}
          </li>
        </ul>
      </b-alert>
    </div>

    <b-modal ref="modal-confirm" hide-footer>
      <div slot="modal-title">
        <span>{{
          isUpdate ? "Se editará un servicio entre dos empresas" : "Se asociará un servicio entre dos empresas"
        }}</span>
      </div>
      <p class="d-block text-center mt-3 mb-5">
        ¿Los datos ingresados son correctos?
      </p>
      <div v-if="!send" class="float-right">
        <b-button @click.prevent="hideModal" variant="outline-secondary"
          >Cancelar</b-button
        >
        <b-button variant="primary" @click="submit">Aceptar</b-button>
      </div>
      <div v-else class="d-flex justify-content-center" style="font-size: 36px">
        <i class="el-icon-loading"></i>
      </div>
    </b-modal>
    <b-row>
      <b-col md="3">
        <label for="input-rut"> <span class="text-danger">*</span> Rut Empresa contratista</label>
        <b-form-input
          id="input-rut"
          type="text"
          v-model="rut_company"
          disabled
        />
        <b-form-invalid-feedback id="input-live-feedback">{{ rut_message }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="3">
        <label for="input-company"> <span class="text-danger">*</span> Empresa que contrata</label>
        <b-form-select
          id="input-company"
          v-model="my_company_id"
          :state="this.states.my_company_id"
          :disabled="disabled_company || isUpdate"
          :formatter="(e) => formatter('my_company_id', e)"
          @change="loadBranchOffices(my_company_id)"
        >
          <template v-slot:first>
            <option :value="null" disabled>Selecciona una opción</option>
          </template>
          <option
            v-for="company in companies"
            v-bind:key="company.id"
            :value="company.id"
          >
            {{ company.business_name }}
          </option>
        </b-form-select>
        <b-form-invalid-feedback id="input-live-feedback">La empresa que contrata es requerida</b-form-invalid-feedback>
      </b-col>
      <b-col md="3">
        <label for="input-branch-office"> <span class="text-danger">*</span> Sucursal</label>
        <b-form-select
          id="input-branch-office"
          v-model="service.branch_office_id"
          :state="this.states.branch_office_id"
          :disabled="isUpdate"
          @change="formatter('branch_office_id', service.branch_office_id)"
        >
          <template v-slot:first>
            <option :value="null" disabled>Selecciona una opción</option>
          </template>
          <option
            v-for="branchOffice in branchOffices"
            v-bind:key="branchOffice.id"
            :value="branchOffice.id"
          >
            {{ branchOffice.name }}
          </option>
        </b-form-select>
        <b-form-invalid-feedback id="input-live-feedback">La sucursal es requerida</b-form-invalid-feedback>
      </b-col>
      <b-col md="3">
        <label for="input-service-type"> <span class="text-danger">*</span> Tipo de servicio</label>
        <b-form-select
          id="input-service-type"
          v-model="service.service_type_id"
          :state="this.states.service_type_id"
          :disabled="isUpdate"
          @change="formatter('service_type_id', service.service_type_id)"
        >
          <template v-slot:first>
            <option :value="null" disabled>Selecciona una opción</option>
          </template>
          <option
            v-for="serviceType in serviceTypes"
            v-bind:key="serviceType.id"
            :value="serviceType.id"
          >
            {{ serviceType.name }}
          </option>
          </b-form-select>
        <b-form-invalid-feedback id="input-live-feedback">El tipo de servicio es requerido</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="6">
        <label for="input-description"> <span class="text-danger">*</span> Descripción del servicio </label>
        <b-form-input
          id="input-description"
          type="text"
          v-model="service.description"
          :state="this.states.description"
          @click="alert('a')"
          @change="formatter('description', service.description)"
        >
        </b-form-input>
        <b-form-invalid-feedback id="input-live-feedback">La descripción del servicio es obligatorio</b-form-invalid-feedback>
      </b-col>
      <b-col md="3">
        <label for="input-start">
          <span class="text-danger">*</span> Fecha de inicio
        </label>
        <b-form-input
          id="input-start"
          type="date"
          v-model="service.start"
          :state="this.states.start"
          @change="formatter('start', service.start)"
        />
        <b-form-invalid-feedback id="input-live-feedback">La fecha de inicio es requerida</b-form-invalid-feedback>
      </b-col>
      <b-col md="3">
        <label for="input-finished">
          <span class="text-danger">*</span> Fecha de término
        </label>
        <b-form-input
          id="input-finished"
          type="date"
          v-model="service.finished"
          :state="this.states.finished"
          @change="formatter('finished', service.finished)"
        />
        <b-form-invalid-feedback id="input-live-feedback">La fecha de término es requerida</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <b-row>
      <b-button class="m-3" type="submit" variant="success">
        {{ isUpdate ? "Editar servicio" : "Asociar servicio" }}
      </b-button>
    </b-row>
  </form>
</template>

<script>
export default {
  props: ["auth", "rut_company", "company_id", "service_types", "companies", "serviceToUpdate"],
  data() {
    const {
      rut_company,
      company_id,
      service_types,
      companies
    } = this.$props
    const truthty = this.$truthty;
    return {
      service: {
        company_id: company_id,
        branch_office_id: "",
        service_type_id: "",
        description: "",
        start: "",
        finished: "",
        active: true
      },
      states: {
        my_company_id: null,
        branch_office_id: null,
        service_type_id: null,
        description: null,
        start: null,
        finished: null,
      },
      my_company_id: null,
      rut_company: rut_company,
      serviceTypes: service_types,
      companies: companies,
      branchOffices: null,
      disabled_company: false,
      isUpdate: false,
      errors: []
    }
  },
  methods: {
    formatter(label, value) {
      if (this.$truthty(value)) {
        this.states[label] = true
      }
      else {
        this.states[label] = false
      }
      //return value
    },
    checkForm() {
      const {
        states: {
          my_company_id,
          branch_office_id,
          service_type_id,
          description,
          start,
          finished
        },
        isUpdate
      } = this
      let error = false
      if (!isUpdate) {
        if (!my_company_id) {
          error = true
          if (my_company_id == null) {
            this.states.my_company_id = false
          }
        }
        if (!branch_office_id) {
          error = true
          if (branch_office_id == null) {
            this.states.branch_office_id = false
          }
        }
        if (!service_type_id) {
          error = true
          if (service_type_id == null) {
            this.states.service_type_id = false
          }
        }
      }
      if (!description) {
        error = true
        if (description == null) {
          this.states.description = false
        }
      }
      if (!start) {
        error = true
        if (start == null) {
          this.states.start = false
        }
      }
      if (!finished) {
        error = true
        if (finished == null) {
          this.states.finished = false
        }
      }
      if (error) {
        return false
      }
      else {
        this.$refs["modal-confirm"].show()
      }
    },
    loadBranchOffices($company_id) {
      if (this.$truthty($company_id)) {
        const {
          companies
        } = this.$props
        let companyBranchOffices = companies.find((company) => {
          return company.id == $company_id;
        }).branch_offices;
        this.branchOffices = companyBranchOffices;
        this.service.branch_office_id = "";
        this.states.my_company_id = true
      }
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    submit(e) {
      e.preventDefault();
      if (this.isUpdate) {
        const url = `${window.location.origin}/services/edit`;
        axios.put(url, this.service).then(response => {
          if (response.status == 200) {
            window.location.href = window.location.origin + "/services";
          }
          else if (response.status == 201 && this.$truthty(response.data)) {
            const {
              data
            } = response
            this.errors = data
          }
          else {
            this.errors.push("Ha ocurrido un error procesando la operación. Inténtelo más tarde.")
          }
        }).catch(err => {
          this.errors.push("Ha ocurrido un error procesando la operación. Inténtelo más tarde.")
        })
      }
      else {
        const url = `${window.location.origin}/services/associate`;
        axios.post(url, this.service).then(response => {
          if (response.status == 200) {
            window.location.href = window.location.origin + "/services";
          }
          else if (response.status == 201 && this.$truthty(response.data)) {
            const {
              data
            } = response
            this.errors = data
          }
          else {
            this.errors.push("Ha ocurrido un error procesando la operación. Inténtelo más tarde.")
          }
        }).catch(err => {
          this.errors.push("Ha ocurrido un error procesando la operación. Inténtelo más tarde.")
        })
      }
    }
  },
  mounted() {
    const {
      auth,
      serviceToUpdate
    } = this.$props
    if (!auth.isAdmin && this.$truthty(auth.company_id)) {
      this.my_company_id = auth.company_id
      this.loadBranchOffices(auth.company_id)
      this.disabled_company = true
    }
    else if(this.$truthty(serviceToUpdate)) {
      const {
        companies
      } = this.$props
      this.service = serviceToUpdate
      this.my_company_id = companies[0].id
      this.branchOffices = companies[0].branchOffices
      this.isUpdate = true
      this.states.description = true
      this.states.start = true
      this.states.finished = true
    }
  }
}
</script>
