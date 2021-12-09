<template>
  <div>
    <div v-if="submitted">
      Esmena presentada
      <pre>{{ submitted }}</pre>
    </div>
    <div v-else-if="submitting">Presentant esmena...</div>
    <form v-else @submit.prevent="submitAmendment">
      <div v-if="amendable">
        {{ amendable.title }}
        Errors: {{ errors }}
        <div class="d-flex">
          <textarea
            v-if="amendable.type !== 'deletion'"
            v-model="amendable.text"
            style="width: 600px; height: 600px"
          />
          <pre v-if="amendable.type === 'modification'" v-html="preview"></pre>
          <pre
            v-if="amendable.type === 'deletion'"
            class="deleted"
            v-html="amendable.original"
          ></pre>
        </div>
        <div>
          Justificació
          <textarea v-model="form.justification" />
        </div>
        <div>Presentada per: {{ user.name }} {{ user.last_name }}</div>
        <div v-if="canRegisterAsAssembly">
          <label>
            <input v-model="form.registered_by_assembly" type="checkbox" />
            Register as {{ user.data.comarca }}
          </label>
        </div>
        <button type="submit">Presenta esmena</button>
      </div>
    </form>
  </div>
</template>

<script>
import Diff from 'text-diff'

export default {
  data() {
    return {
      amendable: null,
      form: {
        justification: '',
        registered_by_assembly: false,
      },
      errors: [],
      submitting: false,
      submitted: null,
      csrf: '',
    }
  },

  computed: {
    preview() {
      const { original, text } = this.amendable
      const diff = new Diff({ timeout: 2, editCost: 6 })
      const textDiff = diff.main(original, text)
      diff.cleanupEfficiency(textDiff)
      return diff.prettyHtml(textDiff)
    },

    user() {
      return this.$store.state.auth.user
    },

    canRegisterAsAssembly() {
      return this.$store.state.assembly.assembly.is_spokesperson
    },
  },

  mounted() {
    this.$root.$on('amendText', (payload) => {
      const { text, ...amendable } = payload

      // Prefill with original text
      if (payload.type === 'deletion') {
        this.amendable = {
          original: text,
          text: '',
          ...amendable,
        }
      } else {
        this.amendable = {
          original: text,
          text,
          ...amendable,
        }
      }

      // Reset form
      this.form.justification = ''
      this.form.registered_by_assembly = false
      this.submitted = null
      this.errors = []
      this.submitting = false
    })
  },

  methods: {
    async submitAmendment() {
      this.errors = []
      this.submitting = true

      try {
        const amendment = await this.$api.submitAmendment(
          this.$store.state.assembly.assembly.ref,
          this.$route.params.doc,
          {
            ...this.amendable,
            ...this.form,
          }
        )
        this.submitted = amendment
        this.$root.$emit('amendmentSubmitted', amendment)
      } catch (response) {
        this.errors = response.errors
      } finally {
        this.submitting = false
      }
    },
  },
}
</script>

<style lang="scss">
form {
  background: yellow;
}

ins {
  background: green;
}

del {
  background: red;
}

.deleted {
  text-decoration: line-through;
}
</style>
