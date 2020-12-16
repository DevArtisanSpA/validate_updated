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
        <span>Se asociará un servicio entre dos empresas</span>
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
          :disabled="disabled_company"
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
      <b-button class="m-3" type="submit" variant="success">
        Asociar servicio
      </b-button>
    </b-row>
  </form>
</template>

<script>
export default {
  props: ["auth", "rut_company", "company_id", "service_types", "companies"],
  data() {
    const {
      rut_company,
      company_id,
      service_types,
      companies
    } = this.$props
    return {
      service: {
        company_id: company_id,
        branch_office_id: "",
        service_type_id: "",
        active: false
      },
      states: {
        my_company_id: null,
        branch_office_id: null,
        service_type_id: null,
      },
      my_company_id: null,
      rut_company: rut_company,
      serviceTypes: service_types,
      companies: companies,
      branchOffices: null,
      disabled_company: false,
      errors: []
    }
  },
  methods: {
    formatter(label, value) {
      console.log(label, value)
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
          service_type_id
        }
      } = this
      let error = false
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
      console.log(this.service)

      const url = `${window.location.origin}/services/associate`;
      axios.post(url, this.service).then(response => {
        if (response.status == 200) {
          window.location.href = window.location.origin + "/home";
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
  },
  mounted() {
    const {
      auth
    } = this.$props
    if (!auth.isAdmin && this.$truthty(auth.company_id)) {
      this.company_id = auth.company_id
      this.loadBranchOffices(auth.company_id)
      this.disabled_company = true
    }
  }
}
</script>
