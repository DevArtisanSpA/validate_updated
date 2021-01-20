<template>
  <div>
    <!-- <b-row>
      <b-col sm="4">
        <el-input v-model="search" placeholder="Buscar " clearable />
      </b-col>
    </b-row> -->
    <b-overlay :show="loading" rounded="sm">
      <el-table
        stripe
        height="600"
        class="w-100"
        :data="tableData"
        ref="multipleTable"
      >
        <!-- <el-table-column
        v-if="this.$props.states.area == 0"
        label="Empleado"
        :sort-method="sortSecundario('surname')"
        sortable
        :filters="valuesFilterEmployee(tableData)"
        :filter-method="filterEmployee"
      >
        <template slot-scope="props">{{
          `${props.row.surname} ${props.row.second_surname}, ${props.row.name}`
        }}</template>
      </el-table-column> -->
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.branch_office.company.business_name"
          label="Principal"
          sortable
          :sort-method="sortPrincipal"
          :filters="
            valuesFilter(
              tableData,
              'service.branch_office.company.business_name'
            )
          "
          :filter-method="
            filterRow('service.branch_office.company.business_name')
          "
        />
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.branch_office.name"
          label="Sucursal"
          sortable
          :sort-method="sortBranch"
          :filters="valuesFilter(tableData, 'service.branch_office.name')"
          :filter-method="filterRow('service.branch_office.name')"
        />
        <el-table-column
          v-if="auth.user_type_id == 1"
          prop="service.company.business_name"
          sortable
          label="Contratista"
          :filters="valuesFilter(tableData, 'service.company.business_name')"
          :filter-method="filterRow('service.company.business_name')"
        />
        <el-table-column
          label="Servicio"
          sortable
          :sort-method="sortService"
          prop="service.description"
        ></el-table-column>
        <!-- <template slot-scope="props">
{{`${props.row.service.description.toCamelCase()} tipo ${props.row.service.service_type.name}`}}
        </template> -->
        <el-table-column label="Nombre">
          <template slot-scope="props">
            {{
              `${
                $truthty(props.row.surname)
                  ? props.row.surname.toCamelCase()
                  : ""
              } ${
                $truthty(props.row.second_surname)
                  ? props.row.second_surname.toCamelCase()
                  : ""
              }, ${
                $truthty(props.row.name) ? props.row.name.toCamelCase() : ""
              } `
            }}
          </template>
        </el-table-column>
        <el-table-column
          prop="identification_id"
          label="N° de identificación"
          sortable
        />
        <!-- <el-table-column
          prop="service.service_type.name"
          label="Tipo"
          sortable
        /> -->
        <el-table-column
          label="Periodo"
          sortable
          prop="service.month_year_registry"
        />
        <el-table-column label="Estado">
          <template slot-scope="props">
            <div v-if="props.row.service.rejected > 0">
              Revisados incorrectos
            </div>
            <div v-else-if="props.row.service.pending > 0">
              Pendiente revisión
            </div>
            <div v-else-if="props.row.service.approved > 0">
              Revisados correctos
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Acciones" width="120">
          <template slot-scope="scope">
            <el-button
              :type="
                scope.row.service.rejected > 0
                  ? 'danger'
                  : scope.row.service.pending > 0
                  ? 'warning'
                  : 'success'
              "
              icon="el-icon-view"
              v-b-tooltip.hover
              title="Ver"
              circle
              @click.prevent="goTo(scope.row)"
            ></el-button>
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
  props: ["documents", "auth", "employees"],
  data() {
    return {
      search: null,
      loading: false,
      tableData: this.employees,
    };
  },
  methods: {
    valuesFilter(tableData, e) {
      let names = [];
      let access = e.split(".");
      tableData.map((data) => {
        let add = data;
        access.map((x) => {
          add = add[x];
        });
        names.push(add);
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
        let add = row;
        access.map((x) => {
          add = add[x];
        });
        return add == value;
      };
    },
    goTo(row) {
      window.location.href = `${window.location.origin}/review/${row.service.id}/documents/employees/${row.id}/monthly/${row.service.month_year_registry}`;
    },
    sortService(a, b) {
      if (
        a.service.branch_office.company.business_name <
        b.service.branch_office.company.business_name
      )
        return -1;
      if (
        a.service.branch_office.company.business_name >
        b.service.branch_office.company.business_name
      )
        return 1;
      if (a.service.description < b.service.description) return -1;
      if (a.service.description > b.service.description) return 1;
    },
    sortPrincipal(a, b) {
      if (
        a.service.branch_office.company.business_name <
        b.service.branch_office.company.business_name
      )
        return -1;
      if (
        a.service.branch_office.company.business_name >
        b.service.branch_office.company.business_name
      )
        return 1;
    },
    sortBranch(a, b) {
      if (
        a.service.branch_office.company.business_name <
        b.service.branch_office.company.business_name
      )
        return -1;
      if (
        a.service.branch_office.company.business_name >
        b.service.branch_office.company.business_name
      )
        return 1;
      if (a.service.branch_office.name < b.service.branch_office.name)
        return -1;
      if (a.service.branch_office.name > b.service.branch_office.name) return 1;
    },
  },
  mounted() {
    console.log(this.employees);
  },
};
</script>