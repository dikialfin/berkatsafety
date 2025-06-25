<template>
  <nav class="navbar navbar-vertical fixed-start navbar-expand-md navbar-light d-none d-md-block" style="box-shadow:none;border:none;padding-left:40px;padding-right:40px">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/app/dashboard" style="width:auto;padding:0;padding-top:8px">
      <div class="d-flex align-items-center gap-3 ">
        <img :src="getLogo()" class="img-fluid" style="width:100%">
        <!-- <h4 class="mb-0" style="font-size:18pt">MyNotaris</h4> -->
      </div>
      </router-link>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
        <span class="navbar-toggler-icon" />
      </button>

      <div class="d-flex align-items-center gap-2" v-if="$route.params.company_id">
        <button class="btn btn-white btn-sm px-3" @click="$router.push({name:'dashboard'})" >
          <i class="fe fe-chevron-left"></i>
        </button>
        <p class="mb-0 fw-bold">{{company.name}} Community</p>
      </div>

      <div id="navbarToggler" class="collapse navbar-collapse mt-4" style="background:white;border-radius:16px">
        <ul class="navbar-nav">
          <sidebar-button text="Dashboard" url="/app/dashboard" icon-class="fe fe-grid" />
        </ul>

        <template v-if="menu.content || [1].includes(user.role_id)">
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav">
            <sidebar-button text="About Us" url="/app/about"  icon-class="fe fe-list" />
            <sidebar-button text="Contact Us" url="/app/contact-us"  icon-class="fe fe-list" />
            <sidebar-button text="About  (CSR)" url="/app/csr"  icon-class="fe fe-list" />
            <sidebar-button text="Brands" url="/app/brands"  icon-class="fe fe-list" />
            <sidebar-button text="Blogs" url="/app/blogs"  icon-class="fe fe-list" />
            <sidebar-button text="Catalogue" url="/app/catalogue"  icon-class="fe fe-list" />
            <sidebar-button text="Categories" url="/app/categories"  icon-class="fe fe-list" />
            <sidebar-button text="Products" url="/app/products"  icon-class="fe fe-list" />
            <sidebar-button text="Media" url="/app/media"  icon-class="fe fe-list" />
            <sidebar-button text="Image Slider" url="/app/image-slider"  icon-class="fe fe-list" />
            <sidebar-button text="Announcement" url="/app/announcement"  icon-class="fe fe-list" />
          </ul>
        </template>

        <hr class="navbar-divider my-3">
        <ul class="navbar-nav">
          <template v-if="menu.content || [1].includes(user.role_id)">
            <sidebar-button text="Translations" url="/app/translations"  icon-class="fe fe-globe" />
          </template>
          <sidebar-button text="SEO Pages Static" url="/app/seo-pages-static"  icon-class="fe fe-globe" />
        </ul>

        <template v-if="menu.role || menu.user || [1].includes(user.role_id)">
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav">
            <sidebar-button text="Role" url="/app/role"  icon-class="fe fe-list" />
            <sidebar-button text="Users" url="/app/user"  icon-class="fe fe-users" />
            <sidebar-button text="Logs" :url="{path: '/app/user-log'}" icon-class="fe fe-activity" />
          </ul>

        </template>
        <template v-if="menu.content || [1].includes(user.role_id)">
          <ul class="navbar-nav">
            <sidebar-button text="Setting" url="/app/setting"  icon-class="fe fe-list" />
          </ul>
        </template>
        <hr class="navbar-divider my-3">
        <ul class="navbar-nav">
          <sidebar-button text="Account" :url="{path: '/app/account'}" icon-class="fe fe-user" />
        </ul>

      </div>

    </div>
  </nav>
</template>

<script>
import SidebarButton from './SidebarButton.vue'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  components: {
    'sidebar-button': SidebarButton,
  },
  data() {
    return {
      company:{},
      menu: {
        role: false,
        user:false,
        content: false
      }
    }
  },
  watch:{
    '$route.params':function(){
      if(this.$route.params.company_id){
        this.getCompany()
      }else{
        this.company = {}
      }
    }
  },
  computed: mapGetters({
    user: 'user',
    membership: 'membership'
  }),
  mounted(){
    if(this.$route.params.company_id){
      this.getCompany()
    }
    
    let permission = this.user.permission
    this.menu.role = permission.some(key => key.startsWith("role"));
    this.menu.user = permission.some(key => key.startsWith("user"));
    this.menu.content = permission.some(key => key.startsWith("content"));
  },
  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    },
    getLogo(){
      let logo = '/images/logo-home.png'
      return logo
    },
    getCompany(){
      axios.get(`/api/v1/company/edit/${this.$route.params.company_id}`).then(res => {
        this.company = res.data.data.company
      })
    },
  }
}
</script>

<style scoped>
.navbar {
  background-color: white;
}
.navbar-nav .nav-link>.fe{
  font-size: 1.3rem;
  margin-right: 10px;
  font-weight:500;
  color: black;
}
.nav-link{
  color: black;
}
.nav-link.active::before {
  border: none !important;;
}
.nav-link.active {
  background-color: #00243d;
  color:white!important;
}
.navbar-vertical.navbar-expand-md .navbar-nav .nav-link {
  padding-top: 1rem;
  padding-bottom: 1rem;
  margin-right: 10px;
  margin-left: 10px;
  border-radius: 10px;
}
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
.btn-outlet{
  padding-top:5px!important;
  padding-bottom:5px!important;
  background: #3073B9, 100%;
  color:white;
}
.p-small{
  font-size:14pt;
  font-weight:bold;
}
.p-outlet{
  font-size:16pt;
}
</style>
