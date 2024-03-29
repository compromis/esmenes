<template>
  <div class="document-wrapper">
    <div class="document-title">
      <h1 class="text-regular ps-3 mb-3">
        {{ document.title }}
        <b-button
          v-if="document.pdf"
          :href="document.pdf"
          variant="supermuted"
          target="_blank"
          size="sm"
          class="pdf ms-2 py-2 px-3"
        >
          <font-awesome-icon :icon="['fal', 'file-download']" class="me-1" />
          PDF
        </b-button>
      </h1>
      <div v-if="canBeAmended" class="mb-3 mb-lg-0 mt-lg-2 ms-3 ms-lg-0">
        <circly-button
          id="general-amendment"
          icon="plus"
          @click="generalAmendment"
        >
          Esmena general
        </circly-button>
      </div>
      <div v-else></div>
      <amendment-list
        :amendments="generalAmendments"
        class="mx-3 mx-xl-0 mt-3 mt-xl-0"
      />
    </div>
    <nav class="document-table">
      <div class="document-table-wrapper">
        <document-toc :toc="toc" />
      </div>
    </nav>
    <div class="document-content">
      <nuxt-content :ref="`content_${doc}`" :document="document" />
      <amendable-form />
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  async asyncData({ $content, params }) {
    const { assembly, doc } = params
    const document = await $content(assembly + '/docs/' + doc).fetch()

    return { assembly, doc, document }
  },

  data() {
    return {
      toc: [],
    }
  },

  computed: {
    generalAmendments() {
      const amendments = this.$store.state.assembly.amendments
      return amendments.general || []
    },

    canBeAmended() {
      return this.$store.state.assembly.assembly.amendments_open
    },

    tocComponents() {
      return process.env.NODE_ENV === 'production'
        ? this.$children
        : this.$refs[`content_${this.doc}`].$children
    },
  },

  watch: {
    '$route.params.doc'() {
      this.fetchAmendments()
      this.$nextTick(() => {
        this.$nextTick(() => {
          this.setDocument()
        })
      })

      setTimeout(() => {
        this.toc = this.createToc(this.tocComponents)
      }, 1000)
    },
  },

  mounted() {
    // Set document in store
    this.setDocument()

    // Fetch document amendments
    this.fetchAmendments()

    // Create table of contents
    setTimeout(() => {
      this.toc = this.createToc(this.tocComponents)
    }, 1000)

    // Add amendment if user submitted one
    this.$root.$on('amendmentSubmitted', (amendment) => {
      this.$store.commit('assembly/addAmendment', amendment)
    })
  },

  methods: {
    async fetchAmendments() {
      const { assembly, doc } = this.$route.params
      const amendments = await this.$api.amendments(assembly, doc)
      this.$store.commit('assembly/setAmendments', amendments)
    },

    generalAmendment() {
      this.$root.$emit(
        'amendText',
        {
          article: 'general',
          title: this.document.title,
          type: 'addition',
          text: '',
        },
        '#general-amendment'
      )
    },

    setDocument() {
      // Set current document in the store
      const { slug, title } = this.document
      this.$store.commit('assembly/setDocument', { slug, title })
    },

    createToc(components) {
      const toc = []
      components.forEach((component) => {
        if ('isIndexable' in component) {
          toc.push({
            id: component.indexId,
            article: component.article,
            title: component.indexTitle,
            children: this.createToc(component.$children),
            isIndexable: component.isIndexable,
            isAmendable: component.isAmendable,
            amendments: component.amendments ? component.amendments.length : 0,
          })
        }
      })

      return toc
    },
  },
}
</script>

<style lang="scss">
@import '../../../assets/scss/_bootstrap-mixins.scss';

.document {
  &-wrapper {
    display: grid;
    grid-template-columns: 18.75rem 1fr;
    grid-template-rows: auto 1fr;
    column-gap: 1rem;
  }

  &-title {
    display: grid;
    grid-template-columns: 1fr 12.5rem;
    gap: 0.5rem 1rem;
    grid-column: 2;
    max-width: 1000px;
    width: 100%;
    margin: 0 auto;
    margin-top: 3rem;

    .pdf {
      transform: translateY(-0.25rem);
      letter-spacing: 0.03em;
    }
  }

  &-content {
    display: block;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;

    h3 {
      font-weight: 500;
      margin: 1.75rem 1rem 0.5rem 1rem;
    }
  }

  &-table {
    grid-column: 1;
    grid-row: 1 / span 2;
    border-right: 1px var(--gray-300) solid;

    &-wrapper {
      padding: 1rem;
      position: sticky;
      top: var(--navbar-height);
      height: calc(100vh - var(--navbar-height));
      overflow-y: auto;

      @include scrollbar();
    }
  }

  .nuxt-content {
    font-weight: 400;
  }
}

@include media-breakpoint-down(lg) {
  .document {
    &-wrapper {
      grid-template-columns: 1fr;
    }

    &-title {
      grid-template-columns: 1fr;
      gap: 0;
      grid-row: 1;
      grid-column: 1;

      .circly-button {
        padding-left: 1rem;
      }
    }

    &-content {
      margin: 0;
    }

    &-table {
      grid-row: 2;
      grid-column: 1;
      border-right: 0;
    }

    &-table-wrapper {
      height: auto;
      border: 1px solid var(--gray-300);
      padding: 0.5rem 1rem;
      margin: 0 1rem;
      border-radius: 0.75rem;

      &::before {
        content: 'Taula de continguts';
        display: block;
        font-size: $text-sm;
        color: var(--gray-700);
        margin: 0.25rem 0 0.75rem;
      }
    }
  }
}
</style>
