<template>
  <div class="col-md-9">
    <div class="d-flex align-items-center justify-content-center flex-column">
        <label class="h3 mr-2">Reportes por empresa</label>        
        <b-form-select 
          v-model="data.selected" 
          class="w-25"
          v-on:change="searchReportData($event)"
        >
          <!-- This slot appears above the options from 'options' prop -->
          <template v-slot:first>
            <b-form-select-option :value="null">Seleccionar una empresa</b-form-select-option>
          </template>

          <b-form-select-option
            v-for="company in data.companies"
            v-bind:key="company.id"
            :value="company.id"
          >{{ company.business_name.toUpperCase()  }}</b-form-select-option>
        </b-form-select>
    </div>
    <div v-if="data.selected !== null && (($truthty(data.principal_data) && data.principal_data.length > 0) 
      || ($truthty(data.principal_data) && data.employee_data.length > 0))">
      <b-row>
        <b-col md="6" class="my-3">
          <div class="card">
            <h5 class="card-header">Documentación empresa base</h5>
            <div class="card-body">
              <div v-if="percent_company > 0">
                <pie-chart 
                  :data="data.principal_data" 
                  :title="['% Documentación empresa base']" 
                  :styles="this.chartStyle"
                  :height="200"
                ></pie-chart>
              </div>
              <div class="d-flex justify-content-between" v-for="(child, index) in data.principal_data" v-bind:key="index">
                <span>{{child.business_name}}</span> <span>{{child.resume.percent}}%</span>
              </div>
            </div>
          </div>
        </b-col>
        <b-col md="6" class="my-3">
          <div class="card">
            <h5 class="card-header">Documentación empleados base</h5>
            <div class="card-body">
              <div v-if="percent_employee > 0">
                <pie-chart 
                  :data="data.employee_data" 
                  :title="['% Documentación empresa base']" 
                  :styles="this.chartStyle"
                  :height="200"
                ></pie-chart>
              </div>
              <div class="d-flex justify-content-between" v-for="(child, index) in data.employee_data" v-bind:key="index">
                <span>{{child.business_name}}</span> <span>{{child.resume.percent}}%</span>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
      <hr>
      <b-row>
        <b-col md="6" class="my-3" v-for="(child, index) in data.contractors_data" v-bind:key="index">
          <div class="card">
            <h5 class="card-header">{{child.business_name}}</h5>
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <strong>Empleados:</strong> <span>{{child.resume.total}}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>Hombres</span> <span>{{child.resume.men}}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>Mujeres</span> <span>{{child.resume.women}}</span>
              </div>
              <div class="d-flex justify-content-between mt-3">
                <strong>Edades</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span>18 a 40 Años</span> <span>{{child.resume.younger}}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>41 a 80 Años</span> <span>{{child.resume.older}}</span>
              </div>
              <div class="d-flex justify-content-between mt-3">
                <strong>Discapacidad</strong> <span>{{child.resume.disability}}</span>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
    </div>
    <div v-if="data.selected === null" class="text-center pt-5 mt-5">
        <h1>Por favor seleccionar una empresa.</h1>
    </div>

    <div v-if="data.selected !== null && (data.principal_data.length == 0 || data.employee_data.length == 0)" class="text-center pt-5 mt-5">
        <h1>Esta empresa no tiene información asociada a mostrar.</h1>
    </div>

  </div>
</template>

<script>
export default {
  props: ["data"],
  methods: {
    searchReportData(id){
      window.location.href = window.location.origin + '/reports/'+id;
    },
    init() {},
  },
  data(){
    const {$truthty,data:{principal_data,employee_data}} =  this;
    const total_company = $truthty(principal_data)?principal_data.reduce((a, b) => a + (b.resume.total || 0), 0):0;
    const complete_company = $truthty(principal_data)?principal_data.reduce((a, b) => a + (b.resume.complete || 0), 0):0;
    const total_employee = $truthty(employee_data)?employee_data.reduce((a, b) => a + (b.resume.total || 0), 0):0;
    const complete_employee = $truthty(employee_data)?employee_data.reduce((a, b) => a + (b.resume.complete || 0), 0):0;
    return {
      percent_company: complete_company === 0 ? 0 : (complete_company / total_company) * 100,
      percent_employee: complete_employee === 0 ? 0 : (complete_employee / total_employee) * 100,
      chartStyle: {
        height: '200px',
        position: 'relative',
        margin: '2rem 1rem'
      },
    }
  }
};
</script>
