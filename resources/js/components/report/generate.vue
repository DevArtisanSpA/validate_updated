<template>
  <div class="col-md-9" id="Father">
    <div
      class="d-flex align-items-center justify-content-center flex-column pb-2"
    >
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
    <div v-if="selected && existDocument">
      <b-row class="mt-2">
        <b-col md="4" offset-md="1"
          ><b-card no-body class="overflow-hidden">
            <b-row no-gutters class="m-0">
              <b-col md="3"
                ><i class="el-icon-document p-2" style="font-size: 2em"></i>
              </b-col>
              <b-col md="9">
                <div class="d-flex align-items-center h-100 text-center">
                  <b-card-text>
                    <a
                      :href="`/documents/download/${document.id}`"
                      target="_blank"
                      >{{ data.name }}
                      <!-- <small style="color: #212529">ultimo</small> -->
                    </a>
                  </b-card-text>
                </div>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
        <b-col offset-md="2" md="4">
          <b-button
            variant="info"
            class="h-100"
            style="border-radius: 0.25rem"
            @click="newDocument(selected)"
          >
            <div v-if="!loading">Nuevo</div>
            <i v-else class="el-icon-loading" style="font-size:1.5em;"> </i>
          </b-button>
        </b-col>
      </b-row>
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
import moment from "moment";
export default {
  props: ["data"],
  data() {
    return {
      error: 0,
      errors: [],
      selected: null,
      formdata: {},
      existDocument: false,
      loading: false,
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
    search(service_id) {
      if (!this.$truthty(service_id)) return;
      this.formdata = {};
      let finish = moment().clone().endOf("month").format("YYYY-MM-DD");
      let month_year_registry = moment().format("YYYY-MM");
      axios
        .post(window.location.origin + "/pdf/find", {
          finish,
          month_year_registry,
          name: this.data.name,
          service_id,
        })
        .then((response) => {
          console.log(response.data);
          if (!this.$truthty(response.data.document)) {
            this.newDocument(service_id);
          } else {
            this.document = response.data.document;
            this.existDocument = true;
          }
        });
    },
    newDocument(value) {
      if (this.loading) return;
      this.loading = true;
      axios
        .get(window.location.origin + this.data.url + value)
        .then((response) => {
          this.existDocument = false;
          this.formdata = response.data;
          this.loading = false;
        });
    },
  },
  mounted() {
    this.init();
  },
};
</script>