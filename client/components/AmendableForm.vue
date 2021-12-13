<template>
  <transition name="slide-in">
    <div v-if="shown" class="amendable-form-wrapper">
      <div class="amendable-form">
        <button class="close" @click="close">
          <font-awesome-icon :icon="['fal', 'times']" size="lg" />
        </button>
        <div v-if="submitted" class="success">
          <font-awesome-icon
            :icon="['fal', 'check-circle']"
            class="icon mb-3"
          />
          <h2 class="text-4xl text-regular mb-4">Esmena presentada</h2>
          <p class="text-xl">La teua esmena s'ha enregistrat correctament.</p>
          <p class="text-xl">
            Pots arreplegar signatures amb el següent enllaç:
          </p>
          <div class="mt-4">
            <b-button href="`/`" class="me-2">Ves a l'esmena -&gt;</b-button>
            <b-button variant="inverted" @click="close">
              Torna al document
            </b-button>
          </div>
        </div>
        <form v-else class="form" @submit.prevent="submitAmendment">
          <h2 class="text-3xl text-regular p-4">Presenta esmena</h2>
          <div v-if="amendable">
            <div class="pb-3 px-4">
              <span v-if="amendable.type === 'deletion'">Suprimeix:</span>
              <span v-if="amendable.type === 'modification'">Esmena a:</span>
              <span v-if="amendable.type === 'addition'">Afegeix a:</span>
              <strong>{{ amendable.title }}</strong>
            </div>
            <div class="form-field form-field-cols border-top">
              <div v-if="amendable.type !== 'deletion'" class="form-field-col">
                <amendable-input
                  ref="text"
                  v-model="amendable.text"
                  name="text"
                  type="textarea"
                  variant="float"
                  label="Esmena"
                  class="text-input"
                  :error="errorMessage('text')"
                />
              </div>
              <div
                v-if="amendable.type !== 'addition'"
                class="form-field-col faux-input border-left"
              >
                <div class="faux-label">Modificacions</div>
                <pre class="preview" v-html="preview" />
              </div>
            </div>
            <div class="form-field">
              <amendable-input
                ref="justification"
                v-model="form.justification"
                name="justification"
                type="textarea"
                variant="float"
                label="Justificació"
                :error="errorMessage('justification')"
              />
            </div>
            <div class="faux-input border-bottom">
              <div class="faux-label">Presentada per</div>
              <div class="faux-value">{{ user.name }} {{ user.last_name }}</div>
            </div>
            <div
              v-if="canRegisterAsAssembly"
              class="faux-input border-bottom px-4 py-3"
            >
              <div class="form-check form-switch">
                <input
                  id="registerAsAssembly"
                  v-model="form.registered_by_assembly"
                  class="form-check-input"
                  type="checkbox"
                />
                <label class="form-check-label" for="registerAsAssembly">
                  Aprovada per l'assemblea de
                  <strong>{{ user.data.comarca }}</strong>
                </label>
              </div>
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
          this.$refs.text.$refs.input.focus()
        } else if (this.$refs.justification) {
          this.$refs.justification.$refs.input.focus()
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
    errorMessage(field) {
      return field in this.errors ? this.errors[field][0] : ''
    },
  },
}
</script>

<style lang="scss">
.amendable-form {
  display: flex;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 11000;
  width: 100%;
  max-width: min(calc(100vw - 100px), 1000px);
  background: var(--white);
  overflow-y: auto;

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
    padding: 2rem 1.5rem;
    color: var(--text-muted);
    border: 0;
    transition: 0.25s ease;

    &:hover {
      color: var(--text-color);
    }
  }
}

.text-input {
  height: 100%;
}

.form {
  flex-grow: 1;
}

.form-field {
  border-bottom: 1px var(--gray-200) solid;

  &-cols {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    min-height: 50vh;
  }
}

.preview {
  font-family: $font-family-sans-serif;
  font-size: 1rem;
  padding: 0 1.5rem 0.5rem 1.5rem;

  ins {
    background: mix($green, $white, 25%);
    text-decoration: none;
  }

  del {
    background: mix($red, $white, 25%);
  }
}

.faux-input {
  .faux-label {
    padding: 1rem 1.5rem 0.5rem 1.5rem;
    font-size: 0.875rem;
    color: var(--text-muted);
  }

  .faux-value {
    font-size: 1rem;
    padding: 0 1.5rem 1rem 1.5rem;
  }
}

.success {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  flex-grow: 1;
  flex-direction: column;

  .icon {
    color: $green;
    font-size: 5rem;
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
