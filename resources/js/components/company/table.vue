<template>
  <div>
    <div v-if="errors.length">
      <b-alert variant="danger" show>
        <ul class="mb-0 mx-3">
          <li v-for="(error, index) in errors" v-bind:key="index">
            {{ error }}
          </li>
        </ul>
      </b-alert>
    </div>
    <el-input
      v-model="search"
      placeholder="Buscar empresa"
      class="input-search"
      clearable
      @input="changeInput($event)"
    />
    <el-table
      stripe
      height="600"
      class="w-100"
      :data="
        this.tableData.filter((data) => {
          return (
            !this.$truthty(this.search) ||
            (this.$truthty(this.search) &&
              ((this.$truthty(data.business_name) &&
                data.business_name
                  .toLowerCase()
                  .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.contact_email) &&
                  data.contact_email
                    .toLowerCase()
                    .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.contact_email) &&
                  data.rut.toLowerCase().includes(this.search.toLowerCase())) ||
                // data.parent_name.toLowerCase().includes(this.search.toLowerCase()) ||
                (this.$truthty(data.parentCompanies) &&
                  this.$truthty(
                    data.parentCompanies.find((parentCompanyName) => {
                      return parentCompanyName
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                    })
                  ))))
          );
        })
      "
    >
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title">
          <h5>IMPORTANTE</h5>
        </div>

        <div class="d-block text-center mt-2 mb-4">
          <span>¿Estás seguro de eliminar a esta empresa?</span>
        </div>

        <div class="float-right">
          <b-button @click.prevent="hideModal" variant="outline-secondary"
            >Cancelar</b-button
          >
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
      </b-modal>
      <el-table-column type="expand">
        <template slot-scope="props">
          <b-row class="mb-0">
            <b-col md="4">
              <p>
                <b>Empleados:</b>
                0
              </p>
            </b-col>
            <b-col md="4">
              <p>
                <b>Giro:</b>
                {{ props.row.commercial_business.name }}
              </p>
            </b-col>
          </b-row>
        </template>
      </el-table-column>
      <el-table-column
        prop="rut"
        label="Rut"
        width="100"
        sortable
        :sort-method="sortTernario('rut')"
      />
      <el-table-column
        prop="business_name"
        label="Razón social"
        sortable
        :sort-method="sortSecundario('business_name')"
        :filters="valuesFilter(tableData, 'business_name')"
        :filter-method="filter('business_name')"
      />
      <el-table-column
        prop="contact_name"
        label="Nombre de contacto"
        sortable
        :sort-method="sortTernario('rut')"
      />
      <el-table-column
        prop="contact_email"
        label="Email de Contacto"
        sortable
        :sort-method="sortTernario('rut')"
      />
      <el-table-column
        label="Principal"
        :filters="valuesFilterPrincipal(tableData)"
        :filter-method="filterPrincipal"
      >
        <!-- <el-table-column
        label="Principal"
        prop="name_parent"
        sortable
        :filters="valuesFilter(tableData, 'name_parent')"
        :filter-method="filter('name_parent')"
      > -->
        <template slot-scope="props">
          <div v-if="auth.user_type_id == 1 || auth.company_id == props.row.id">
            <div
              v-for="(company,index) in props.row.parentCompanies"
              v-bind:key="index"
            >
              <p class="mb-1" v-bind:style="{ color: '#606266' }">
                {{ company }}
              </p>
            </div>
          </div>
          <p v-else class="mb-1" v-bind:style="{ color: '#606266' }">
            {{ $truthty(auth.company) ? auth.company.business_name : "" }}
          </p>
        </template>
      </el-table-column>

      <el-table-column label="Acciones" width="120">
        <template slot-scope="props">
          <el-button
            v-if="auth.user_type_id != 1 && auth.company_id !== props.row.id"
            v-on:click="show(props.row.id, tableData)"
            type="warning"
            icon="el-icon-view"
            v-b-tooltip.hover
            title="Ver detalle"
            circle
          ></el-button>
          <el-button
            v-if="auth.user_type_id == 1 || auth.company_id === props.row.id"
            v-on:click="edit(props.row.id, tableData)"
            type="primary"
            icon="el-icon-edit"
            v-b-tooltip.hover
            title="Editar"
            circle
          ></el-button>
          <el-button
            v-if="auth.user_type_id == 1"
            v-on:click="deleteRow(props.row.id, tableData)"
            type="danger"
            icon="el-icon-delete"
            v-b-tooltip.hover
            title="Eliminar"
            circle
          ></el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  props: ["companies", "auth"],
  methods: {
    changeInput($event) {
      this.tableData = this.$props.companies.filter((data) => {
        return (
          !this.$truthty(this.search) ||
          (this.$truthty(this.search) &&
            ((this.$truthty(data.business_name) &&
              data.business_name
                .toLowerCase()
                .includes(this.search.toLowerCase())) ||
              (this.$truthty(data.contact_email) &&
                data.contact_email.toLowerCase().includes(this.search.toLowerCase())) ||
              (this.$truthty(data.contact_email) &&
                data.rut.toLowerCase().includes(this.search.toLowerCase())) ||
              (this.$truthty(data.parentCompanies) &&
                this.$truthty(
                  data.parentCompanies.find((parentCompanyName) => {
                    return parentCompanyName
                      .toLowerCase()
                      .includes(this.search.toLowerCase());
                  })
                ))))
        );
      });
    },
    edit(id, rows) {
      window.location.href =
        window.location.origin + "/companies/" + id + "/edit";
    },
    show(id, rows) {
      window.location.href = window.location.origin + "/companies/" + id;
    },
    deleteRow(id, rows) {
      this.idRowDelete = id;
      this.$refs["modal-confirm"].show();
    },
    submit() {
      axios
        .delete(window.location.origin + "/companies/" + this.idRowDelete)
        .then((res) => {
          window.location.href = window.location.origin + "/companies";
        })
        .catch((err) => {
          this.errors.push( err.response.data.message );
        });
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    valuesFilter(tableData, e) {
      let names = [];
      tableData.map((data) => {
        names.push(data[e]);
      });
      names = names.unique();
      names.sort();
      let values = [];
      names.map((name) => {
        values.push({ text: name, value: name });
      });
      return values;
    },
    filter: (e) => (value, row) => {
      return row[e] == value;
    },
    valuesFilterPrincipal(tableData) {
      let names = [];
      tableData.map((data) => {
        if (this.auth.user_type_id > 1) {
          if (data.id == this.auth.company_id) {
            data.parentCompanies.map((data2) => {
              names.push(data2);
            });
          } else {
            names.push(this.company.business_name);
          }
        } else {
          data.parentCompanies.map((data2) => {
            names.push(data2);
          });
        }
      });
      names = names.unique();
      names.sort();
      let values = [];
      names.map((name) => {
        values.push({ text: name, value: name });
      });
      return values;
    },
    sortSecundario(other) {
      return function (a, b) {
        if (a.parent_name < b.parent_name) return -1;
        if (a.parent_name > b.parent_name) return 1;
        if (a[other] < b[other]) return -1;
        if (a[other] > b[other]) return 1;
      };
    },
    sortTernario(other) {
      return function (a, b) {
        if (a.parent_name < b.parent_name) return -1;
        if (a.parent_name > b.parent_name) return 1;
        if (a.business_name < b.business_name) return -1;
        if (a.business_name > b.business_name) return 1;
        if (a[other] < b[other]) return -1;
        if (a[other] > b[other]) return 1;
      };
    },
    init() {
      console.log(this.$props.companies)
    },
  },
  data() {
    let company = {};
    if (this.auth.user_type_id > 1) {
      company = this.$props.companies.find((x) => {
        return x.id == this.auth.company_id;
      });
    }
    return {
      tableData: this.$props.companies,
      idRowDelete: null,
      search: "",
      company,
      errors: [],
    };
  },
  mounted() {
    this.init();
  },
};
</script>

