<template>
  <div class="document-wrapper">
    <nav class="document-table">
      <div class="document-table-wrapper">
        <document-toc :toc="toc" />
      </div>
    </nav>
    <div class="document-content">
      <div class="document-title">
        <h1 class="text-regular ps-3 mb-3">{{ document.title }}</h1>
        <div v-if="canBeAmended">
          <circly-button
            id="general-amendment"
            icon="plus"
            @click="generalAmendment"
          >
            Esmena general
          </circly-button>
        </div>
        <amendment-list :amendments="generalAmendments" />
      </div>
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
  },

  watch: {
    '$route.params.doc'(ref) {
      this.fetchAmendments()
      this.$nextTick(() => {
        this.$nextTick(() => {
          this.toc = this.createToc(this.$refs[`content_${ref}`].$children)
          this.setDocument()
        })
      })
    },
  },

  mounted() {
    // Set document in store
    this.setDocument()

    // Fetch document amendments
    this.fetchAmendments()

    // Create table of contents
    this.toc = this.createToc(this.$refs[`content_${this.doc}`].$children)

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
        if (component.isIndexable) {
          toc.push({
            id: component.indexId,
            article: component.article,
            title: component.indexTitle,
            children: this.createToc(component.$children),
          })
        }
      })

      return toc
    },
  },
}
</script>

<style lang="scss">
.document {
  &-wrapper {
    display: grid;
    grid-template-columns: 18.75rem 1fr;
  }

  &-content {
    display: block;
    width: 100%;
    max-width: 1000px;
    margin: 2rem auto;

    h3 {
      font-weight: 500;
      margin: 1.75rem 1rem 0.5rem 1rem;
    }
  }

  &-table {
    &-wrapper {
      padding: 2rem 1rem;
      position: sticky;
      top: var(--navbar-height);
      height: calc(100vh - var(--navbar-height));
      overflow-y: auto;
    }
  }

  &-title {
    display: grid;
    grid-template-columns: 1fr 12.5rem;
    gap: 0.5rem 1rem;
  }

  .nuxt-content {
    font-weight: 400;
  }
}
</style>
