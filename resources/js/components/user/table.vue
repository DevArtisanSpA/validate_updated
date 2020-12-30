<template>
  <div>
    <el-input
      v-model="search"
      placeholder="Buscar usuario"
      class="input-search"
      clearable
      @input="changeInput($event)"
    />
    <el-table
      stripe
      height="600"
      class="w-100"
      :data="
      this.tableData"
    >
      <b-modal ref="modal-confirm" hide-footer>
        <div slot="modal-title">
          <h5>IMPORTANTE</h5>
        </div>

        <div class="d-block text-center mt-2 mb-4">
          <span>¿Estás seguro de eliminar a este usuario?</span>
        </div>

        <div class="float-right">
          <b-button @click="hideModal">Cancelar</b-button>
          <b-button variant="primary" @click="submit">Aceptar</b-button>
        </div>
      </b-modal>
      <el-table-column prop="name" label="Nombre" />
      <el-table-column prop="email" label="Email" />
      <el-table-column prop="company.business_name" label="Empresa" />
      <el-table-column prop="created_at" label="Fecha Registro" />
      <el-table-column label="Acciones" width="120">
        <template slot-scope="props">
          <el-button
            v-on:click="edit(props.$index, tableData)"
            type="primary"
            icon="el-icon-edit"
            v-b-tooltip.hover
            title="Editar"
            circle
          ></el-button>
          <el-button
            v-on:click="deleteRow(props.$index, tableData)"
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
  props: ["users"],
  methods: {
    changeInput(event) {
      console.log(this.search);
      console.log(event)
      this.tableData = this.$props.users.filter((data) => {
        return (
          data.name.toLowerCase().includes(this.search.toLowerCase()) ||
          data.email.toLowerCase().includes(this.search.toLowerCase()) ||
          (this.$truthty(data.company) && data.company.business_name.toLowerCase().includes(this.search.toLowerCase()))
        );
      });
    },
    edit(index, rows) {
      window.location.href =
        window.location.origin + "/users/" + rows[index].id + "/edit";
    },
    deleteRow(index, rows) {
      this.idRowDelete = rows[index].id;
      this.$refs["modal-confirm"].show();
    },
    submit() {
      axios
        .delete(window.location.origin + "/users/" + this.idRowDelete)
        .then((res) => {
          window.location.href = window.location.origin + "/users";
        })
        .catch((err) => {
          // catch error
        });
    },
    hideModal() {
      this.$refs["modal-confirm"].hide();
    }
  },
  data() {
    return {
      tableData: this.$props.users,
      idRowDelete: null,
      search: "",
    };
  },
  mounted() {
    this.$props.users.forEach((element) => {
      element.created_at = element.created_at.split("T")[0];
    });
  },
};
</script>

