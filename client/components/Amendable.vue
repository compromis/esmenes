<template>
  <article :id="article" class="amendable">
    <div
      :class="['amendable-content', { hovering, 'is-amendable': canBeAmended }]"
      @click="
        canBeAmended ? amendText('modification', `#art${article}-edit`) : null
      "
    >
      <component
        :is="hTag"
        v-if="title"
        :id="indexId"
        class="text-regular mb-3"
      >
        {{ indexTitle }}
      </component>
      <div :id="'text' + indexId" ref="text" class="amendable-text">
        <slot />
      </div>
    </div>
    <div class="amendable-actions">
      <div v-if="canBeAmended" class="amendable-actions-sticky py-3">
        <circly-button
          :id="`art${article}-edit`"
          class="edit-button mb-lg-3"
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

    canBeAmended() {
      return this.$store.state.assembly.assembly.amendments_open
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
@import '../assets/scss/_bootstrap-mixins.scss';

.amendable {
  display: grid;
  grid-template-columns: 1fr 12.5rem;
  gap: 0.5rem 1rem;

  &-content {
    border-radius: 0.75rem;
    padding: 1rem;
    transition: 0.25s ease;

    &.is-amendable {
      cursor: pointer;

      &:hover,
      &.hovering {
        background: #fff5d0;
      }
    }
  }

  &-text {
    & > *:last-child {
      margin-bottom: 0;
    }

    li {
      margin-bottom: 0.5rem;
    }

    /* Second degree lists */
    & > ol > li > ol > li {
      list-style: lower-latin;
    }
  }

  &-actions {
    &-sticky {
      position: sticky;
      top: calc(var(--navbar-height) + 1rem);
    }
  }
}

@include media-breakpoint-down(lg) {
  .amendable {
    grid-template-columns: 1fr;
    gap: 0;

    &-actions-sticky {
      display: flex;
      gap: 1rem;
      align-items: center;
      padding-left: 1rem;
    }

    &-submissions {
      padding: 0 1rem;
    }
  }
}
</style>
