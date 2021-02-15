<template>
  <div class="col-md-9" id="Father">
    <div class="d-flex align-items-center justify-content-center flex-column pb-2">
      <label class="h3 mr-2">{{ data.text }}</label>
      <b-form-select v-model="selected" class="w-25" @change="search($event)">
        <!-- This slot appears above the options from 'options' prop -->
        <template v-slot:first>
          <b-form-select-option :value="null"
            >Seleccionar una empresa</b-form-select-option
          >
        </template>

        <b-form-select-option
          v-for="service in data.services"
          v-bind:key="service.id"
          :value="service.id"
          >{{ service.description.toUpperCase() }}</b-form-select-option
        >
      </b-form-select>
    </div>
    <div v-if="selected && $truthty(formdata)">
      <report-fixed
        v-if="data.componentName == 'report-fixed'"
        :data="formdata"
      />
      <report-eventual
        v-if="data.componentName == 'report-eventual'"
        :data="formdata"
      />
      <certificate-fixed
        v-if="data.componentName == 'certificate-fixed'"
        :data="formdata"
      />
      <certificate-eventual
        v-if="data.componentName == 'certificate-eventual'"
        :data="formdata"
      />
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
      selected: null,
      formdata: {},
    };
  },
  methods: {
    init() {
      // if(this.$truthty(this.data.selected)){
      // let e = document.getElementById("Father");
      // let pdfpreview = document.createElement(this.data.componentName);
      // pdfpreview.setAttribute(":data", {});
      // e.appendChild(pdfpreview);
      // console.log(e);
      // }
    },
    search(value) {
      // window.location.href = window.location.origin + this.data.url + value;
      console.log(window.location.origin + this.data.url + value);
      axios
        .get(window.location.origin + this.data.url + value)
        .then((response) => {
          this.formdata = response.data;
        });
    },
  },
  mounted() {
    this.init();
  },
};
</script>