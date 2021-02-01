<template>
  <div>
    <b-modal
      ref="modal-confirm"
      hide-footer
      :no-close-on-esc="true"
      :hide-header-close="true"
      :header-bg-variant="success ? 'info' : error ? 'danger' : ''"
    >
      <div slot="modal-title" v-if="!$truthty(message)">
        ¿Estás seguro de notificar la actualización de los estados?
      </div>
      <div slot="modal-title" v-else>{{ message }}</div>
      <!-- <div class="d-block text-justify" style="margin-bottom: 20px">
       
      </div> -->
      <div v-if="!send && $truthty(message)" class="float-right">
        <b-button
          @click="hideModal"
          :variant="success ? 'outline-info' : 'outline-danger'"
          >Aceptar</b-button
        >
      </div>
      <div v-else-if="!send" class="float-right">
        <b-button @click="hideModal" variant="outline-secondary"
          >Cancelar</b-button
        >
        <b-button variant="primary" @click="submit">Aceptar</b-button>
      </div>
      <div v-else class="d-flex justify-content-center" style="font-size: 36px">
        <i class="el-icon-loading"></i>
      </div>
    </b-modal>
    <b-row>
      <b-col sm="4">
        <el-input v-model="search" placeholder="Buscar " clearable />
      </b-col>
      <b-col sm="4" v-if="monthly == 2">
        <b-form-input
          type="month"
          @change="filterSearch"
          :max="max"
          v-model="monthYear"
        />
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
              data.description.toLowerCase().includes(search.toLowerCase()) ||
              data.branch_office.company.business_name
                .toLowerCase()
                .includes(search.toLowerCase()) ||
              data.company.business_name
                .toLowerCase()
                .includes(search.toLowerCase())
          )
        "
        ref="multipleTable"
        class="w-100"
      >
        <!-- <el-table-column type="selection" width="30"> </el-table-column> -->
        <el-table-column
          prop="branch_office.company.business_name"
          label="Principal"
          sortable
          :filters="
            valuesFilter(tableData, 'branch_office.company.business_name')
          "
          :filter-method="filterRow('branch_office.company.business_name')"
        />
        <el-table-column
          prop="branch_office.name"
          label="Sucursal"
          sortable
          :filters="valuesFilter(tableData, 'branch_office.name')"
          :filter-method="filterRow('branch_office.name')"
        /><el-table-column
          v-if="auth.user_type_id == 1"
          prop="company.business_name"
          sortable
          label="Contratista"
          :filters="valuesFilter(tableData, 'company.business_name')"
          :filter-method="filterRow('company.business_name')"
        />
        <el-table-column
          prop="description"
          label="Servicio"
          :filters="valuesFilter(tableData, 'description')"
          :filter-method="filterRow('description')"
          sortable
        />

        <el-table-column
          prop="service_type.name"
          label="Tipo"
          :filters="valuesFilter(tableData, 'service_type.name')"
          :filter-method="filterRow('service_type.name')"
          sortable
          width="100"
        />
        <!-- <el-table-column label="Empleados" width="170">
          <template slot-scope="props">
            <div class="text-center">
              <p class="my-0" style="color: #606266">
                Total de empleados: {{ props.row.employeeTotal }}
              </p>
              <p class="my-0" style="color: #606266">
                Pendientes: {{ props.row.employeePending }}
              </p>
              <p class="my-0" style="color: #606266">
                Rechazados: {{ props.row.employeeRejected }}
              </p>
              <p class="my-0" style="color: #606266">
                Aprobados: {{ props.row.employeeApproved }}
              </p>
            </div></template
          >
        </el-table-column> -->
        <el-table-column label="Acciones" width="120">
          <template slot-scope="scope">
            <el-button
              type="primary"
              icon="el-icon-d-arrow-right"
              title="Siguiente"
              circle
              @click="goTo(scope.row)"
              v-b-tooltip.hover
            />
            <el-button
              type="warning"
              icon="el-icon-message"
              v-b-tooltip.hover
              title="Notificar las revisiones"
              circle
              @click="openModal(scope.row)"
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
  props: ["auth", "services", "monthly", "period"],
  data() {
    return {
      search: null,
      loading: false,
      tableData: this.services,
      service: null,
      send: false,
      monthYear: this.$truthty(this.period) ? this.period.monthYear : null,
      max: moment().format("YYYY-MM"),
      success: false,
      error: false,
      message: "",
    };
  },
  methods: {
    filterSearch(value) {
      window.location.href =
        window.location.origin + "/review/employees/monthly/" + value;
    },
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
    goTo(service) {
      switch (this.monthly) {
        case 1:
          window.location.href = `${window.location.origin}/review/${service.id}/employees/base`;
          break;
        case 2:
          window.location.href = `${window.location.origin}/review/${service.id}/employees/monthly/${this.monthYear}`;
          break;
        default:
      }
    },
    edit(id_service) {},
    hideModal() {
      this.$refs["modal-confirm"].hide();
      this.service = null;
      this.error = false;
      this.success = false;
      this.message = "";
      
    },
    openModal(service) {
      this.$refs["modal-confirm"].show();
      this.service = service;
    },
    submit() {
      console.log(this.monthly, this.service);
      this.send = true;
      axios
        .post(`${window.location.origin}/mail/documents/response`, {
          documents: [],
          service_id: this.service.id,
          area: 1,
          temp: this.monthly,
          period: this.monthYear,
        })
        .then((response) => {
          console.log(response.data);
          this.message = response.data.message;
          // window.location.href = urlBack;
          this.send = false;
          this.success = true;
          //this.hideModal();
        })
        .catch((error) => {
          console.log(error.response.data);
          this.message = error.response.data.message;
          this.send = false;
          this.error = true;
          //this.hideModal();
        });
    },
  },
  mounted() {
    console.log(this.services);
  },
};
</script>