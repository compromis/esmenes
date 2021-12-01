<template>
  <div>
    <h2>Assemblies</h2>

    <nuxt-link
      v-for="assembly in assemblies"
      :key="assembly.ref"
      :to="`/${assembly.ref}`"
      >{{ assembly.name }}</nuxt-link
    >
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
  },
}
</script>
