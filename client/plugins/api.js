import axios from 'axios'

class API {
  constructor(token) {
    this.apiUrl = process.env.NUXT_ENV_API_URL
    this.token = token
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    axios.defaults.headers.common.Authorization = 'Bearer ' + token
    axios.defaults.headers.common['X-XSRF-TOKEN'] = ''
  }

  _call(method, path, params) {
    return new Promise((resolve, reject) => {
      axios[method](this.apiUrl + '/' + path, params)
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
