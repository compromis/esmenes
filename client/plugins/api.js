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

  assembly(assembly) {
    return this._call('get', `api/assembly/${assembly}`)
  }

  amendments(assembly, document) {
    return this._call('get', `api/assembly/${assembly}/amendments/${document}`)
  }

  amendment(assembly, amendment) {
    return this._call('get', `api/assembly/${assembly}/amendment/${amendment}`)
  }

  support(assembly, amendment) {
    return this._call(
      'post',
      `api/assembly/${assembly}/amendment/${amendment}/support`
    )
  }

  submitAmendment(assembly, document, amendment) {
    return this._call(
      'post',
      `api/assembly/${assembly}/amendments/${document}/new`,
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
