<template>
  <div>Carregant...</div>
</template>

<script>
export default {
  middleware({ store, redirect }) {
    if (store.state.auth.token) {
      redirect('/assemblies')
    } else {
      redirect(process.env.NUXT_ENV_ESPAI_SSO)
    }
  },
}
</script>
