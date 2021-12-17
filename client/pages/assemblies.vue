<template>
  <div class="text-muted text-2xl p-3">
    <div v-if="assemblies.length === 0">
      No tens cap procés d'esmenes de documents obert
    </div>
    <div v-else>Redirigint...</div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  data() {
    return {
      assemblies: [],
    }
  },

  async mounted() {
    this.assemblies = await this.$api.assemblies()
    this.$store.commit('assembly/setAssembly', null)

    // Redirect to first assembly
    if (this.assemblies.length > 0) {
      this.$router.push(`/${this.assemblies[0].ref}`)
    }
  },
}
</script>
