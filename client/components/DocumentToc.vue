<template>
  <ul class="toc">
    <li
      v-for="item in toc"
      :key="item.id"
      :class="{ 'show-children': showChildren }"
    >
      <font-awesome-icon
        v-if="item.children.length"
        class="chevron"
        :icon="['fal', 'chevron-right']"
      />
      <a
        :href="`#${item.id}`"
        class="link-muted-to-black"
        @click="handleClick(item, $event)"
      >
        {{ item.title }}
      </a>
      <transition name="slide">
        <div v-show="showChildren" class="children">
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

  methods: {
    handleClick(item, e) {
      if (item.children.length) {
        e.preventDefault()
        this.showChildren = !this.showChildren
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
  color: var(--text-muted);

  .chevron {
    transition: 0.25s ease;
    margin-right: 0.25rem;
  }

  .show-children {
    .chevron {
      transform: rotate(90deg);
    }

    & > a {
      font-weight: bold;
    }
  }

  .children .toc {
    margin: 0.5rem 0 1rem 1rem;
  }
}
</style>
