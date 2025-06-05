<template>
  <li class="nav-item" v-if="children.length == 0">
    <router-link class="nav-link" :to="url">
      <i :class="iconClass" /> {{ text }}
    </router-link>
  </li>
  <li class="nav-item" v-else>
    <a :class="`nav-link ${$route.name.includes(menuKey)?'active':''}`" href="#"  @click.prevent="isToggle = !isToggle">
      <i :class="iconClass" /> {{ text }}
    </a>
    <b-collapse class="collapse" :id="`menu${collapseId}`" v-model="isToggle">
      <ul class="nav nav-sm flex-column">
        <li class="nav-item" v-for="child in children" :key="`childmenu${child.text}`">
          <router-link class="nav-link submenu" :to="child.url">
            <i :class="child.iconClass" /> {{ child.text }}
          </router-link>
        </li>
      </ul>
    </b-collapse>
  </li>
</template>

<script>
export default {
  name: 'SidebarButton',
  props: {
    text: {
      type: String,
      required: true
    },
    url: {
      type: [String, Object],
      required: true
    },
    iconClass: {
      type: String,
      required: true
    },
    children:{
      type: Array,
      default:[]
    },
    collapseId:{
      type: String,
      required: true
    },
    menuKey:{
      type: String,
      default:''
    }
  },
  watch:{
    '$route.name':function(val){
      if(!val.includes(this.menuKey)){
        this.isToggle = false
      }
    }
  },
  data(){
    return {
      isToggle: false
    }
  }
}
</script>

<style scoped>
.navbar-nav .nav-link>.fe{
  font-size: 1.3rem;
  margin-right: 10px;
  font-weight:300;
  color: black;
}
.nav-link{
  color: black;
  border-radius: 12px;
}
.nav-link.active::before {
  content: none;
}
.nav-link.submenu{
  font-size: 12pt;
}
.nav-link.submenu.active {
  background-color: white!important;
  color: #052C7A!important;
}
.nav-link.submenu.active>.fe {
  color: #052C7A!important;
}
.nav-link.active {
   background-color: #052C7A;
  color:white!important;
}
.nav-link:hover {
  color:#052C7A;
}
.nav-link:hover .fe{
  color:#052C7A;
}
.nav-link.active>.fe {
  color:white!important;
}
.navbar-vertical.navbar-expand-md .navbar-nav .nav-link {
  padding-top: 0.8rem;
  padding-bottom: 0.8rem;
}
</style>
