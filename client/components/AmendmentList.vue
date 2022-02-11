<template>
  <b-card v-if="amendments.length > 0" type="outline" size="sm" class="mb-4">
    <button
      class="card-padded amendments-button"
      @click="displayAmendments = !displayAmendments"
    >
      <div class="me-auto">{{ amendments.length }} {{ amendmentWord }}</div>
      <div>
        <font-awesome-icon
          :icon="['fal', 'chevron-right']"
          size="lg"
          :class="[
            'amendments-icon',
            { 'amendments-icon-open': displayAmendments },
          ]"
          fixed-width
        />
      </div>
    </button>
    <transition name="slide">
      <b-card-list v-show="displayAmendments">
        <li v-for="amendment in amendments" :key="amendment.id">
          <div class="amendment-header d-flex mb-2">
            <div class="me-auto d-lg-flex align-items-center align-self-start">
              <h5 class="m-0">
                <nuxt-link
                  :to="linkBase + amendment.ref"
                  class="link-muted-to-black"
                >
                  {{
                    amendment.custom_num
                      ? amendment.custom_num
                      : `Esmena #${amendment.num}`
                  }}
                </nuxt-link>
              </h5>
              <amendment-status
                :status="amendment.status"
                class="ms-lg-2 mt-2 mt-lg-0"
              />
            </div>
            <support-amendment :amendment="amendment" class="ms-2" />
          </div>
          <div class="amendment-content">
            <div class="text-sm">Text esmenat</div>
            <amendment-comparison
              class="mb-3 text-md"
              :before="amendment.original"
              :after="amendment.compromise || amendment.amended"
            />
          </div>
          <div class="amendment-justification d-none">
            <div class="text-sm">Justificació</div>
            <p>{{ amendment.justification }}</p>
          </div>
          <div v-if="amendment.custom_registered_by" class="amendment-author">
            <div class="text-sm">Presentada per</div>
            {{ amendment.custom_registered_by }}
          </div>
          <div
            v-else-if="amendment.registered_by_assembly"
            class="amendment-assembly mt-2"
          >
            <div class="text-sm">Aprovada en assemblea</div>
            {{ amendment.registered_by_assembly }}
          </div>
          <div v-else class="amendment-author">
            <div class="text-sm">Presentada per</div>
            {{ amendment.registered_by }}
          </div>
        </li>
      </b-card-list>
    </transition>
  </b-card>
</template>

<script>
export default {
  props: {
    amendments: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      displayAmendments: false,
    }
  },

  computed: {
    amendmentWord() {
      return this.amendments.length === 1 ? 'esmena' : 'esmenes'
    },
    linkBase() {
      const { assembly, document } = this.$store.state.assembly
      return `/${assembly.ref}/${document.slug}/`
    },
  },
}
</script>

<style lang="scss" scoped>
.amendments-button {
  color: var(--gray-600);
  display: flex;
  width: 100%;
  text-align: left;
  appearance: none;
  border: none;
  background: none;
}

.amendments-icon {
  transition: 0.25s;

  &-open {
    transform: rotate(90deg);
  }
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.4s;
  max-height: 800px;
  overflow: hidden;
}

.slide-enter,
.slide-leave-to {
  max-height: 0;
  overflow: hidden;
}
</style>
