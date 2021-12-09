<template>
  <div>
    <div v-if="amendment">
      <pre>
        {{ amendment }}
      </pre>
    </div>
    <div v-else>Carregant...</div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  data() {
    return {
      amendment: null,
    }
  },

  async mounted() {
    const { assembly, amendment } = this.$route.params
    this.amendment = await this.$api.amendment(assembly, amendment)
  },
}
</script>
