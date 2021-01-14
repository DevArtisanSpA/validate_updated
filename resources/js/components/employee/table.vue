<template>
  <div>
    <b-row>
      <b-col
        ><el-input
          v-model="search"
          placeholder="Buscar empleado"
          class="input-search"
          @input="changeInput"
          clearable /></b-col
      ><b-col class="d-flex justify-content-end">
        <!-- <button
          type="button"
          v-if="myEmployee"
          @click="sureToSendMail"
          class="btn btn-warning"
        >
          Enviar correo
        </button>
        <el-tooltip
          v-else
          class="item"
          effect="dark"
          content="Botón deshabilitado por no contar con empleados"
          placement="top"
        >
          <div>
            <button type="button" disabled class="btn btn-warning">
              Enviar correo
            </button>
          </div>
        </el-tooltip> -->

        <b-modal
          ref="modal_mail"
          hide-footer
          @hidden="hideModalMail"
          :no-close-on-esc="send"
          :no-close-on-backdrop="send"
        >
          <div slot="modal-title"><h5>IMPORTANTE</h5></div>
          <div class="d-block text-center mt-2 mb-4">
            <span v-if="$truthty(errorMail)">{{ errorMail }}</span>
            <span v-else-if="$truthty(messageMail)">{{ messageMail }}</span>
            <span v-else>¿Estás seguro de enviar correo?</span>
          </div>
          <div
            style="float: right"
            v-if="!send && !$truthty(errorMail) && !$truthty(messageMail)"
          >
            <b-button @click.prevent="hideModalMail" variant="outline-secondary"
              >Cancelar</b-button
            >
            <b-button variant="primary" @click="send_mail">Aceptar</b-button>
          </div>
          <div
            v-else-if="!$truthty(errorMail) && !$truthty(messageMail)"
            class="d-flex justify-content-center"
            style="font-size: 36px"
          >
            <i class="el-icon-loading"></i>
          </div>
        </b-modal>
      </b-col>
    </b-row>

    <el-table
      ref="multipleTable"
      stripe
      height="600"
      class="w-100"
      :data="tableData"
      @selection-change="handleSelectionChange"
    >
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title"><h5>IMPORTANTE</h5></div>

        <div class="d-block text-center mt-2 mb-4">
          <span>¿Estás seguro de eliminar a este usuario?</span>
        </div>

        <div style="float: right">
          <b-button @click.prevent="hideModal" variant="outline-secondary"
            >Cancelar</b-button
          >
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
      </b-modal>
      <el-table-column type="expand" width="30">
        <template slot-scope="props">
          <b-row class="mb-0">
            <b-col md="4"
              ><p><strong>Email:</strong> {{ props.row.email }}</p></b-col
            >
            <b-col md="4"
              ><p><strong>Telefono:</strong> {{ props.row.phone }}</p></b-col
            >
            <b-col md="12"
              ><p>
                <strong>Dirección:</strong>
                {{
                  `${
                    $truthty(props.row.address)
                      ? `${props.row.address.toCamelCase()}, `
                      : ""
                  } 
                    ${
                      $truthty(props.row.commune.name)
                        ? `${props.row.commune.name.toCamelCase()}, `
                        : ""
                    }
                    ${
                      $truthty(props.row.commune.region.name)
                        ? `${props.row.commune.region.name.toCamelCase()}`
                        : ""
                    }`
                }}
              </p></b-col
            >
            <b-col md="4">
              <p v-if="$truthty(props.row.job_type)">
                <strong>Cargo:</strong> {{ props.row.job_type.name }}
              </p>
              <p v-else><strong>Cargo:</strong> Otro</p>
            </b-col>
            <b-col md="4">
              <p>
                <strong>Servicio:</strong>{{ props.row.service.description }}
              </p>
            </b-col>
            <b-col md="4">
              <p>
                <strong>Empresa Contratista:</strong
                >{{ props.row.service.company.business_name }}
              </p>
            </b-col>
            <b-col md="4">
              <p>
                <strong>Empresa Principal:</strong
                >{{ props.row.service.branch_office.company.business_name }}
              </p> </b-col
            ><b-col md="4">
              <p>
                <strong>Sucursal:</strong
                >{{ props.row.service.branch_office.name }}
              </p>
            </b-col>
            <!-- <b-col md="4"
              ><p>
                <strong>Fecha inicio de contrato:</strong>
                {{ props.row.contract_start }}
              </p></b-col
            >
            <b-col md="4"
              ><p v-if="props.row.contract_finished == null">
                <strong>Fecha término de contrato:</strong> Indefinido
              </p>
              <p v-else>
                <strong>Fecha término de contrato:</strong>
                {{ props.row.contract_finished }}
              </p></b-col> -->
          </b-row>
        </template>
      </el-table-column>
      <!-- <el-table-column type="selection" width="30"> </el-table-column> -->
      <el-table-column
        label="Status"
        width="100"
        :filters="valuesFilterStatus(tableData)"
        :filter-method="filter('validation')"
      >
        <template slot-scope="props">
          <el-tooltip
            class="item"
            effect="dark"
            :content="getStatus(props.row.validation)"
            placement="top"
          >
            <i
              :class="getIconStatus(props.row.validation)"
              type="danger"
              v-bind:style="{ fontSize: '40px' }"
            ></i>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column prop="identification_id" label="N° Documento" sortable />
      <el-table-column label="Nombre" :sort-method="sortTernario('surname')">
        <template slot-scope="props">
          {{
            `${
              $truthty(props.row.surname) ? props.row.surname.toCamelCase() : ""
            } ${
              $truthty(props.row.second_surname)
                ? props.row.second_surname.toCamelCase()
                : ""
            }, ${$truthty(props.row.name) ? props.row.name.toCamelCase() : ""} `
          }}
        </template>
      </el-table-column>
      <el-table-column
        label="Servicio"
        prop="service.description"
        sortable
      ></el-table-column>
      <el-table-column label="Acciones" width="120">
        <template slot-scope="props">
          <el-button
            v-if="auth.id_type != 1 && auth.id_company !== props.row.id_company"
            v-on:click="show(props.row)"
            type="warning"
            icon="el-icon-view"
            v-b-tooltip.hover
            title="Ver detalle"
            circle
          ></el-button>

          <el-button
            v-if="auth.id_type == 1 || auth.id_company === props.row.id_company"
            v-on:click="edit(props.row)"
            type="primary"
            icon="el-icon-edit"
            v-b-tooltip.hover
            title="Editar"
            circle
          ></el-button>
          <el-button
            v-if="auth.id_type == 1 || auth.id_company === props.row.id_company"
            v-on:click="deleteRow(props.row)"
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
const copy = (x) => {
  if (x != null && x != undefined) {
    return JSON.parse(JSON.stringify(x));
  }
  return x;
};

export default {
  props: ["employees", "auth"],
  data() {
    return {
      tableData: this.employees,
      idRowDelete: null,
      search: "",
      errors: [],
      send: false,
      errorMail: false,
      messageMail: null,
    };
  },
  mounted() {
    this.init();
  },
  methods: {
    changeInput() {
      const { search, multipleSelection } = this;
      let respaldo1 = copy(multipleSelection);
      let base = this.employees.filter((data) => {
        return (
          !search ||
          data.identification_id.toLowerCase().includes(search.toLowerCase()) ||
          (this.$truthty(data.surname) &&
            data.surname.toLowerCase().includes(search.toLowerCase())) ||
          (this.$truthty(data.second_surname) &&
            data.second_surname.toLowerCase().includes(search.toLowerCase())) ||
          (this.$truthty(data.name) &&
            data.name.toLowerCase().includes(search.toLowerCase())) ||
          data.service.description
            .toLowerCase()
            .includes(search.toLowerCase()) ||
          data.service.company.business_name
            .toLowerCase()
            .includes(search.toLowerCase()) ||
          data.service.branch_office.company.business_name
            .toLowerCase()
            .includes(search.toLowerCase()) ||
          data.service.branch_office.name
            .toLowerCase()
            .includes(search.toLowerCase())
        );
      });
      let respaldo2 = [];
      respaldo1.map((x) => {
        let row = base.find((y) => {
          return x.id == y.id;
        });
        if (this.$truthty(row)) {
          respaldo2.push(row);
        }
      });
      this.multipleSelectionBefore = respaldo2;
      this.tableData = base;
      if (this.$truthty(this.multipleSelectionBefore)) {
        let aux = copy(this.multipleSelectionBefore);
        aux.forEach((row) => {
          this.$refs.multipleTable.toggleRowSelection(row, true);
        });
      }
    },
    edit(row) {
      window.location.href =
        window.location.origin +
        "/services/" +
        row.service.id +
        "/employees/" +
        row.id +
        "/edit/";
    },
    show(row) {
      window.location.href = window.location.origin + "/employees/" + row.id;
    },
    deleteRow(row) {
      this.idRowDelete = row.id;
      this.$refs["modal-confirm"].show();
    },
    submit() {
      axios
        .delete(window.location.origin + "/employees/" + this.idRowDelete)
        .then((res) => {
          window.location.href = window.location.origin + "/employees";
        })
        .catch((err) => {
          // catch error
        });
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    hideModalMail() {
      this.$refs["modal_mail"].hide();
      this.errorMail = "";
      this.messageMail = "";
      this.statusMail = false;
    },
    sureToSendMail() {
      if (this.$truthty(this.multipleSelection)) {
        this.$refs["modal_mail"].show();
      }
    },
    send_mail() {
      this.send = true;
      axios
        .post(window.location.origin + "/mail/mail_employee_created", {
          employees: this.multipleSelection,
        })
        .then((respose) => {
          this.send = false;
          if (respose.status == 200) {
            this.statusMail = true;
            this.messageMail = "El correo se envio exitosamente";
            this.$refs.multipleTable.clearSelection();
          } else {
            this.statusMail = false;
            this.errorMail =
              "Ha ocurrido error al enviar el correo, intente más tarde";
          }
        })
        .catch((error) => {
          this.send = false;
          this.statusMail = false;
          this.errorMail = false;
          this.errorMail =
            "Ha ocurrido error al enviar el correo, intente más tarde";
        });
    },
    getStatus(validation) {
      const numEmpresa = 4;
      if (validation == 1 || validation == 1 + numEmpresa) {
        return `Pendiente por falta de archivos ${
          validation > numEmpresa ? "de la empresa" : ""
        }`;
      } // negado por falta de documento
      if (validation == 2 || validation == 2 + numEmpresa) {
        return `Pendiente, aún se están revisando los documentos ${
          validation > numEmpresa ? "de la empresa" : ""
        }`;
      } // negado por falta de documento
      if (validation == 3 || validation == 3 + numEmpresa) {
        return "Aprobado";
      } // aprobado
      if (validation == 4 || validation == 4 + numEmpresa) {
        return `Rechazado ${
          validation > numEmpresa ? "por documentacion de la empresa" : ""
        }`;
      } // negado estado de documento
    },
    getIconStatus(validation) {
      const numEmpresa = 4;
      if (
        validation == 1 ||
        validation == 2 ||
        validation == 1 + numEmpresa ||
        validation == 2 + numEmpresa
      ) {
        return "el-icon-remove text-warning";
      } // negado por falta de documento
      if (validation == 3 || validation == 3 + numEmpresa) {
        return "el-icon-success text-success";
      } // aprobado
      if (validation == 4 || validation == 4 + numEmpresa) {
        return "el-icon-error text-danger";
      } // negado estado de documento
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
        if (a.business_name) {
          if (a.business_name < b.business_name) return -1;
          if (a.business_name > b.business_name) return 1;
        } else {
          if (a.company.business_name < b.company.business_name) return -1;
          if (a.company.business_name > b.company.business_name) return 1;
        }
        if (a[other] < b[other]) return -1;
        if (a[other] > b[other]) return 1;
      };
    },
    valuesFilterEmployee(tableData) {
      let names = [];
      tableData.map((data) => {
        names.push(`${data.surname} ${data.second_surname}, ${data.name}`);
      });
      names = names.unique();
      names.sort();
      let values = [];
      names.map((name) => {
        values.push({ text: name, value: name });
      });
      return values;
    },
    filterEmployee(value, row) {
      return `${row.surname} ${row.second_surname}, ${row.name}` == value;
    },
    valuesFilterStatus(tableData) {
      let values = [];
      tableData.map((data) => {
        values.push(data.validation);
      });
      values = values.unique();
      values.sort();
      let valuesEnd = [];
      values.map((v) => {
        valuesEnd.push({ text: this.getStatus(v), value: v });
      });
      return valuesEnd;
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    init() {
      console.log(this.employees);
    },
  },
};
</script>

