<template>
  <ul class="toc">
    <li
      v-for="item in tocWithAmendments"
      :key="item.id"
      :class="{ 'show-children': showChildren === item.id }"
    >
      <a
        :href="`#${item.id}`"
        class="link-muted-to-black"
        @click="handleClick(item, $event)"
      >
        <font-awesome-icon
          v-if="item.children.length"
          class="chevron me-2"
          :icon="['fal', 'chevron-right']"
        />
        <font-awesome-icon v-else class="me-2" :icon="['fal', 'file-alt']" />
        <span class="title">{{ item.title }}</span>
        <span
          v-if="item.amendments > 0"
          class="amendments"
          :title="`${item.amendments} esmenes`"
        >
          {{ item.amendments }}
        </span>
      </a>
      <transition name="slide">
        <div v-show="showChildren === item.id" class="children">
          <document-toc :toc="item.children" />
        </div>
      </transition>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    toc: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      showChildren: false,
    }
  },

  computed: {
    tocWithAmendments() {
      return this.toc.map((item) => {
        if (item.article) {
          const amendments = this.$store.state.assembly.amendments[item.article]
          item.amendments = amendments ? amendments.length : 0
        }

        return item
      })
    },
  },

  methods: {
    handleClick(item, e) {
      if (item.children.length) {
        e.preventDefault()
        if (this.showChildren !== item.id) {
          this.showChildren = item.id
        } else {
          this.showChildren = false
        }
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.toc {
  list-style: none;
  margin: 0;
  padding: 0;
  color: var(--b-text-muted);

  a {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    margin: 0 -0.5rem;
    border-radius: 0.25rem;

    &:hover {
      text-decoration: none;

      .title {
        text-decoration: underline;
        text-decoration-color: var(--b-gray-400);
      }
    }

    &:focus {
      background: var(--b-gray-100);
      box-shadow: none !important;
    }
  }

  .amendments {
    display: inline-flex;
    background: $gray-500;
    color: $white;
    width: 1.5em;
    height: 1.5em;
    font-size: 0.75em;
    border-radius: 1em;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-left: 0.25rem;
  }

  .chevron {
    transition: 0.25s ease;
  }

  .show-children {
    & > a {
      font-weight: bold;

      & > .chevron {
        transform: rotate(90deg);
      }
    }
  }

  .children .toc {
    margin: 0 0 1rem 1.25rem;
  }
}
</style>
