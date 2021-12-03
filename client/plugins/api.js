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

  csrf() {
    return this._call('get', 'sanctum/csrf-cookie')
  }

  userinfo(token) {
    axios.defaults.headers.common.Authorization = 'Bearer ' + token
    return this._call('get', 'api/user')
  }

  assemblies() {
    return this._call('get', 'api/assemblies')
  }

  assembly(ref) {
    return this._call('get', 'api/assemblies/' + ref)
  }

  amendments(assembly, doc) {
    return this._call('get', `api/assemblies/${assembly}/amendments/${doc}`)
  }

  amendment(ref) {
    return this._call('get', 'api/amendments/' + ref)
  }

  submitAmendment(assemblyId, amendment) {
    return this._call(
      'post',
      `api/assemblies/${assemblyId}/amendments/new`,
      amendment
    )
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
