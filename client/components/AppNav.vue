<template>
  <nav :class="['app-nav', { scrolled }]">
    <div class="nav-logo">
      <a href="https://compromis.net" class="nav-logo-compromis">
        <compromis-logo :collapsible="scrolled ? 'always' : 'sm'" />
      </a>
      <div class="nav-logo-append">
        <transition mode="out-in" name="fade">
          <router-link
            v-if="!assembly || !scrolled"
            ref="general"
            to="/assemblies"
          >
            Esmenes
          </router-link>
          <div
            v-else-if="assembly"
            ref="assembly"
            class="d-flex align-items-center"
          >
            <router-link :to="`/${assembly.ref}`" class="d-none d-lg-inline">
              {{ assembly.name }}
            </router-link>
            <span
              v-if="document && document.slug"
              class="ms-2 d-none d-lg-inline"
            >
              &gt;
            </span>
            <a v-if="document && document.slug" href="#top">
              {{ document.title }}
            </a>
          </div>
        </transition>
      </div>
    </div>
    <div class="nav-user">
      <user-dropdown />
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      scrolled: false,
    }
  },

  computed: {
    assembly() {
      return this.$store.state.assembly.assembly
    },

    document() {
      return this.$store.state.assembly.document
    },
  },

  mounted() {
    window.addEventListener('scroll', this.handleScroll)
  },

  methods: {
    handleScroll() {
      this.scrolled = window.scrollY > 0
    },
  },
}
</script>

<style lang="scss" scoped>
.app-nav {
  display: flex;
  align-items: center;
  position: fixed;
  background: var(--b-white);
  height: var(--navbar-height, 3rem);
  padding: 0 1rem;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;

  .nav-logo {
    svg {
      height: 1.75rem;
    }

    &-append {
      font-size: 1.25em;
      color: var(--b-text-muted);
    }
  }

  .nav-user {
    margin-left: auto;
  }

  &.scrolled {
    border-bottom: 1px var(--b-gray-300) solid;
  }
}
</style>
