<template>
  <div>
    <div v-if="assemblies.length === 0">
      No hi ha cap procés d'esmenes de documents obert
    </div>
    <div v-else>Redirigint a {{ assemblies[0].name }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      assemblies: [],
    }
  },

  async mounted() {
    this.assemblies = await this.$api.assemblies()
    // Temporarily redirect to the first assembly
    if (this.assemblies.length > 0) {
      this.$router.push({ path: `/${this.assemblies[0].ref}` })
    }
  },
}
</script>
