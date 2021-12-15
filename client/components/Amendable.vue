<template>
  <article :id="article" class="amendable">
    <div
      :class="['amendable-content', { hovering }]"
      @click="amendText('modification', `#art${article}-edit`)"
    >
      <component
        :is="hTag"
        v-if="title"
        :id="indexId"
        class="text-regular mb-0"
      >
        {{ indexTitle }}
      </component>
      <div :id="'text' + indexId" ref="text" class="amendable-text">
        <slot />
      </div>
    </div>
    <div class="amendable-actions">
      <div class="amendable-actions-sticky py-3">
        <circly-button
          :id="`art${article}-edit`"
          class="edit-button mb-3"
          :aria-describedby="'text' + indexId"
          @click="amendText('modification', `#art${article}-edit`)"
          @mouseover="hovering = true"
          @mouseout="hovering = false"
        >
          Modifica
        </circly-button>
        <circly-button
          :id="`art${article}-delete`"
          class="delete-button"
          icon="trash"
          :aria-describedby="'text' + indexId"
          @click="amendText('deletion', `#art${article}-delete`)"
          @mouseover="hovering = true"
          @mouseout="hovering = false"
        >
          Suprimeix
        </circly-button>
      </div>
    </div>
    <div class="amendable-submissions">
      <amendment-list :amendments="amendments" />
    </div>
  </article>
</template>

<script>
import TurndownService from 'turndown'

export default {
  isIndexable: true,

  props: {
    article: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      default: null,
    },
    level: {
      type: [String, Number],
      default: 4,
    },
    isIndexable: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      hovering: false,
    }
  },

  computed: {
    hTag() {
      return 'h' + this.level
    },

    indexId() {
      return 'Art' + this.article
    },

    indexTitle() {
      return `Article ${this.article}. ${this.title || ''}`
    },

    context() {
      return this.$parent.title || ''
    },

    amendments() {
      return this.article in this.$store.state.assembly.amendments
        ? this.$store.state.assembly.amendments[this.article]
        : []
    },
  },

  methods: {
    amendText(type, focusBackTo) {
      const { article } = this
      const title = this.title ? this.indexTitle : this.context
      const html = this.$refs.text.innerHTML
      const turndownService = new TurndownService({
        bulletListMarker: '-',
        strongDelimiter: '',
        emDelimiter: '',
      })
      const text = turndownService.turndown(html).replaceAll('-   ', '- ')
      this.$root.$emit('amendText', { article, title, text, type }, focusBackTo)
    },
  },
}
</script>

<style lang="scss">
.amendable {
  display: grid;
  grid-template-columns: 1fr 12.5rem;
  gap: 0.5rem 1rem;

  &-content {
    border-radius: 0.75rem;
    padding: 1rem;
    transition: 0.25s ease;
    cursor: pointer;

    &:hover,
    &.hovering {
      background: #fff5d0;
    }
  }

  &-text {
    & > *:last-child {
      margin-bottom: 0;
    }
  }

  &-actions {
    &-sticky {
      position: sticky;
      top: calc(var(--navbar-height) + 1rem);
    }
  }
}
</style>
