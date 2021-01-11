<template>
  <div>
    <b-row>
      <b-col sm="4">
        <el-input v-model="search" placeholder="Buscar " clearable />
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      search: null,
    };
  },
  methods: {},
  mounted() {},
};
</script>