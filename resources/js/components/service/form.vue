<template>
  <form class="pt-2 pb-5">
    <b-row>
      <b-col md="4">
        <label for="input-rut"> <span class="text-danger">*</span> Rut Empresa contratista</label>
        <b-form-input
          id="input-rut"
          type="text"
          v-model="rut_company"
          disabled
        />
        <b-form-invalid-feedback id="input-live-feedback">{{ rut_message }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="4">
        <label for="input-branch-office"> <span class="text-danger">*</span> Sucursal</label>
        <b-form-select
          id="input-branch-office"
          v-model="service.branch_office_id"
          :state="this.states.branch_office_id"
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
      <b-col md="4">
        <label for="input-service-type"> <span class="text-danger">*</span> Tipo de servicio</label>
        <b-form-select
          id="input-service-type"
          v-model="service.service_type_id"
          :state="this.states.service_type_id"
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
  props: ["auth", "rut_company", "company_id", "service_types", "branch_offices"],
  data() {
    const {
      rut_company,
      company_id,
      service_types,
      branch_offices
    } = this.$props
    return {
      service: {
        company_id: company_id,
        branch_office_id: "",
        service_type_id: "",
      },
      states: {
        branch_office_id: true,
        service_type_id: true,
      },
      rut_company: rut_company,
      serviceTypes: service_types,
      branchOffices: branch_offices
    }
  },
  methods: {
    submit() {
      const {
        rut_company
      } = this

      const url = `${window.location.origin}/companies/rut/${rut_company}`;
      axios.get(url).then(response => {
        if (response.status == 200) {
          const {
            data: {
              company
            }
          } = response
          if (this.$truthty(company)) {
            if (company.id == this.$props.auth.company_id) {
              this.rut_state = false
              this.rut_message = "La empresa contratista no puede ser igual a su empresa"
            }
            else {
              //La empresa ya existe
            }
          }
          else {
            this.rut_state = false
            this.rut_message = "La empresa contratista es inválida"
          }
        }
        else {
          //la empresa no existe
        }
      })
    }
  },
  mounted() {
    if (this.$props.setStage) {
      alert("Aaaaa")
    }
  }
}
</script>
