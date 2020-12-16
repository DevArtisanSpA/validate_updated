<template>
  <div>
    <b-row class="input-group" v-if="this.$props.auth.id_type == 1">
      <b-col md="6">
        <b-form-select v-model="selected" class="w-50">
          <!-- This slot appears above the options from 'options' prop -->
          <template v-slot:first>
            <b-form-select-option :value="null">Todas las empresas</b-form-select-option>
          </template>

          <b-form-select-option
            v-for="company in this.$props.companies"
            v-bind:key="company.id"
            :value="company.business_name"
          >{{ company.business_name.toUpperCase() }}</b-form-select-option>
        </b-form-select>
      </b-col>
    </b-row>
    <div>
      <el-table
        stripe
        height="500"
        class="w-100"
        :data="this.companies_branch.filter(data => !selected ||
          data.company.business_name.toLowerCase().includes(selected.toLowerCase())
        )"
        :default-sort="{prop: 'company.business_name', order: 'ascending'}"
      >
        <b-modal ref="modal-confirm" hide-footer>
          <div slot="modal-title">
            <h5>IMPORTANTE</h5>
          </div>

          <div class="d-block text-center mt-2 mb-4">
            <p class="mt-3 mb-5">¿Estás seguro de eliminar este sucursal?</p>
          </div>

          <div class="d-flex justify-content-end">
            <b-button class="mr-2" @click.prevent="hideModal" variant="outline-secondary">Cancelar</b-button>
            <b-button variant="primary" @click="submit">Aceptar</b-button>
          </div>
        </b-modal>
        <el-table-column prop="company.business_name" label="Empresa" sortable />
        <el-table-column prop="name" label="Sucursal" sortable />
        <el-table-column prop="address" label="Dirección" />
        <el-table-column prop="commune.name" label="Comuna" sortable />
        <el-table-column prop="commune.region.name" label="Región" sortable />
        <el-table-column label="Acciones" width="120">
          <template slot-scope="scope">
            <el-button
              v-on:click="edit(scope.row.id, tableData)"
              type="primary"
              icon="el-icon-edit"
              v-b-tooltip.hover
              title="Editar"
              circle
            ></el-button>
            <el-button
              v-on:click="deleteRow(scope.row.id, tableData)"
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
  </div>
</template>

<script>
export default {
  props: ["companies_branch", "companies", "auth"],
  methods: {
    edit(id, rows) {
      window.location.href = window.location.origin + "/branch_offices/" + id + "/edit";
    },
    create() {
      window.location.href = window.location.origin + "/branch_offices/create";
    },
    deleteRow(id, rows) {
      this.idRowDelete = id;
      this.$refs["modal-confirm"].show();
    },
    submit() {
      axios
        .delete(window.location.origin + "/branch_offices/" + this.idRowDelete)
        .then((res) => {
          window.location.href = window.location.origin + "/branch_offices";
        })
        .catch((err) => {
          // catch error
        });
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    },
    init() {},
  },
  data() {
    return {
      tableData: this.$props.companies_branch,
      idRowDelete: null,
      selected: null,
    };
  },
  mounted() {
    this.init();
  },
};
</script>
