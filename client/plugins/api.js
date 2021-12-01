import axios from 'axios'

class API {
  constructor(token) {
    this.apiUrl = process.env.NUXT_ENV_API_URL
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    axios.defaults.withCredentials = true

    if (token) {
      this.token = token
      axios.defaults.headers.common.Authorization = 'Bearer ' + token
    }
  }

  xsrf() {
    return this._call('get', '/sanctum/csrf-cookie')
  }

  userinfo(token) {
    axios.defaults.headers.common.Authorization = 'Bearer ' + token
    return this._call('get', 'user')
  }

  assemblies() {
    return this._call('get', 'user/assemblies')
  }

  _call(method, path, params) {
    return new Promise((resolve, reject) => {
      axios[method](this.apiUrl + '/api/' + path, params)
        .then((response) => {
          resolve(response.data)
        })
        .catch((error) => {
          reject(error.response.data)
        })
    })
  }
}

export default ({ store, req }, inject) => {
  const api = new API(store.state.auth.token, req)
  inject('api', api)
}
