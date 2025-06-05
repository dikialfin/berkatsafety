<template>
  <router-view :key="$route.path"></router-view>
</template>

<script>
import axios from 'axios'
import router from '@/js/router'
import store from '@/js/stores'
export default {
  mounted() {
    axios.interceptors.request.use(request => {
      const token = store.getters['token']
      if (token) {
        request.headers.common.Authorization = `Bearer ${token}`
      }
      return request
    })

    axios.interceptors.response.use(function (response) {
      return response;
    }, function (error) {
      if (error.response.status === 401 || error.response.status === 419) {
        if(error.response.data.message === 'CSRF token mismatch.') return
        store.dispatch('logout')
        router.replace({name: 'Login'})
      } else if(error.response.status === 403) {
        router.push({name: 'VerifyEmail'})
      } else if(error.response.status === 423) {
        router.push({name: 'ConfirmPassword'})
      }

      return Promise.reject(error);
    });
  },
}
</script>