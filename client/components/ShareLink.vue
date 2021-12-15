<template>
  <div class="share-link text-sm text-muted">
    <input
      class="copy-url"
      type="text"
      :value="url"
      no-label
      @focus="$event.target.select()"
    />
    <b-button
      :class="['mr-0']"
      :variant="copied ? 'secondary' : 'muted'"
      outline
      size="sm"
      @click="copyUrl"
    >
      <template v-if="!copied">
        <font-awesome-icon :icon="['fal', 'clipboard']" />
        <span class="visually-hidden">Copia</span>
      </template>
      <template v-else-if="copied">
        <font-awesome-icon :icon="['fal', 'check']" />
        <span class="visually-hidden">Copiat</span>
      </template>
    </b-button>
  </div>
</template>

<script>
export default {
  props: {
    amendmentRef: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      copied: false,
    }
  },

  computed: {
    url() {
      const { ref: assembly } = this.$store.state.assembly.assembly
      const { slug: document } = this.$store.state.assembly.document
      return `https://esmenes.compromis.net/${assembly}/${document}/${this.amendmentRef}`
    },
  },

  methods: {
    copyUrl() {
      navigator.clipboard.writeText(this.url)
      this.copied = true
      setTimeout(() => {
        this.copied = false
      }, 4000)
    },
  },
}
</script>

<style lang="scss" scoped>
.share-link {
  display: flex;

  .copy-url {
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem 0 0 0.5rem;
    padding: 0 0.5rem;
    color: inherit;
    width: 100%;

    &:focus {
      @include focus-shadow($primary);
    }
  }

  .button {
    border-width: 1px;
    border-radius: 0 0.5rem 0.5rem 0;
    margin-left: -1px;
  }
}
</style>
