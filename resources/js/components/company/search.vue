<template>
  <form class="pt-2 pb-5">
    <b-row>
      <b-col>
        <label for="input-rut"> <span class="text-danger">*</span> Rut Empresa contratista</label>
        <b-form-input
          id="input-rut"
          type="text"
          v-model="rut_company"
          :state="this.rut_state"
          :formatter="(e) => checkRut(e)"
        />
        <b-form-invalid-feedback id="input-live-feedback">{{ rut_message }}</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <b-row>
      <b-button class="m-3" variant="success" @click="checkCompany">
        Siguiente
      </b-button>
    </b-row>
  </form>
</template>

<script>
export default {
  props: ["auth"],
  name: "CompanySearch",
  data() {
    return {
      rut_company: "",
      rut_state: null,
      rut_message: ""
    }
  },
  methods: {
    checkRut(newRut) {
      if (newRut.length < 3) {
          this.rut_state = false;
          this.rut_message = "El rut debe ser entregado";
      } else {
        this.rut_state = true;
        this.rut_message = "";
      }
      return newRut;
    },
    checkCompany() {
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
              const companyData = {
                id: company.id,
                rut: company.rut
              }
              this.$emit("setCompanyData", companyData)
              this.$emit("setStage", 3)
            }
          }
          else {
            this.rut_state = false
            this.rut_message = "La empresa contratista es inválida"
          }
        }
        else {
          const companyData = {
            id: null,
            rut: this.rut_company
          }
          this.$emit("setCompanyData", companyData)
          this.$emit("setStage", 2)
        }
      })
    }
  }
}
</script>
