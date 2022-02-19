<template>
  <div class="amendment-page text-lg">
    <div class="container pt-4">
      <div v-if="amendment" class="amendment-item">
        <div class="amendment-header d-flex mb-3">
          <h4 class="mb-0">
            {{
              amendment.custom_num
                ? amendment.custom_num
                : `Esmena #${amendment.num}`
            }}
          </h4>
          <amendment-status :status="amendment.status" class="ms-2" />
        </div>
        <b-card type="outline" padded size="sm">
          <h5 class="mb-2">{{ amendment.title }}</h5>
          <b-tab-list size="sm" muted focus-dark class="my-3">
            <b-tab
              :selected="activeTab === 'comparison'"
              @click="activeTab = 'comparison'"
            >
              Comparació
            </b-tab>
            <b-tab
              :selected="activeTab === 'original'"
              @click="activeTab = 'original'"
            >
              Text original
            </b-tab>
            <b-tab
              :selected="activeTab === 'amended'"
              @click="activeTab = 'amended'"
            >
              Text esmenat
            </b-tab>
          </b-tab-list>
          <div class="amendment-tabs-content mt-1 text-lg text-base">
            <div v-if="activeTab === 'comparison'">
              <amendment-comparison
                class="text-lg"
                :before="amendment.original"
                :after="amendment.compromise || amendment.amended"
              />
            </div>
            <div v-if="activeTab === 'amended'">
              <div
                class="text-sans-serif text-lg"
                v-html="formatHtml(amendment.compromise || amendment.amended)"
              ></div>
            </div>
            <div v-if="activeTab === 'original'">
              <div
                class="text-sans-serif text-lg"
                v-html="formatHtml(amendment.original)"
              ></div>
            </div>
          </div>
        </b-card>
        <div class="amendment-justification mt-3 d-none">
          <div class="text-md text-muted">Justificació</div>
          <p>{{ amendment.justification }}</p>
        </div>
        <div
          v-if="amendment.custom_registered_by"
          class="amendment-author mt-3"
        >
          <div class="text-md text-muted">Presentada per</div>
          {{ amendment.custom_registered_by }}
        </div>
        <div
          v-else-if="amendment.registered_by_assembly"
          class="amendment-assembly text-muted mt-3"
        >
          <div class="text-md">Aprovada en assemblea</div>
          {{ amendment.registered_by_assembly }}
        </div>
        <div v-else class="amendment-author mt-3">
          <div class="text-md text-muted">Presentada per</div>
          {{ amendment.registered_by }}
        </div>
        <div v-if="amendment.supports.length" class="amendment-supports mt-3">
          <div class="text-md text-muted">
            Llista de suports ({{ amendment.supports.length }} de 15 signatures
            necessàries)
          </div>
          <b-badge-list>
            <b-badge
              v-for="support in amendment.supports"
              :key="support.id"
              size="md"
              variant="supermuted"
            >
              {{ support.user.name }} {{ support.user.last_name }}
            </b-badge>
          </b-badge-list>
        </div>
        <support-amendment :amendment="amendment" full-width class="mt-3" />
        <div class="mt-3">
          <div class="text-md">Comparteix l'esmena</div>
          <share-link :amendment-ref="amendment.ref" />
        </div>
      </div>
      <div v-else>Carregant...</div>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  async asyncData({ $content, store, params }) {
    // Set document
    const { assembly, doc } = params
    const { slug, title } = await $content(assembly + '/docs/' + doc).fetch()
    store.commit('assembly/setDocument', { slug, title })
  },

  data() {
    return {
      amendment: null,
      activeTab: 'comparison',
    }
  },

  async mounted() {
    // Fetch amendment with signatures
    const { assembly, amendment } = this.$route.params
    this.amendment = await this.$api.amendment(assembly, amendment)

    // Refresh when user supports amendment
    this.$root.$on('supportedAmendment', async () => {
      this.amendment = await this.$api.amendment(assembly, amendment)
    })
  },

  methods: {
    formatHtml(text) {
      if (!text) return '(sense text)'

      return String(text)
        .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2')
        .replaceAll('  ', '&nbsp;&nbsp;')
    },
  },
}
</script>

<style lang="scss">
.amendment-page {
  background: var(--white);

  .container {
    max-width: 1000px;
    margin: 0 auto;
  }
}
</style>
