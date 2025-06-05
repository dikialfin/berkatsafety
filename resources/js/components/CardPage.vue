<template>
  <div class="card">
    <div v-if="title" class="card-header" :style="{
      background : backgroundHeader
    }"
    >
      <h4 class="mb-0">
        <button class="btn btn-white me-3" @click="$router.go(-1)" v-if="isBack">
            <i class="fe fe-chevron-left" />
          </button>
        {{ title }}
      </h4>
    </div>
    <div v-if="multiNav.length != 0" class="card-header" :style="{
      background : backgroundHeader
    }">
      <ul role="tablist" class="nav nav-tabs card-header-tabs">
        <li v-for="(nav,index) in multiNav" :key="index" role="presentation" class="nav-item">
          <router-link class="nav-link" :to="{name: nav.pageName, params: nav.params}">
            {{ nav.name }}
          </router-link>
        </li>
      </ul>
    </div>
    <slot name="custom-header"/>

    <div class="card-body" :style="{
      background : backgroundBody
    }"
    >
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CardPage',

  props: {
    isBack: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: null
    },
    backgroundHeader: {
      type: String,
      default: '#f9fbfd'
    },
    backgroundBody: {
      type: String,
      default: '#ffffff'
    },
    multiNav: {
      type: Array,
      default () {
        return []
      }
    }
  }
}
</script>

<style scoped>
.card-header-tabs .nav-link.active{
  border-bottom-color: transparent;
}
.nav-tabs .nav-link{
  padding: 1rem;
}

.nav-tabs .nav-item{
  margin-left: 0;
  margin-right: 0;
}
</style>
