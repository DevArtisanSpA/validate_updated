<template>
  <div class="col-md-9">
    <div class="d-flex align-items-center justify-content-center flex-column">
        <label class="h3 mr-2">Certificado empresa</label>        
        <b-form-select 
          v-model="selected" 
          class="w-25"
          v-on:change="search($event)"
        >
          <!-- This slot appears above the options from 'options' prop -->
          <template v-slot:first>
            <b-form-select-option :value="null">Seleccionar una empresa</b-form-select-option>
          </template>

          <b-form-select-option
            v-for="company in data.companies"
            v-bind:key="company.id"
            :value="company.id"
          >{{ company.business_name.toUpperCase() }}</b-form-select-option>
        </b-form-select>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data"],
  data() {
    return {
      error: 0,
      errors: [],
      selected:null,
      formdata: {
       
      },
    };
  },
  methods: {
    init() {
    },
  },
  mounted() {
    this.init();
  },
};
</script>