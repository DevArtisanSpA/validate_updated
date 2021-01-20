require('./bootstrap');

window.Vue = require("vue");
import ElementUI from "element-ui";
import { BootstrapVue, BIconCheckCircleFill, BIconExclamationCircle } from "bootstrap-vue";
import locale from "element-ui/lib/locale/lang/es";

import "element-ui/lib/theme-chalk/index.css";

import "bootstrap-vue/dist/bootstrap-vue.css";

import "element-ui/lib/theme-chalk/index.css";

Vue.use(ElementUI, { locale });
Vue.use(BootstrapVue);
Vue.component('BIconCheckCircleFill', BIconCheckCircleFill);
Vue.component('BIconExclamationCircle', BIconExclamationCircle);

//Company
/*Vue.component(
  "company-table",
  require("./components/company/company-table.vue").default
);*/
Vue.component(
  "company-form",
  require("./components/company/form.vue").default
);
Vue.component(
  "company-search",
  require("./components/company/search.vue").default
);
Vue.component(
  "company-table",
  require("./components/company/table.vue").default
);

//Service
Vue.component(
  "associate-service",
  require("./components/service/associate.vue").default
);

Vue.component(
  "service-form",
  require("./components/service/form.vue").default
);

Vue.component(
  "service-table",
  require("./components/service/table.vue").default
);

Vue.component(
  "service-multitable",
  require("./components/service/multitable.vue").default
);

//User
Vue.component(
  "user-form",
  require("./components/user/form.vue").default
);

Vue.component(
  "user-table",
  require("./components/user/table.vue").default
);

//Employee
Vue.component(
  "employee-form",
  require("./components/employee/form.vue").default
);
// Vue.component(
//   "company-search",
//   require("./components/company/search.vue").default
// );
Vue.component(
  "employee-table",
  require("./components/employee/table.vue").default
);

//Document
Vue.component(
  "document-input",
  require("./components/document/input.vue").default
);
Vue.component(
  "document-form",
  require("./components/document/form.vue").default
);
Vue.component(
  "document-table-company-base",
  require("./components/document/company/base/table.vue").default
);
Vue.component(
  "document-table-company-monthly",
  require("./components/document/company/monthly/table.vue").default
);
Vue.component(
  "document-table-employee-base",
  require("./components/document/employee/base/table.vue").default
);
Vue.component(
  "document-table-employee-monthly",
  require("./components/document/employee/monthly/table.vue").default
);

Vue.component(
  "review-table-company-base",
  require("./components/review/company/base/table.vue").default
);
Vue.component(
  "review-table-company-monthly",
  require("./components/review/company/monthly/table.vue").default
);
Vue.component(
  "review-table-employee-base",
  require("./components/review/employee/base/table.vue").default
);
Vue.component(
  "review-table-employee-monthly",
  require("./components/review/employee/monthly/table.vue").default
);
Vue.component(
  "review-form",
  require("./components/review/form.vue").default
);
Vue.prototype.$truthty = function (data) {
  if (
    data === undefined ||
    data === null ||
    (!data && data !== 0) ||
    data === ""
  ) {
    return false;
  }
  if (Array.isArray(data)) {
    return data.length !== 0;
  }
  if (typeof data !== "string" && typeof data !== "number") {
    if (typeof data === "object") {
      return Object.keys(data).length !== 0;
    }
  }
  return true;
};
String.prototype.toCamelCase = function () {
  let regex = /[A-Z\xC0-\xD6\xD8-\xDE]?[a-z\xDF-\xF6\xF8-\xFF]+|[A-Z\xC0-\xD6\xD8-\xDE]+(?![a-z\xDF-\xF6\xF8-\xFF])|\d+/g;

  let inputArray = this.match(regex);
  if (inputArray == null) {
    return '';
  }
  let result = "";
  for (let i = 0; i < inputArray.length; i++) {
    let str = inputArray[i];
    let tempStr = str.toLowerCase();
    tempStr = tempStr.substr(0, 1).toUpperCase() + tempStr.substr(1);

    result += tempStr + " ";
  }
  return result;
};

Vue.prototype.$csrf_token = $('meta[name="csrf-token"]').attr("content");

Array.prototype.unique = function (a) {
  return function () { return this.filter(a) }
}(function (a, b, c) {
  return c.indexOf(a, b + 1) < 0
});
//Report
Vue.component(
  "report-by-company",
  require("./components/report/report-by-company.vue").default
);

Vue.component(
  "pie-chart",
  require("./components/report/pie-chart.vue").default
);

//Branch_offices
Vue.component(
  "branch-office-table",
  require("./components/branchOffice/branch-office-table.vue").default
);
Vue.component(
  "branch-office-new-edit",
  require("./components/branchOffice/branch-office-new-edit.vue").default
);



const app = new Vue({
  el: "#app",
  components: {}
});

