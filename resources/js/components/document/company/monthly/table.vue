
      
<template>
  <div>
    <b-row>
      <b-col sm="4">
        <el-input v-model="search" placeholder="Buscar " clearable /> </b-col
      ><b-col sm="4">
        <b-form-input type="month" @input="filterSearch" :max="max" v-model="monthYear" />
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded="sm">
      <el-table
        stripe
        height="600"
        :data="
          tableData.filter(
            (data) =>
              !search ||
              data.company.business_name
                .toLowerCase()
                .includes(search.toLowerCase()) ||
              data.name_parent.toLowerCase().includes(search.toLowerCase()) ||
              data.branch_name.toLowerCase().includes(search.toLowerCase()) ||
              data.document_id.toLowerCase().includes(search.toLowerCase()) ||
              data.name.toLowerCase().includes(search.toLowerCase()) ||
              data.surname.toLowerCase().includes(search.toLowerCase())
          )
        "
        ref="multipleTable"
        class="w-100"
      >
        <!-- <el-table-column type="selection" width="30"> </el-table-column> -->
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.branch_office.company.business_name"
          label="Principal"
          sortable
          :filters="valuesFilter(tableData, 'name_parent')"
          :filter-method="filterRow('name_parent')"
        />
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.company.business_name"
          sortable
          label="Contratista"
          :filters="valuesFilter(tableData, 'business_name')"
          :filter-method="filterRow('business_name')"
        />
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.branch_office.name"
          label="Sucursal"
          sortable
          :filters="valuesFilter(tableData, 'branch_name')"
          :filter-method="filterRow('branch_name')"
        />
        <el-table-column
          prop="service.description"
          label="Servicio"
          sortable
        /><el-table-column
          prop="service.service_type.name"
          label="Tipo"
          sortable
        />
        <el-table-column label="Documentos" min-width="230">
          <template slot-scope="props">
            <div v-for="doc in props.row.service.documents" v-bind:key="doc.id">
              <p
                class="mb-1"
                v-if="
                  doc.validation_state.name != 'Sin documento' &&
                  doc.path_data != null
                "
              >
                <a :href="`/documents/download/${doc.id}`" target="_blank">
                  {{ doc.type.name }}
                  <i
                    v-if="doc.validation_state.name == 'Enviando (sin revisar)'"
                    class="el-icon-remove text-warning"
                  />
                  <i
                    v-if="doc.validation_state.name == 'Aprobado'"
                    class="el-icon-success text-success"
                  />
                  <i
                    v-if="doc.validation_state.name == 'Rechazado'"
                    class="el-icon-error text-danger"
                  />
                </a>
              </p>
              <p class="mb-1" v-else>
                {{ doc.type.name }}
              </p>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Acciones" width="120">
          <template slot-scope="scope">
            <el-button
              v-if="auth.user_type_id == 1 || auth.id_company === scope.row.id"
              v-on:click="edit(scope.row.service.id,scope.row.id)"
              type="primary"
              icon="el-icon-edit"
              v-b-tooltip.hover
              title="Editar"
              circle
            ></el-button>
            <el-button
              v-if="auth.user_type_id != 1 && auth.id_company !== scope.row.id"
              type="warning"
              icon="el-icon-view"
              title="Verificar"
              circle
              @click="goTo(scope.row)"
              v-b-tooltip.hover
            />
            <el-button
              @click="downloadZip(scope.row.service.id)"
              type="info"
              icon="el-icon-download"
              v-b-tooltip.hover
              title="Descargar Todos"
              circle
            />
          </template>
        </el-table-column>
      </el-table>
    </b-overlay>
  </div>
</template>

<script>
import moment from "moment";
const copy = (x) => {
  return JSON.parse(JSON.stringify(x));
};

export default {
  props: ["documents", "auth", "companies"],
  data() {
    let aux=window.location.pathname.split('/');
    return {
      search: null,
      loading: false,
      tableData: this.companies,
      max: moment().format("YYYY-MM"),
      monthYear:aux[aux.length-1],
    };
  },
  methods: {
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
    filterRow(e) {
      return (value, row) => {
        return row[e] == value;
      };
    },
    downloadZip(id_service) {},
    goTo(row) {},
    edit(id_service,id_company) {
      window.location.href = `${window.location.origin}/services/${id_service}/documents/companies/${id_company}/monthly/${this.monthYear}/edit`;
    },
    filterSearch() {
      this.loading = true;
      window.location.href =
        window.location.origin + "/documents/companies/monthly/" + this.monthYear;
    }
  },
  mounted() {
    console.log(this.companies, this.auth);
  },
};
</script>