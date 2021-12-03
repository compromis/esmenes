<template>
  <div>Carregant...</div>
</template>

<script>
export default {
  mounted() {
    this.$store.commit('auth/logOut')
    this.$router.push('/')
  },
}
</script>
