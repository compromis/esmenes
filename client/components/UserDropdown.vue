<template>
  <div v-if="user" class="dropdown-trigger">
    <b-button class="user-button" size="sm" variant="default">
      <span class="d-none d-md-inline">{{ user.name }}</span>
      <font-awesome-icon
        :icon="['fal', 'user-circle']"
        size="lg"
        class="ms-1"
      />
    </b-button>
    <b-card as="ul" class="dropdown-items text-sm" size="sm">
      <li>
        <nuxt-link to="/logout">
          <font-awesome-icon
            :icon="['fal', 'sign-out']"
            size="lg"
            class="me-1"
          />
          Tanca sessió
        </nuxt-link>
      </li>
    </b-card>
  </div>
</template>

<script>
export default {
  computed: {
    user() {
      return this.$store.state.auth.user
    },
  },
}
</script>

<style lang="scss" scoped>
.user-button {
  padding: 0.25rem 0.5rem;
  margin-right: -0.5rem;
}

.dropdown-items {
  padding: 0;
  list-style: none;
  visibility: hidden;
  opacity: 0;
  transform: scale(0.95);
  position: absolute;
  display: block;
  top: 3.25rem;
  right: 0.5rem;
  transition: 0.15s ease-in-out;

  a {
    display: block;
    color: var(--b-black);
    overflow: hidden;
    padding: 0.5rem 0.75rem;
    transition: 0.15s ease-in-out;

    &:hover {
      text-decoration: none;
      color: var(--b-black);
      background: var(--b-gray-100);
    }
  }

  li {
    padding: 0;

    &:first-child a {
      border-top-right-radius: var(--b-border-radius);
      border-top-left-radius: var(--b-border-radius);
    }

    &:last-child a {
      border-bottom-right-radius: var(--b-border-radius);
      border-bottom-left-radius: var(--b-border-radius);
    }
  }

  i {
    width: 16px;
    height: 16px;
  }
}

.dropdown-trigger:hover,
.dropdown-trigger:focus-within {
  .dropdown-items {
    visibility: visible;
    opacity: 1;
    transform: scale(1);
  }
}
</style>
