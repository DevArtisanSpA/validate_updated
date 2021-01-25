<template>
  <div class="card h-100">
    <div class="card-body row mb-0">
      <b-col md="12">
        <div for="input-search">
          <h4 class="card-title">Consultar estado empleado</h4>
        </div>
      </b-col>
      <b-col md="6">
        <b-form-input
          id="input-search"
          v-model="search"
          :state="state"
          trim
        ></b-form-input>
        <b-form-invalid-feedback id="input-live-feedback">{{
          this.invalidFeedback()
        }}</b-form-invalid-feedback>
      </b-col>
      <b-col md="4">
        <button type="button" @click="submit" class="btn btn-success">
          Consultar
        </button>
      </b-col>
      <div class="col-md-2 text-center">
        <div v-if="this.returnOK === true && this.checkRut()">
          <BIconCheckCircleFill
            font-scale="3"
            class="rounded-circle bg-success p-2 text-white"
          ></BIconCheckCircleFill>
          <!-- <p class="my-3">Empleado validado correctamente.</p> -->
        </div>
        <div v-if="this.returnOK === false && this.checkRut()">
          <BIconExclamationCircle
            font-scale="3"
            class="rounded-circle bg-danger p-2 text-white"
          ></BIconExclamationCircle>
          <!-- <p class="my-3">Empleado no validado.</p> -->
        </div>
      </div>
      <div class="col-md-12">
        <p class="my-1 text-muted" v-if="this.returnOK === true && this.checkRut()">
          Empleado validado correctamente.
        </p>
        <p class="my-1 text-muted" v-if="this.returnOK === false && this.checkRut()">
          Empleado no validado.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
const copy = (x) => {
  if (x != null && x != undefined) {
    return JSON.parse(JSON.stringify(x));
  }
  return x;
};
//$state = 1; // negado por falta de documento
//$state = 2; // negado por falta de documento
//$state = 3; // aprobado
//$state = 4; // negado estado de documento
export default {
  props: ["employee"],
  computed: {
    state() {
      return this.checkRut();
    },
    // invalidFeedback() {
    //   if (this.checkRut()) {
    //     return "El RUT ingresado no es válido.";
    //   }
    //   return "Por favor ingresar un RUT válido.";
    // },
  },
  methods: {
    invalidFeedback() {
      if (this.checkRut()) {
        return "El RUT ingresado no es válido.";
      }
      return "Por favor ingresar un RUT válido.";
    },
    submit() {
      const { search } = this;

      if (search.length > 6) {
        if (this.checkRut()) {
          axios
            .post(window.location.origin + "/employee/getEmployeeState", {
              number_identification: search,
            })
            .then((res) => {
              const { data } = res;
              this.returnOK = data;
              this.rutOK = null;
            })
            .catch((err) => {
              // catch error
              this.returnOK = false;
              this.rutOK = null;
            });
        } else {
          this.rutOK = false;
        }
      } else {
        this.rutOK = false;
      }
    },
    checkRut() {
      const { search } = this;
      if (search.length === 0) {
        return null;
      }
      if (search.length < 7) {
        return false;
      }
      let valor = search;

      // Despejar Puntos y guion
      valor = valor.replace(/[.-]/g, "");

      // Aislar Cuerpo y Dígito Verificador
      let cuerpo = valor.slice(0, -1);
      if (cuerpo) {
        let dv = valor.slice(-1).toUpperCase();

        // Calcular Dígito Verificador
        let suma = 0;
        let multiplo = 2;

        let arrayMultiplos = [2, 3, 4, 5, 6, 7];
        let index = 0;

        // Para cada dígito del Cuerpo
        for (let i = 1; i <= cuerpo.length; i++) {
          let valorCuerpo = Number(cuerpo[cuerpo.length - i]);
          let producto = valorCuerpo * arrayMultiplos[index];
          index++;
          if (index == arrayMultiplos.length) {
            index = 0;
          }
          suma = suma + producto;
        }

        // Calcular Dígito Verificador en base al Módulo 11
        let dvEsperado = 11 - (suma % 11);

        // Casos Especiales (0 y K)

        let dvAux_k = dv == "K" ? 10 : dv;
        let dvAux_0 = dv == 0 ? 11 : dv;

        // Validar que el Cuerpo coincide con su Dígito Verificador
        if (
          dvEsperado != dv &&
          dvEsperado != dvAux_k &&
          dvEsperado != dvAux_0
        ) {
          this.rutOK = false;
          this.returnOK = null;
          return false;
        }
      } else {
        this.returnOK = null;
        this.rutOK = false;
        return false;
      }
      this.rutOK = true;
      return true;
    },
    init() {},
  },
  data() {
    return {
      returnOK: null,
      rutOK: null,
      search: "",
    };
  },
  mounted() {
    this.init();
  },
};
</script>

