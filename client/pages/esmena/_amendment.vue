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
  data() {
    return {
      amendment: null,
    }
  },

  async mounted() {
    const { amendment } = this.$route.params
    this.amendment = await this.$api.amendment(amendment)
  },
}
</script>
