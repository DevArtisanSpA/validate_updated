<template>
  <form @submit.prevent="checkForm" class="pt-2 pb-5">
    <div v-if="errors.length">
      <b-alert variant="danger" show>
        <ul class="mb-0 mx-3">
          <li v-for="(error, index) in errors" v-bind:key="index">
            {{ error.message }}
          </li>
        </ul>
      </b-alert>
    </div>

    <b-modal ref="modal-confirm" hide-footer>
      <div slot="modal-title">
        <span
          >Se
          {{ !is_update ? "creará" : "modificará" }} un
          registro de empresa</span
        >
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
      <b-col>
        <strong class="float-right">
          <span class="text-danger">*</span> Campos obligatorios
        </strong>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4">
        <label for="input-rut"> <span class="text-danger">*</span> Rut </label>
        <b-form-input
          id="input-rut"
          type="text"
          v-model="formData.company.rut"
          :disabled="isDisabled() || this.$props.is_for_service"
          :state="this.states.rut"
          @change="getCompany"
        >
        </b-form-input>
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.rut
        }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="4">
        <label for="input-name">
          <span class="text-danger">*</span> Nombre Compañía
        </label>
        <b-form-input
          id="input-name"
          type="text"
          v-model="formData.company.business_name"
          :disabled="isDisabled() || disabled"
          :state="this.states.business_name"
          :formatter="(e) => formatter('business_name', e)"
        />
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.business_name
        }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="4">
        <label for="input-commercial-business">
          <span class="text-danger">*</span> Giro Comercial
        </label>
        <b-form-select
          id="input-commercial-business"
          v-model="formData.company.commercial_business_id"
          :disabled="isDisabled() || disabled"
          :state="this.states.commercial_business_id"
          :formatter="(e) => formatter('commercial_business_id', e)"
        >
          <template v-slot:first>
            <option :value="null" disabled>Selecciona una opción</option>
          </template>
          <option
            v-for="commercial_business in this.$props.dataList
              .commercialBusinesses"
            v-bind:key="commercial_business.id"
            :value="commercial_business.id"
          >
            {{ commercial_business.name }}
          </option>
        </b-form-select>
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.commercial_business_id
        }}</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <b-row>
    </b-row>
    <b-row>
      <b-col md="6">
        <label for="input-contact">
          <span class="text-danger">*</span> Contacto
        </label>
        <b-form-input
          id="input-contact"
          type="text"
          v-model="formData.company.contact_name"
          :disabled="isDisabled() || disabled"
          :state="this.states.contact_name"
          :formatter="(e) => formatter('contact_name', e)"
        />
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.contact_name
        }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="6">
        <label for="input-email">
          <span class="text-danger">*</span> Email
        </label>
        <b-form-input
          id="input-email"
          type="email"
          v-model="formData.company.contact_email"
          :disabled="isDisabled() || disabled"
          :state="this.states.contact_email"
          :formatter="(e) => formatter('contact_email', e)"
        />
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.contact_email
        }}</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <div v-if="is_update == 0">
      <b-row>
        <b-col md="6">
          <label for="input-phone1">
            <span class="text-danger">*</span> Teléfono 1
          </label>
          <b-form-input
            id="input-phone1"
            :formatter="(e) => formatter('phone1', e)"
            type="number"
            v-model="formData.branchOffice.phone1"
            :disabled="isDisabled() || disabled"
            :state="this.states.phone1"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.phone1
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="6">
          <label for="input-phone2">
            <span>Teléfono 2</span>
          </label>
          <b-form-input
            id="input-phone2"
            :formatter="format"
            type="number"
            v-model="formData.branchOffice.phone2"
            :disabled="isDisabled() || disabled"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <label for="input-region">
            <span class="text-danger">*</span> Región
          </label>
          <b-form-select
            id="input-region"
            v-model="formData.branchOffice.region_id"
            @input="loadCommunes(formData.branchOffice.region_id, !disabled)"
            :disabled="isDisabled() || disabled"
            :state="this.states.region_id"
            :formatter="(e) => formatter('region_id', e)"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="region in this.$props.dataList.regions"
              v-bind:key="region.id"
              :value="region.id"
            >
              {{ region.name }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.region_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-comuna">
            <span class="text-danger">*</span> Comuna
          </label>
          <b-form-select
            id="input-comuna"
            v-model="formData.branchOffice.commune_id"
            :disabled="isDisabled() || disabled"
            :state="this.states.commune_id"
            :formatter="(e) => formatter('commune_id', e)"
          >
            <template v-slot:first>
              <option :value="null" disabled>Selecciona una opción</option>
            </template>
            <option
              v-for="commune in communes"
              v-bind:key="commune.id"
              :value="commune.id"
            >
              {{ commune.name.toCamelCase() }}
            </option>
          </b-form-select>
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.commune_id
          }}</b-form-invalid-feedback>
        </b-col>
        <b-col md="4">
          <label for="input-address">
            <span class="text-danger">*</span> Dirección
          </label>
          <b-form-input
            id="input-address"
            type="text"
            v-model="formData.branchOffice.address"
            :disabled="isDisabled() || disabled"
            :state="this.states.address"
            :formatter="(e) => formatter('address', e)"
          />
          <b-form-invalid-feedback id="input-live-feedback">{{
            this.message.address
          }}</b-form-invalid-feedback>
        </b-col>
      </b-row>
    </div>
    <b-row>
      <b-col md="6">
        <label for="input-affiliation">
          <span class="text-danger">*</span> Afiliación
        </label>
        <b-form-radio-group
          id="input-affiliation"
          v-model="formData.company.affiliation"
          :disabled="isDisabled() || disabled"
          :state="this.states.affiliation"
          :formatter="(e) => formatter('affiliation', e)"
        >
          <b-form-radio
            v-for="affiliation in this.$props.dataList.affiliations"
            v-bind:key="affiliation"
            :value="affiliation"
            >{{ affiliation }}</b-form-radio
          >
        </b-form-radio-group>
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.affiliation
        }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="6">
        <label for="input-affiliation-date">
          <span class="text-danger">*</span> Fecha de Afiliación
        </label>
        <b-form-input
          id="input-affiliation-date"
          type="date"
          v-model="formData.company.affiliation_date"
          :disabled="isDisabled() || disabled"
          :state="this.states.affiliation_date"
          :formatter="(e) => formatter('affiliation_date', e)"
        />
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.message.affiliation_date
        }}</b-form-invalid-feedback>
      </b-col>
    </b-row>
    <b-row
      v-if="
        this.$props.auth.isAdmin ||
        (is_update && this.$props.auth.company_id == this.$props.company.id) ||
        !is_update
      "
    >
      <b-button class="m-3" type="submit" variant="success">{{
        !is_update ? "Crear" : "Actualizar"
      }}</b-button>
    </b-row>
  </form>
</template>

<script>
import * as utils from "../../utils";

const labels = {
  rut: "Rut empresa",
  business_name: "Nombre empresa",
  commercial_business_id: "Giro comercial",
  commune_id: "Comuna",
  region_id: "Región",
  address: "Dirección",
  phone1: "Teléfono",
  contact_name: "Nombre de contacto",
  contact_email: "Email de contacto",
  affiliation: "Afiliación",
  affiliation_date: "Fecha de afiliación",
};

export default {
  props: ["company", "dataList", "is_update", "auth", "rut", "is_for_service"],
  data() {
    const {
      company,
      rut
    } = this.$props;
    const truthty = this.$truthty;
    return {
      send: false,
      error: 0,
      errors: [],
      communes: [],
      disabled: false,
      business_name: "",
      formData: {
        company: {
          id: !truthty(company) ? null : company.id,
          rut: !truthty(company) ? truthty(rut) ? rut : null : company.rut,
          business_name: !truthty(company) ? null : company.business_name,
          commercial_business_id: !truthty(company)
            ? null
            : company.commercial_business_id,
          contact_name: !truthty(company) ? null : company.contact_name,
          contact_email: !truthty(company) ? null : company.contact_email,
          affiliation: !truthty(company) ? null : company.affiliation,
          affiliation_date: !truthty(company) ? null : company.affiliation_date,
        },
        branchOffice: {
          region_id: null,
          commune_id: null,
          address: null,
          phone1: null,
          phone2: null
        }
      },
      states: {
        rut: null,
        business_name: null,
        commercial_business_id: null,
        commune_id: null,
        region_id: null,
        address: null,
        phone1: null,
        phone2: null,
        contact_name: null,
        contact_email: null,
        affiliation: null,
        affiliation_date: null,
      },
      message: {
        rut: "",
        business_name: "",
        commercial_business_id: "",
        commune_id: "",
        region_id: "",
        address: "",
        phone1: "",
        phone2: "",
        contact_name: "",
        contact_email: "",
        affiliation: "",
        affiliation_date: "",
      },
    };
  },
  methods: {
    checkForm() {
      this.error = 0;
      this.errors = [];
      if (!this.disabled) {
        if (this.errorRutDuplicate == 1) {
          this.error = 1;
          this.states.rut = false;
          this.message.rut = "El RUT corresponde a otra empresa.";
        }
        if (!this.$truthty(this.formData.company.rut)) {
          this.error = 1;
          this.states.rut = false;
          this.message.rut = "RUT de compañía requerida.";
        }
        if (!this.$truthty(this.formData.company.business_name)) {
          this.error = 1;
          this.states.business_name = false;
          this.message.business_name = "Nombre de compañía requerida.";
        }
        if (!this.$truthty(this.formData.company.commercial_business_id)) {
          this.error = 1;
          this.states.commercial_business_id = false;
          this.message.commercial_business_id = "Giro de compañía requerido.";
        }
        if (!this.$props.is_update) {
          if (!this.$truthty(this.formData.branchOffice.region_id)) {
            this.error = 1;
            this.states.region_id = false;
            this.message.region_id = "Región requerido.";
          }
          if (!this.$truthty(this.formData.branchOffice.commune_id)) {
            this.error = 1;
            this.states.commune_id = false;
            this.message.commune_id = "Comuna requerida.";
          }
          if (!this.$truthty(this.formData.branchOffice.address)) {
            this.error = 1;
            this.states.address = false;
            this.message.address = "Dirección requerida.";
          }
          if (!this.$truthty(this.formData.branchOffice.phone1)) {
            this.error = 1;
            this.states.phone1 = false;
            this.message.phone1 =
              "Se debe informar al menos un teléfono de contacto.";
          }
        }
        if (!this.$truthty(this.formData.company.contact_name)) {
          this.error = 1;
          this.states.contact_name = false;
          this.message.contact_name = "Nombre de contacto requerido.";
        }
        if (!this.$truthty(this.formData.company.contact_email)) {
          this.error = 1;
          this.states.contact_email = false;
          this.message.contact_email = "Email de contacto requerido.";
        }
        if (!this.$truthty(this.formData.company.affiliation)) {
          this.error = 1;
          this.states.affiliation = false;
          this.message.affiliation = "Afiliación requerida.";
        }
        if (!this.$truthty(this.formData.company.affiliation_date)) {
          this.error = 1;
          this.states.affiliation_date = false;
          this.message.affiliation_date = "Fecha de afiliación requerida.";
        }
        if (this.errors.length > 0) {
          this.error = 1;
        }
      }
      if (this.error === 0) {
        this.$refs["modal-confirm"].show();
      } else {
        return false;
      }
    },
    loadCommunes($region_id, reset = true) {
      console.log($region_id)
      if (this.$truthty($region_id)) {
        let regions = this.$props.dataList.regions;
        let communesLocal = regions.find((region) => {
          return region.id == $region_id;
        }).communes;
        this.communes = communesLocal;
      }
      if (reset) {
        this.formData.branchOffice.commune_id = null;
      }
    },
    formatter(label, value) {
      if (label === "phone1" || label === "phone2") {
        if (label === "phone1" && String(value).length < 6) {
          this.states[label] = false;
          this.message[label] = labels[label] + " debe ser informado.";
        } else {
          this.states[label] = true;
        }
        return String(value).substring(0, 10);
      }
      else {
        if (value.length < 3) {
          this.states[label] = false;
          this.message[label] = labels[label] + " debe ser informado.";
        } else {
          this.states[label] = true;
        }
        return value;
      }
    },
    errorAPI(err) {
      this.send = false;
      this.$refs["modal-confirm"].hide();
      this.errors = [];
      this.error = 1;
    },
    submit() {
      console.log(this.formData);
      this.send = true;
      if (this.$props.is_update) {
        const {
          formData: {
            company
          }
        } = this
        const url = `${window.location.origin}/companies/${this.formData.company.id}`;
        axios
          .patch(url, company)
          .then((res) => {
            if (res.status === 200) {
              window.location.href = window.location.origin + "/companies";
            } else {
              this.errorAPI(err);
              res.data.forEach((row) => this.errors.push({ message: row }));
            }
          })
          .catch((err) => {
            this.errorAPI(err);
            this.errors.push({ message: err.response.data.message });
          });
      } else {
        const url = `${window.location.origin}/companies`;
        axios
          .post(url, this.formData)
          .then((res) => {
            if (res.status === 200) {
              if (this.$truthty(res.data.company)) {
                const {
                  data: {
                    company: {
                      id,
                      rut
                    }
                  }
                } = res
                if (this.$props.is_for_service) {
                  const companyData = {
                    id: id,
                    rut: rut
                  }
                  this.$emit("setCompanyData", companyData)
                  this.$emit("setStage", 3)
                }
                else {
                  window.location.href = window.location.origin + "/companies";
                }
              }
            } else {
              this.errorAPI(err);
              res.data.forEach((row) => this.errors.push({ message: row }));
            }
          })
          .catch((err) => {
            this.errorAPI(err);
            this.errors.push({ message: err.response.data.message });
          });
      }
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    isDisabled() {
      if (this.$props.auth.isAdmin) {
        return false;
      }
      if (this.$truthty(this.$props.company)) {
        if (this.$props.company.id === this.$props.auth.company_id) {
          return false;
        } else {
          return true;
        }
      }
      return false;
    },
    getCompany(value) {
      if (value.length >= 8) {
        axios
          .get(`${window.location.origin}/companies/rut/${value}`)
          .then((response) => {
            if(response.status==200){
              const { company } = response.data;
              if (this.$truthty(company)) {
                //document.getElementById("input-rut").focus();
                if (!(this.is_update && company.id == this.$props.company.id)) {
                  this.error = 1;
                  this.errorRutDuplicate = 1;
                  this.states.rut = false;
                  this.message.rut = "El RUT corresponde a otra empresa.";
                  //rut ya existe, no puede ser utilizado.
                }
              }
            }
            else {
              this.error = 0;
              this.errorRutDuplicate = 0;
              this.states.rut = true;
              this.message.rut = "";
            }
          });
      } else {
        this.error = 0;
        this.errorRutDuplicate = 0;
        this.states.rut = true;
        this.message.rut = "";
      }
    },
    init() {
      if (this.$props.company !== undefined) {
        let region_id = this.$props.company.commune.region_id;
        this.loadCommunes(region_id);
        this.formData.commune_id = this.$props.company.commune_id;
      }
    },
    format(e) {
      return String(e).substring(0, 10);
    },
  },
  mounted() {
    console.log(this.$props);
    if (this.$props.setStage) {
      alert("Aaaaa")
    }
    this.init();
  },
};
</script>
