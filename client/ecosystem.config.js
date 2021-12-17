module.exports = {
  apps: [
    {
      name: 'EsmenesFrontend',
      exec_mode: 'cluster',
      instances: 'max',
      script: './node_modules/nuxt/bin/nuxt.js',
      args: 'start --port 8574',
    },
  ],
}
