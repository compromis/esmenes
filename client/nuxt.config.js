export default {
  // Target: https://go.nuxtjs.dev/config-target
  target: 'server',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Compromís - Esmenes',
    htmlAttrs: {
      lang: 'ca',
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
    ],
    link: [
      {
        rel: 'icon',
        type: 'image/x-icon',
        href: 'https://compromis.net/sub/icons/favicon.ico',
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '32x32',
        href: 'https://compromis.net/sub/icons/favicon32.png',
      },
      {
        rel: 'icon',
        type: 'image/png',
        sizes: '192x192',
        href: 'https://compromis.net/sub/icons/favicon192.png',
      },
      {
        rel: 'stylesheet',
        href: 'https://compromis.net/sub/fonts/manrope.css?variable',
      },
    ],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ['@compromis/blobby/scss/blobby.scss', '~/assets/scss/global.scss'],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '~/plugins/blobby.js',
    '~/plugins/persistedstate.js',
    '~/plugins/api.js',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module',
    // https://go.nuxtjs.dev/stylelint
    '@nuxtjs/stylelint-module',
    '@nuxtjs/fontawesome',
    '@nuxtjs/style-resources',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    // https://go.nuxtjs.dev/content
    '@nuxt/content',
  ],

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {},

  // Content module configuration: https://go.nuxtjs.dev/config-content
  content: {},

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {},

  fontawesome: {
    proIcons: {
      light: [
        'faUserCircle',
        'faSignOut',
        'faTimes',
        'faChevronRight',
        'faVoteYea',
        'faCheck',
        'faFileAlt',
        'faCheckCircle',
        'faClipboard',
      ],
      regular: ['faPencil', 'faTrash', 'faPlus'],
    },
  },

  styleResources: {
    scss: [
      '@compromis/blobby/scss/_variables.scss',
      '@compromis/blobby/scss/_mixins.scss',
    ],
  },
}
