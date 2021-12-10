<template>
  <div>
    <div v-if="assemblies.length === 0">
      No hi ha cap procés d'esmenes de documents obert
    </div>
    <div v-else>
      <nuxt-link
        v-for="assembly in assemblies"
        :key="assembly.ref"
        :to="`/${assembly.ref}`"
        >{{ assembly.name }}</nuxt-link
      >
    </div>
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
  },
}
</script>
