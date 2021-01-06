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
        <button
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
        </el-tooltip>

        <b-modal ref="modal_mail" hide-footer @hidden="hideModalMail" :no-close-on-esc="send" :no-close-on-backdrop="send">
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
            <b-col md="12">
              <p v-if="$truthty(props.row.job_type)">
                <strong>Tipo:</strong> {{ props.row.job_type.name }}
              </p>
              <p v-else><strong>Tipo:</strong> Otro</p>
            </b-col>
            <b-col md="4"
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
              </p></b-col
            ></b-row
          >
        </template>
      </el-table-column>
      <el-table-column type="selection" width="30"> </el-table-column>
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
      <el-table-column
        prop="document_id"
        label="Documento"
        sortable
        width="140"
      />
      <el-table-column label="Nombre" :sort-method="sortTernario('surname')">
        <template slot-scope="props">
          {{
            `${$truthty(props.row.surname) ? props.row.surname.toCamelCase() : ""} ${$truthty(props.row.second_surname)? props.row.second_surname.toCamelCase(): ""}, ${$truthty(props.row.name) ? props.row.name.toCamelCase() : ""} `
          }}
        </template>
      </el-table-column>
      <el-table-column
        prop="business_name"
        label="Contratista"
        sortable
        :sort-method="sortSecundario('business_name')"
        :filters="valuesFilter(tableData, 'business_name')"
        :filter-method="filter('business_name')"
      />
      <el-table-column
        prop="parent_name"
        label="Principal"
        sortable
        :filters="valuesFilter(tableData, 'parent_name')"
        :filter-method="filter('parent_name')"
      />
      <el-table-column label="Sucursal" sortable prop="branch_name" />
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
export default {
  props: ["companies","employees", "auth"],
  methods: {
    init() {
      console.log(this.$props.companies)
    },
  },
  data() {
    // let company = {};
    // if (this.auth.user_type_id > 1) {
    //   company = this.$props.companies.find((x) => {
    //     return x.id == this.auth.company_id;
    //   });
    // }
    return {
      tableData: [],
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

