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
      placeholder="Buscar servicio"
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
              ((this.$truthty(data.company.business_name) &&
                data.company.business_name
                  .toLowerCase()
                  .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.service_type.name) &&
                  data.service_type.name
                    .toLowerCase()
                    .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.branch_office.name) &&
                  data.branch_office.name.toLowerCase().includes(this.search.toLowerCase())) ||
                (this.$truthty(data.branch_office.company.business_name) &&
                  this.$truthty(
                    data.branch_office.company.business_name.toLowerCase().includes(this.search.toLowerCase())
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
          <span>¿Estás seguro de eliminar a este servicio?</span>
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
            <b-col md="12">
              <p>
                <b>Descripción:</b>
                {{ props.row.description }}
              </p>
            </b-col>
            <b-col md="3">
              <p>
                <b>Inicio:</b>
                {{ props.row.start }}
              </p>
            </b-col>
            <b-col md="3">
              <p>
                <b>Término:</b>
                {{ props.row.finished }}
              </p>
            </b-col>
          </b-row>
        </template>
      </el-table-column>
      <el-table-column
        prop="company.business_name"
        label="Empresa contratista"
        sortable
        :sort-method="sortSecundario(1)"
        :filters="valuesFilter(tableData, 1)"
        :filter-method="filter(1)"
      />
      <el-table-column
        prop="service_type.name"
        label="Tipo de servicio"
        sortable
        :sort-method="sortSecundario(2)"
        :filters="valuesFilter(tableData, 2)"
        :filter-method="filter(2)"
      />
      <el-table-column
        prop="branch_office.name"
        label="Sucursal"
        sortable
        :sort-method="sortSecundario(3)"
        :filters="valuesFilter(tableData, 3)"
        :filter-method="filter(3)"
      />
      <el-table-column
        prop="branch_office.company.business_name"
        label="Empresa principal"
        sortable
        :sort-method="sortSecundario(4)"
        :filters="valuesFilter(tableData, 4)"
        :filter-method="filter(4)"
      />

      <el-table-column label="Acciones">
        <template slot-scope="props">
          
          <el-button
            v-if="auth.user_type_id != 1 && auth.company_id == props.row.company.id"
            v-on:click="addDocuments(props.row.id, tableData)"
            type="warning"
            icon="el-icon-upload2"
            v-b-tooltip.hover
            title="Subir documentos"
            circle
          ></el-button>
          <el-button
            v-if="auth.user_type_id == 1 || auth.company_id == props.row.branch_office.company.id"
            v-on:click="edit(props.row.id, tableData)"
            type="primary"
            icon="el-icon-edit"
            v-b-tooltip.hover
            title="Editar"
            circle
          ></el-button>
          <el-button
            v-if="auth.user_type_id == 1 || auth.company_id == props.row.company.id"
            v-on:click="addEmployee(props.row.id)"
            type="success"
            icon="el-icon-plus"
            v-b-tooltip.hover
            title="Agregar Empleados"
            circle
          ></el-button>
          <el-button
            v-if="auth.user_type_id == 1 || auth.company_id == props.row.branch_office.company.id"
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
  props: ["services", "auth"],
  methods: {
    changeInput($event) {
      this.tableData = this.$props.companies.filter((data) => {
        return (
          !this.$truthty(this.search) ||
            (this.$truthty(this.search) &&
              ((this.$truthty(data.company.business_name) &&
                data.company.business_name
                  .toLowerCase()
                  .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.service_type.name) &&
                  data.service_type.name
                    .toLowerCase()
                    .includes(this.search.toLowerCase())) ||
                (this.$truthty(data.branch_office.name) &&
                  data.branch_office.name.toLowerCase().includes(this.search.toLowerCase())) ||
                (this.$truthty(data.branch_office.company.business_name) &&
                  this.$truthty(
                    data.branch_office.company.business_name.toLowerCase().includes(this.search.toLowerCase())
                  ))))
        );
      });
    },
    edit(id, rows) {
      window.location.href =
        window.location.origin + "/services/" + id + "/edit";
    },
    addDocuments(id) {
      window.location.href = window.location.origin + "/home";
    },
    addEmployee(id){
      window.location.href = window.location.origin + "/employees/"+id+"/create";
    },
    deleteRow(id, rows) {
      this.idRowDelete = id;
      this.$refs["modal-confirm"].show();
    },
    submit() {
      axios
        .delete(window.location.origin + "/services/" + this.idRowDelete)
        .then((res) => {
          window.location.href = window.location.origin + "/services";
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
        switch(e) {
          case 1:
            names.push(data.company.business_name);
            break;
          case 2:
            names.push(data.service_type.name);
            break;
          case 3:
            names.push(data.branch_office.name);
            break;
          case 4:
            names.push(data.branch_office.company.business_name);
            break;
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
    filter: (e) => (value, row) => {
      console.log(e, value, row);
      let rowValue = undefined;
      switch(e) {
        case 1:
          rowValue = row.company.business_name;
          break;
        case 2:
          rowValue = row.service_type.name;
          break;
        case 3:
          rowValue = row.branch_office.name;
          break;
        case 4:
         rowValue = row.branch_office.company.business_name;
          break;
        default:
          rowValue = "";
      }
      return rowValue == value;
    },
    sortSecundario(id) {
      return function (a, b) {
        switch(id) {
        case 1:
          if (a.company.business_name < b.company.business_name) return -1;
          if (a.company.business_name > b.company.business_name) return 1;
          return 0;
        case 2:
          if (a.service_type.name < b.service_type.name) return -1;
          if (a.service_type.name > b.service_type.name) return 1;
          return 0;
        case 3:
          if (a.branch_office.name < b.branch_office.name) return -1;
          if (a.branch_office.name > b.branch_office.name) return 1;
          return 0;
        case 4:
          if (a.branch_office.company.business_name < b.branch_office.company.business_name) return -1;
          if (a.branch_office.company.business_name > b.branch_office.company.business_name) return 1;
          return 0;
        default:
          return 0;
      }
      };
    },
    init() {
      console.log(this.$props.services)
    },
  },
  data() {
    return {
      tableData: this.$props.services,
      idRowDelete: null,
      search: "",
      errors: [],
    };
  },
  mounted() {
    this.init();
  },
};
</script>

