<template>
  <div>
    <hr />
    <div v-if="!$truthty(employee)">
      <b-row>
        <b-col md="6">
          <p>
            <strong>Principal:</strong>
            {{ service.branch_office.company.business_name }}
          </p>
        </b-col>
        <b-col md="3">
          <p><strong>Sucursal:</strong> {{ service.branch_office.name }}</p>
        </b-col>
        <b-col md="3" v-if="$truthty(service.month_year_registry)">
          <p><strong>Periodo:</strong> {{ service.month_year_registry }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="6">
          <p>
            <strong>Contratista:</strong> {{ service.company.business_name }}
          </p>
        </b-col>
        <b-col md="3">
          <p><strong>Rut:</strong> {{ service.company.rut }}</p>
        </b-col>
        <b-col md="3">
          <p>
            <strong>Tipo:</strong>
            {{ service.company.commercial_business.name }}
          </p>
        </b-col>
      </b-row>
    </div>
    <div v-else>
      <b-row>
        <b-col>
          <p>
            <strong>Principal:</strong>
            {{ service.branch_office.company.business_name }}
          </p>
        </b-col>
        <b-col>
          <p>
            <strong>Sucursal:</strong> {{ service.branch_office.name }}
          </p> </b-col
        ><b-col>
          <p>
            <strong>Contratista:</strong> {{ service.company.business_name }}
          </p>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <p>
            <strong>Apellidos:</strong> {{ employee.surname }}
            {{ employee.second_surname }}
          </p>
        </b-col>
        <b-col>
          <p><strong>Nombres:</strong> {{ employee.name }}</p>
        </b-col>
        <b-col>
          <p>
            <strong>N° identificación:</strong> {{ employee.identification_id }}
          </p>
        </b-col>
      </b-row>
      <b-row v-if="$truthty(service.month_year_registry)">
        <b-col>
          <p><strong>Periodo:</strong> {{ service.month_year_registry }}</p>
        </b-col>
      </b-row>
    </div>
    <hr class="my-0" />
    <b-modal
      ref="modal-confirm"
      hide-footer
      :no-close-on-backdrop="true"
      :no-close-on-esc="true"
      :hide-header-close="true"
    >
      <div slot="modal-title">¿Estás seguro de guardar?</div>
      <div class="d-block text-justify" style="margin-bottom: 20px"></div>
      <div v-if="!send" class="float-right">
        <b-button @click="hideModal" variant="outline-secondary"
          >Cancelar</b-button
        >
        <b-button variant="primary" @click="submit">Aceptar</b-button>
      </div>
      <div v-else class="d-flex justify-content-center" style="font-size: 36px">
        <i class="el-icon-loading"></i>
      </div>
    </b-modal>
    <form id="agregar" @submit.prevent="checkForm">
      <el-table :data="tableData" :show-header="false">
        <el-table-column class="pb-1 mb-1">
          <template slot-scope="props">
            <b-row class="pb-1 mb-1" style="font-size: 16px">
              <b-col md="4">
                <label :for="'input-' + props.row.id">{{
                  props.row.type.name
                }}</label>
              </b-col>
              <b-col md="2">
                <label>
                  <a
                    :href="`/documents/download/${props.row.id}`"
                    target="_blank"
                    >Ver documento</a
                  >
                </label>
              </b-col>
              <b-col md="5" offset-md="1" v-if="auth.user_type_id == 1">
                <el-radio-group
                  v-model="props.row.validation_state_id"
                  :id="'input-' + props.row.id"
                >
                  <el-radio :label="2">Pendiente</el-radio>
                  <el-radio :label="4">Rechazado</el-radio>
                  <el-radio :label="3">Aprobado</el-radio>
                </el-radio-group>
              </b-col>
              <b-col sm="12" v-if="props.row.validation_state_id == 4">
                <div v-if="auth.user_type_id != 1">
                  <div
                    v-if="$truthty(props.row.observations)"
                    class="input-obs-rest"
                    style="height: 4.5rem !important"
                  >
                    <div
                      :disabled="true"
                      v-for="(observation, i) in props.row.observations.split(
                        '</div>'
                      )"
                      v-bind:key="index + '-' + i"
                    >
                      <small>{{ observation.split("\\n")[0] }} </small>
                      <p class="mb-2">{{ observation.split("\\n")[1] }}</p>
                    </div>
                  </div>
                  <b-form-textarea
                    v-else
                    type="text"
                    placeholder="Observaciones"
                    rows="2"
                    size="sm"
                    no-resize
                    :disabled="true"
                  />
                </div>
                <b-form-textarea
                  v-else
                  id="input-obs"
                  type="text"
                  placeholder="Observaciones"
                  rows="2"
                  size="sm"
                  no-resize
                  :disabled="auth.user_type_id == 1 ? false : true"
                  v-model="observations[props.$index]"
                />
              </b-col>
            </b-row>
          </template>
        </el-table-column>
      </el-table>
      <b-row>
        <b-col class="d-flex justify-content-end">
          <b-button
            v-if="auth.user_type_id == 1"
            class="my-4"
            @click.prevent="discard"
            variant="outline-secondary"
            >Descartar</b-button
          >
          <b-button
            v-if="auth.user_type_id == 1"
            class="my-4"
            type="submit"
            variant="success"
            >Guardar</b-button
          >
        </b-col></b-row
      >
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
  props: ["documents", "auth", "service", "employee", "monthly"],
  data() {
    return {
      company: "",
      tableData: copy(this.documents),
      documentsPrev: copy(this.documents),
      observations: new Array(this.documents.length),
      send: false,
    };
  },
  methods: {
    checkForm() {
      this.$refs["modal-confirm"].show();
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    discard() {
      this.documents = copy(this.documentsPrev);
      this.observations = new Array(this.$props.data.length);
    },
    submit() {
      let url = `${window.location.origin}/documents/update`;
      let promises = [];
      this.send = true;
      let urlBack = window.location.origin + "/review/";
      urlBack = this.$truthty(this.employee)
        ? urlBack + "employees/"
        : (urlBack = urlBack + "companies/");
      urlBack = this.$truthty(this.monthly)
        ? urlBack + "monthly"
        : (urlBack = urlBack + "base");
      axios
        .post(url, {
          documents: this.tableData,
          observations: this.observations,
        })
        .then((response) => {
          window.location.href = urlBack;
          // this.send = false;
          // this.hideModal();
        })
        .catch((error) => {
          this.send = false;
          this.hideModal();
        });
    },
  },
  mounted() {
    console.log(this.$props);
  },
};
</script>