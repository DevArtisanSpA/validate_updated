<template>
  <div>
    <b-tabs content-class="mt-3">
      <b-tab active>
        <template #title>
          Servicios contratados <b-badge variant="info">{{ services.contracted.length }}</b-badge>
        </template>
        <service-table :auth="auth" :services="services.contracted" />
      </b-tab>
      <b-tab>
        <template #title>
          Servicios brindados <b-badge variant="info">{{ services.active.length }}</b-badge>
        </template>
        <service-table :auth="auth" :services="services.active" />
      </b-tab>
      <b-tab>
        <template #title>
          Servicios pendientes <b-badge variant="info">{{ services.pending.length }}</b-badge>
        </template>
        <service-table :auth="auth" :services="services.pending" />
      </b-tab>
  </b-tabs>
  </div>
</template>

<script>

export default {
  props: ["auth", "services"]
}
</script>
