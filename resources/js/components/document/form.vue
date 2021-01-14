<template>
  <div>
    <b-row>
      <b-col>
        <strong class="float-right">
          <span class="text-danger">*</span> Campos obligatorios
        </strong>
      </b-col>
    </b-row>
    <form @submit.prevent="checkForm">
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title">
          ¿Estás seguro de realizar las siguientes acciones?
        </div>

        <div class="d-block text-justify" style="margin-bottom: 20px">
          <li v-for="(text, index) in texts" v-bind:key="index">{{ text }}</li>
        </div>

        <div v-if="!send" class="float-right">
          <b-button @click="hideModal">Cancelar</b-button>
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
        <div
          v-else
          class="d-flex justify-content-center"
          style="font-size: 36px"
        >
          <i class="el-icon-loading"></i>
        </div>
      </b-modal>
      <div id="errors-div">
        <b-alert variant="warning" show v-if="errors.length" id="errors-div">
          <ul class="mb-0 mx-3">
            <li v-for="(error, index) in errors" v-bind:key="index">
              {{ error.message }}
            </li>
          </ul>
        </b-alert>
      </div>
      <b-row>
        <b-col sm="6">
          <label for="input-add"> Documentos </label>
          <b-form-select id="input-add" v-model="document_type">
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="(document_type, index) in this.document_types"
              v-bind:key="document_type.name + index"
              :value="document_type"
            >
              {{ document_type.name }}
            </option>
          </b-form-select>
        </b-col>

        <b-col class="d-flex align-items-end">
          <b-button variant="primary" v-on:click="addDoc($event, document_type)"
            >Agregar</b-button
          >
        </b-col>
      </b-row>
      <div
        v-for="(document, index) in formData.documents"
        v-bind:key="document.id + document.name + index"
      >
        <b-row>
          <b-col class="d-flex align-items-end" md="12">
            <label>
              -
              <strong>{{ document.name }}</strong>
            </label>
          </b-col>
        </b-row>
        <div v-if="!$props.monthly">
          <b-row>
            <b-col md="4">
              <label :for="'input-ini' + index">
                <span class="text-danger">*</span> Fecha del Documento
              </label>
              <b-input
                :id="'input-ini' + index"
                type="date"
                v-model="formData.documents[index].start"
                :max="max"
                :formatter="(e) => formatter('date_init', index, e)"
                :state="formData.states[index].date_init"
              />
              <b-form-invalid-feedback id="input-live-feedback">{{
                formData.message[index].date_init
              }}</b-form-invalid-feedback>
            </b-col>
            <b-col class="d-flex align-items-end" offset-md="1" md="5">
              <document-input
                :icon="iconState(formData.documents[index].validation_state_id)"
                :color="
                  colorState(formData.documents[index].validation_state_id)
                "
                :fileExt="formData.documents[index]"
                @input="inputFile(index, ...arguments)"
                :state="formData.states[index].file"
              />
            </b-col>
            <b-col class="d-flex align-items-center" md="1" offset-md="1">
              <el-button
                v-if="validDelete(document)"
                type="danger"
                icon="el-icon-close"
                title="Quitar"
                v-on:click="deleteDoc(index)"
                circle
                plain
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col md="4">
              <label :for="'input-end' + index">Fecha de Vigencia</label>
              <b-input
                :id="'input-end' + index"
                type="date"
                v-model="formData.documents[index].finished"
                :min="formData.documents[index].start"
              />
            </b-col>
            <b-col md="5" offset-md="1">
              <div class="input-obs-rest">
                <div v-if="$truthty(formData.documents[index].observations)">
                  <div
                    :disabled="true"
                    v-for="(observation, i) in formData.documents[
                      index
                    ].observations.split('</div>')"
                    v-bind:key="index + '-' + i"
                  >
                    <small>{{ observation.split("\\n")[0] }} </small>
                    <p class="mb-2">{{ observation.split("\\n")[1] }}</p>
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </div>
        <div v-else>
          <b-row>
            <b-col class="d-flex align-items-end" md="5">
              <document-input
                :icon="iconState(formData.documents[index].validation_state_id)"
                :color="
                  colorState(formData.documents[index].validation_state_id)
                "
                :fileExt="formData.documents[index]"
                @input="inputFile(index, ...arguments)"
                :state="formData.states[index].file"
              />
            </b-col>
            <b-col md="5" offset-md="1">
              <div class="input-obs-rest">
                <div v-if="$truthty(formData.documents[index].observations)">
                  <div
                    :disabled="true"
                    v-for="(observation, i) in formData.documents[
                      index
                    ].observations.split('</div>')"
                    v-bind:key="index + '-' + i"
                  >
                    <small>{{ observation.split("\\n")[0] }} </small>
                    <p class="mb-2">{{ observation.split("\\n")[1] }}</p>
                  </div>
                </div>
              </div>
            </b-col>
            <b-col class="d-flex align-items-center" md="1">
              <el-button
                v-if="validDelete(document)"
                type="danger"
                icon="el-icon-close"
                title="Quitar"
                v-on:click="deleteDoc(index)"
                circle
                plain
              /> </b-col
          ></b-row>
        </div>
      </div>
      <div>
        <b-button
          class="my-4"
          @click.prevent="discard"
          variant="outline-secondary"
          :disabled="
            !(
              $truthty(formData.documents) ||
              JSON.stringify(prevDocument) !==
                JSON.stringify(formData.documents)
            )
          "
          >Descartar</b-button
        >
        <b-button
          class="my-4"
          type="submit"
          variant="success"
          :disabled="
            !(
              $truthty(formData.documents) ||
              JSON.stringify(prevDocument) !==
                JSON.stringify(formData.documents)
            )
          "
          >Guardar</b-button
        >
      </div>
    </form>
  </div>
</template>

<script>
import moment from "moment";
const copy = (x) => {
  if (x != null && x != undefined) return JSON.parse(JSON.stringify(x));
  return x;
};
export default {
  props: [
    "documents",
    "document_types",
    "auth",
    "service",
    "employee",
    "monthly",
  ],
  data() {
    return {
      send: false,
      texts: [],
      document_type: null,
      error: 0,
      errors: [],
      formData: {
        documents: [],
        states: [],
        message: [],
      },
      prevDocument: [],
      idsDelete: [],
      idsIgnore: [],
      max: moment().format("YYYY-MM-DD"),
      maxM: moment().format("YYYY-MM"),
    };
  },
  methods: {
    addDoc($event, document_type) {
      $event.preventDefault();
      let document = {
        // id,
        document_type_id: document_type.id,
        validation_state_id: 2,
        service_id: this.service.id,
        employee_id: this.$truthty(this.employee) ? this.employee.id : null,
        start: null,
        finish: null,
        month_year_registry: null,
        path_data: null,
        file: null,
        name: document_type.name,
        observations: "",
      };
      this.formData.documents.splice(0, 0, document);
      this.formData.states.splice(0, 0, {
        date_init: null,
        file: null,
        newfile: true,
      });
      this.formData.message.splice(0, 0, { date_init: null, file: null });
      this.document_type = null;
    },
    deleteDoc(index) {
      this.formData.documents.splice(index, 1);
      this.formData.states.splice(index, 1);
      this.formData.message.splice(index, 1);
    },
    deleteFile(index) {
      this.formData.documents[index].path_data = null;
    },
    validDelete(document) {
      const elements=this.documents.filter(x=>{
        return [4,5,6].includes(x.document_type_id);
      })
      if(elements.length>1 || elements.length==0){
        return true;
      }
      return false;
    },
    inputFile(index, file) {
      if (file == null) {
        this.formData.documents[index].path_data = null;
        this.formData.documents[index].validation_state_id = 1;
        this.formData.states[index].file = false;
        // this.formData.states[index].newFile=false
        this.formData.message[index].file = "El archivo es requerido";
      } else {
        this.formData.documents[index].validation_state_id = 2;
        this.formData.states[index].file = null;
        this.formData.states[index].newfile = true;
        this.formData.message[index].file = null;
      }
      this.formData.documents[index].file = file;
    },
    colorState(state) {
      if (state == 2) return "warning";
      if (state == 3) return "success";
      if (state == 4) return "danger";
      return "";
    },
    iconState(state) {
      if (state == 2) return "el-icon-remove";
      if (state == 3) return "el-icon-success";
      if (state == 4) return "el-icon-error";
      return "";
    },
    observationSplit(obs, separ, index) {
      return obs.split(separ)[index];
    },
    checkForm() {
      this.error = 0;
      this.errors = [];
      if (
        !this.$truthty(this.prevDocument) &&
        !this.$truthty(this.formData.documents)
      ) {
        this.errors.push({
          message: `No existen documento que crear`,
        });
        this.error = 1;
        return false;
      }
      console.log(this.formData.documents);
      this.formData.documents.map((document, index) => {
        console.log(document);
        // if (this.filter === 0) {
        if (!this.$truthty(document.start)) {
          this.formData.states[index].date_init = false;
          this.formData.message[index].date_init =
            "Fecha del documento es requerido";
          this.error = 1;
        } else {
          if (
            moment(moment().format("YYYY-MM-DD")).diff(
              document.start,
              "days",
              true
            ) < 0
          ) {
            this.errors.push({
              message: `La fecha del documento ${document.name} (${
                index + 1
              }°) no puede ser mayor a hoy`,
            });
            this.error = 1;
          }
        }
        if (this.$truthty(document.finished) && this.$truthty(document.start)) {
          if (
            moment(document.start).diff(document.finished, "days", true) > 0
          ) {
            this.errors.push({
              message: `La fecha finalización del documento ${document.name} (${
                index + 1
              }°) no puede ser menor a la del documento`,
            });
            this.error = 1;
          }
        }

        // if (this.filter === 1) {
        //   if (!this.$truthty(document.month_year_registry)) {
        //     this.formData.states[index].date_init = false;
        //     this.formData.message[index].date_init =
        //       "Fecha del documento es requerido";
        //     this.error = 1;
        //   }
        // }
        if (document.id == null) {
          if (document.file == null) {
            this.formData.states[index].file = false;
            this.formData.message[index].file = "El archivo es requerido";
            this.error = 1;
          }
        }
      });
      let comparison = this.compare();
      if (!comparison) {
        this.errors.push({
          message: `No existen cambios`,
        });
        this.error = 1;
      }
      if (this.error === 0) {
        this.$refs["modal-confirm"].show();
      } else {
        const element = document.getElementById("errors-div");
        element.scrollIntoView({ behavior: "smooth" });
        return false;
      }
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    compare() {
      let texts = [];
      let idsDelete = [];
      let idsIgnore = [];
      let documents = copy(this.formData.documents);
      this.prevDocument.map((document) => {
        let indexNew = documents.findIndex((doc) => {
          return doc.id == document.id;
        });
        if (indexNew !== -1) {
          let newD = JSON.stringify(documents[indexNew]);
          let oldD = JSON.stringify(document);
          if (oldD != newD) {
            texts.push(`Actualizar documento ${document.name}`);
          } else {
            idsIgnore.push(document.id);
          }
          documents.splice(indexNew, 1);
        } else {
          idsDelete.push(document.id);
          texts.push(`Eliminar documento ${document.name} (antiguo)`);
        }
      });
      documents.map((document) => {
        texts.push(`Agregar documento ${document.name}`);
      });
      this.texts = texts;
      this.idsIgnore = idsIgnore;
      this.idsDelete = idsDelete;
      if (texts.length > 0) {
        return true;
      }
      return false;
    },
    formatter(label, index, value) {
      if (value.length < 3) {
        this.formData.states[index][label] = false;
        this.formData.message[index][label] =
          "Fecha del Documento debe ser informado.";
      } else {
        this.formData.states[index][label] = true;
      }
      return value;
    },
    submit() {
      let url = window.location.origin + "/documents";
      let config = { headers: { "Content-Type": "multipart/form-data" } };
      let promises = [];
      let formData = new FormData();
      this.formData.documents.map((document, index) => {
        if (!this.idsIgnore.includes(document.id)) {
          formData.append("data" + (index + 1), JSON.stringify(document));
          formData.append("file" + (index + 1), document.file);
        }
      });
      console.log("in");
      promises.push(axios.post(url, formData, config));
      console.log(this.idsDelete);
      promises.push(axios.post(url+'/delete',{ids:this.idsDelete}));
      this.send = true;
      console.log(url);
      Promise.all(promises)
        .then((values) => {
          // window.history.back()
          this.send = false;
          this.$refs["modal-confirm"].hide();
        })
        .catch((err) => {
          this.send = false;
          this.$refs["modal-confirm"].hide();
          this.errors = [];
          this.errors.push({ message: err.response.data.message });
          this.error = 1;
        });
    },
    init() {
      this.documents.map((document) => {
        document.name = document.type.name;
        this.formData.documents.push(document);
        this.formData.states.push({
          date_init: null,
          file: null,
          newfile: false,
        });
        this.formData.message.push({ date_init: null, file: null });
      });
      this.prevDocument = copy(this.formData.documents);
    },
  },
  mounted() {
    this.init();
    console.log(this.$props);
  },
};
</script>