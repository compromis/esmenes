<template>
  <div>
    <a :href="loginUrl">Login</a>
  </div>
</template>

<script>
export default {
  middleware({ store, redirect }) {
    if (store.state.auth.token) {
      redirect('/assemblies')
    }
  },

  computed: {
    loginUrl() {
      return process.env.NUXT_ENV_ESPAI_SSO
    },
  },
}
</script>
