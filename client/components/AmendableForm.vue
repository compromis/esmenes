<template>
  <div>
    <form @submit.prevent="submitAmendment">
      <div v-if="amendable">
        {{ amendable.title }}
        <div class="d-flex">
          <textarea
            v-model="amendable.text"
            style="width: 600px; height: 600px"
          />
          <pre v-html="preview"></pre>
        </div>
        <div>
          Justificació
          <textarea v-model="form.justification" />
        </div>
        <div>Presentada per: {{ user.name }} {{ user.last_name }}</div>
        <div
          v-if="
            user.data.secretari_comarcal || user.data.secretari_comarcal_jove
          "
        >
          <input v-model="form.registered_by_assembly" type="checkbox" />
          Registered by assembly
        </div>
        <button type="submit">Presenta esmena</button>
      </div>
    </form>
  </div>
</template>

<script>
import diff from 'rich-text-diff'

export default {
  data() {
    return {
      amendable: null,
      form: {
        justification: '',
        registered_by_assembly: false,
      },
      errors: [],
      csrf: '',
    }
  },

  computed: {
    preview() {
      return diff(this.amendable.original, this.amendable.text)
    },

    user() {
      return this.$store.state.auth.user
    },
  },

  mounted() {
    this.$root.$on('amendText', (payload) => {
      this.amendable = {
        original: payload.text,
        ...payload,
      }
    })
  },

  methods: {
    async submitAmendment() {
      const submitted = await this.$api.submitAmendment(
        this.$store.state.assembly.assembly.ref,
        {
          document_ref: this.$route.params.doc,
          ...this.amendable,
          ...this.form,
        }
      )

      this.$root.$emit('amendmentSubmitted', submitted)
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
</style>
