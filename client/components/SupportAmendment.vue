<template>
  <div>
    <b-button
      :disabled="loading || supported"
      size="sm"
      :variant="supported ? 'secondary' : 'primary'"
      outline
      @click="support"
    >
      <span v-if="loading"> Carregant </span>
      <span v-else-if="supported"
        ><font-awesome-icon
          :icon="['fal', 'check']"
          size="lg"
          class="me-2"
        />Signat
      </span>
      <span v-else>
        <font-awesome-icon
          :icon="['fal', 'vote-yea']"
          size="lg"
          class="me-2"
        />Dona suport
      </span>
    </b-button>
  </div>
</template>

<script>
export default {
  props: {
    amendment: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      loading: false,
      supported: false,
      error: false,
    }
  },

  created() {
    if ('supports' in this.amendment && this.amendment.supports.length > 0) {
      this.supported =
        this.amendment.supports[0].user_id === this.$store.state.auth.user.id
    }
  },

  methods: {
    async support() {
      this.loading = true
      try {
        await this.$api.support(this.$route.params.assembly, this.amendment.ref)
        this.supported = true
      } catch (e) {
        this.error = true
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
