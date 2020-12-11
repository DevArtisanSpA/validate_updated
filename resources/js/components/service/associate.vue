<template>
  <div>
    <company-search v-if="stage == 1" :auth="auth" @setStage="setStage" @setCompanyData="setCompanyData" />
    <company-form v-if="stage == 2" :auth="auth" :data-list="dataList" :rut="company.rut" is_update="0" @setStage="setStage" @setCompanyData="setCompanyData" />
    <service-form v-if="stage == 3" :auth="auth" :rut_company="company.rut" :company_id="company.id" :branch_offices="branchOffices" :service_types="serviceTypes" />
  </div>
</template>

<script>

export default {
  props: ["auth", "dataList", "service_types", "branch_offices"],
  data() {
    const {
      auth,
      service_types,
      branch_offices,
      dataList
    } = this.$props
    return {
      stage: 1,
      serviceTypes: service_types,
      branchOffices: branch_offices,
      auth: auth,
      dataList: dataList,
      company: {
        id: null,
        rut: null
      }
    }
  },
  methods: {
    setCompanyData(companyData) {
      this.company = companyData
    },
    setStage(newStage) {
      this.stage = newStage
    }
  }
}
</script>
