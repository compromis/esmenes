<template>
  <div>
    <b-button
      :disabled="loading || supported || !canBeSupported"
      :size="fullWidth ? 'lg' : 'sm'"
      :variant="supported ? 'secondary' : 'primary'"
      outline
      :block="fullWidth"
      @click="support"
    >
      <template v-if="loading">Carregant...</template>
      <template v-else-if="supported">
        <font-awesome-icon :icon="['fal', 'check']" size="lg" class="me-2" />
        Has donat suport
      </template>
      <template v-else>
        <font-awesome-icon :icon="['fal', 'vote-yea']" size="lg" class="me-2" />
        Dona suport
      </template>
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
    fullWidth: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      loading: false,
      supported: false,
      error: false,
    }
  },

  computed: {
    canBeSupported() {
      return this.$store.state.assembly.assembly.supports_open
    },
  },

  created() {
    if ('supports' in this.amendment && this.amendment.supports.length > 0) {
      this.supported =
        this.amendment.supports[0].user_id === this.$store.state.auth.user.id
    }
  },

  methods: {
    async support() {
      if (!this.canBeSupported) {
        alert("El termini per signar esmenes s'ha tancat")
      }

      this.loading = true

      try {
        await this.$api.support(this.$route.params.assembly, this.amendment.ref)
        this.supported = true
        this.$root.$emit('supportedAmendment', true)
      } catch (e) {
        this.error = true
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
