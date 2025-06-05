import axios from 'axios'
import store from '@/js/stores'

// Request interceptor
axios.interceptors.request.use(request => {
  const token = store.getters.token
  if (token) {
    request.headers.common.Authorization = `Bearer ${token}`
  }
  return request
})

// Response interceptor
axios.interceptors.response.use(response => response, error => {
  const { status } = error.response
  if (status === 401 ) {
    store.commit('logoutUser')
  }

  if (status >= 500) {
    console.log('Error! ',error.response)
  }

  return Promise.reject(error)
})
