<template>
  <div class="amendment-page">
    <div class="container pt-4">
      <div v-if="amendment" class="amendment-item">
        <div class="amendment-header d-flex mb-3">
          <h4 class="mb-0">Esmena #{{ amendment.num }}</h4>
          <amendment-status :status="amendment.status" class="ms-2" />
        </div>
        <b-card type="outline" padded size="sm">
          <b-tab-list size="sm" muted focus-dark>
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
                :after="amendment.amended"
              />
            </div>
            <div v-if="activeTab === 'amended'">
              <pre
                class="text-sans-serif text-lg"
                v-html="amendment.amended"
              ></pre>
            </div>
            <div v-if="activeTab === 'original'">
              <pre
                class="text-sans-serif text-lg"
                v-html="amendment.original"
              ></pre>
            </div>
          </div>
        </b-card>
        <div class="amendment-justification mt-3">
          <div class="text-sm">Justificació</div>
          <p>{{ amendment.justification }}</p>
        </div>
        <div class="amendment-author mt-3">
          <div class="text-sm">Presentada per</div>
          {{ amendment.registered_by }}
        </div>
        <div
          v-if="amendment.registered_by_assembly"
          class="amendment-assembly mt-3"
        >
          <div class="text-sm">Aprovada en assemblea</div>
          {{ amendment.registered_by_assembly }}
        </div>
        <div v-if="amendment.supports" class="amendment-supports mt-3">
          <div class="text-sm">Llista de suports</div>
          <b-badge-list>
            <b-badge
              v-for="support in amendment.supports"
              :key="support.id"
              size="sm"
              variant="supermuted"
            >
              {{ support.user.name }} {{ support.user.last_name }}
            </b-badge>
          </b-badge-list>
        </div>
        <support-amendment :amendment="amendment" full-width class="mt-4" />
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
  },
}
</script>

<style lang="scss">
.amendment-page {
  background: var(--white);
}
</style>
