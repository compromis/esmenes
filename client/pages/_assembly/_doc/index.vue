<template>
  <div class="document-wrapper">
    <nav class="document-table">
      <ul>
        <li>asfas</li>
        <li>adgasgas</li>
      </ul>
    </nav>
    <div class="document-content">
      <div class="document-title">
        <h1 class="text-regular">{{ document.title }}</h1>
        <circly-button @click="generalAmendment">Esmena general</circly-button>
      </div>
      <amendment-list :amendments="generalAmendments" />
      <nuxt-content ref="content" :document="document" />
      <amendable-form />
    </div>
  </div>
</template>

<script>
import CirclyButton from '@/components/CirclyButton'

export default {
  components: {
    CirclyButton,
  },
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
  },

  watch: {
    '$route.params.doc'() {
      this.fetchAmendments()
    },
  },

  mounted() {
    // Fetch document amendments
    this.fetchAmendments()

    // Add amendment if user submitted one
    this.$root.$on('amendmentSubmitted', (amendment) => {
      this.$store.commit('assembly/addAmendment', amendment)
    })

    // Create toc
    this.toc = this.createToc(this.$refs.content.$children)
  },

  methods: {
    async fetchAmendments() {
      const { assembly, doc } = this.$route.params
      const amendments = await this.$api.amendments(assembly, doc)
      this.$store.commit('assembly/setAmendments', amendments)
    },

    generalAmendment() {
      this.$root.$emit('amendText', {
        article: 'general',
        title: this.document.title,
        type: 'addition',
        text: '',
      })
    },

    createToc(components) {
      const toc = []
      components.forEach((component) => {
        if (component.isIndexable) {
          toc.push({
            id: component.indexId,
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
    grid-template-columns: 0.2fr 1fr;
  }

  &-content {
    display: block;
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    height: 4000px;
  }

  &-table {
    //top: calc(var(--navbar-height, 2rem) + var(--header-height, 6rem));
    ul {
      position: sticky;
      top: 3rem;
    }
  }

  &-title {
    display: grid;
    grid-template-columns: 1fr 0.2fr;
  }

  .nuxt-content {
    font-weight: 400;
  }
}
</style>
