<template>
  <div>
    <b-row>
      <b-col sm="4">
        <el-input v-model="search" placeholder="Buscar " clearable />
      </b-col>
      <b-col  sm="4">
        <b-form-input
          type="month"
          @input="filterSearch"
          :max="max"
        />
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      search:null,
      // monthYear: window.location.pathname.split("/").slice(-1).pop(),
      max: moment().format("YYYY-MM"),
      
    };
  },
  methods: {
   
  },
  mounted() {
  },
};
</script>