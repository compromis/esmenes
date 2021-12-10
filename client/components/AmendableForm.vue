<template>
  <transition name="slide-in">
    <div v-if="shown" class="amendable-form-wrapper">
      <div class="amendable-form">
        <button class="close" @click="close">
          <font-awesome-icon :icon="['fal', 'times']" size="lg" />
        </button>
        <div v-if="submitted">
          Esmena presentada
          <pre>{{ submitted }}</pre>
        </div>
        <form v-else @submit.prevent="submitAmendment">
          <h2 class="text-3xl text-regular p-4">Presenta esmena</h2>
          <div v-if="amendable">
            {{ amendable.title }}
            Errors: {{ errors }}
            <div class="d-flex">
              <textarea
                v-if="amendable.type !== 'deletion'"
                ref="text"
                v-model="amendable.text"
                style="width: 600px; height: 600px"
              />
              <pre
                v-if="amendable.type === 'modification'"
                v-html="preview"
              ></pre>
              <pre
                v-if="amendable.type === 'deletion'"
                class="deleted"
                v-html="amendable.original"
              ></pre>
            </div>
            <div>
              Justificació
              <textarea ref="justification" v-model="form.justification" />
            </div>
            <div>Presentada per: {{ user.name }} {{ user.last_name }}</div>
            <div v-if="canRegisterAsAssembly">
              <label>
                <input v-model="form.registered_by_assembly" type="checkbox" />
                Register as {{ user.data.comarca }}
              </label>
            </div>
            <div class="submit-button p-4">
              <b-button
                variant="inverted"
                size="lg"
                block
                type="submit"
                :disabled="submitting"
              >
                {{ submitting ? 'Enviant...' : 'Presenta esmena' }}
              </b-button>
            </div>
          </div>
        </form>
      </div>
      <div class="amendable-form-backdrop" @click="close"></div>
    </div>
  </transition>
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
      shown: false,
      focusBackTo: null,
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
    this.$root.$on('amendText', (amendment, focusBackTo) => {
      const { text, ...amendable } = amendment

      // Prefill with original text
      if (amendment.type === 'deletion') {
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

      // Open modal
      this.focusBackTo = focusBackTo
      this.open()
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

    open() {
      this.shown = true
      document.body.classList.add('drawer-shown')
      this.$nextTick(() => {
        if (this.$refs.text) {
          this.$refs.text.focus()
        } else if (this.$refs.justification) {
          this.$refs.justification.focus()
        }
      })
    },

    close() {
      this.shown = false
      document.body.classList.remove('drawer-shown')
      if (this.focusBackTo) {
        document.querySelector(this.focusBackTo).focus()
      }
    },
  },
}
</script>

<style lang="scss">
.amendable-form {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 11000;
  width: 100%;
  max-width: min(calc(100vw - 100px), 1000px);
  background: var(--white);
  overflow-y: auto;
  border-top: 1px var(--gray-300) solid;

  --form-padding: 1rem;

  &-backdrop {
    position: fixed;
    z-index: 10000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba($black, 0.75);
  }

  .close {
    position: absolute;
    top: 0;
    right: 0;
    appearance: none;
    background: transparent;
    padding: 1rem 1.5rem;
    color: var(--text-muted);
    border: 0;
    transition: 0.25s ease;

    &:hover {
      color: var(--text-color);
    }
  }

  .submit-button {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
  }
}

.slide-in-enter-active .amendable-form-backdrop,
.slide-in-leave-active .amendable-form-backdrop {
  transition: opacity 0.5s;
}

.slide-in-enter-active,
.slide-in-leave-active {
  transition: 0.5s;
}

.slide-in-enter .amendable-form-backdrop,
.slide-in-leave-to .amendable-form-backdrop {
  opacity: 0;
}

.slide-in-enter-active .amendable-form,
.slide-in-leave-active .amendable-form {
  transition: 0.5s;
}

.slide-in-enter .amendable-form,
.slide-in-leave-to .amendable-form {
  transform: translateX(100%);
}
</style>
