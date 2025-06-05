<template>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
      <div class="offcanvas-header">
        <h5 class="mb-0" id="offcanvasRightLabel">Notifications</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-12" v-for="not in notification" :key="`not${not.id}`">
            <div class="card notif card-sm" @click="openNotification(not)" style="cursor:pointer">
              <div class="card-body">
                <h4 class="mb-0">{{not.title}}</h4>
                <p class="mb-0" v-html="not.description"></p>
                <p class="mb-0" style="font-size:10px">{{parseDate(not.created_at)}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <nav class="navbar sticky-top nav-top navbar-expand-md navbar-light border-0 " style="box-shadow:none">
    <div class="container-fluid top-bar" >
      <router-link class="navbar-brand d-lg-none" to="/app/dashboard" style="width:auto;padding:0;padding-top:8px">
        <div class="d-flex align-items-center gap-3 ">
          <img :src="getLogo()" class="img-fluid" style="width:30px">
          <h4 class="mb-0" style="font-size:14pt">My<b class="text-primary">Notaris</b></h4>
        </div>
      </router-link>
      <!-- <h2 class="mb-0 fw-bold ms-3" v-if="user.role_id == 1">Admin Panel</h2>
      <h2 class="mb-0 fw-bold ms-3" v-if="user.role_id == 2">Company Panel</h2>
      <h2 class="mb-0 fw-bold ms-3" v-if="user.role_id == 3">Member Panel</h2> -->

      <b-navbar-toggle class="" target="nav-collapse"></b-navbar-toggle>

      <b-collapse class=" nav-collapse d-lg-none bg-white" id="nav-collapse" is-nav>
         <ul class="navbar-nav d-lg-none" v-if="$route.params.company_id == null">
          <sidebar-button text="Dashboard" url="/app/dashboard" icon-class="fe fe-grid" />
        </ul>

         <ul class="navbar-nav d-lg-none" v-else>
          <sidebar-button text="Dashboard" :url="`/app/dashboard/${$route.params.company_id}/dashboard`" icon-class="fe fe-pie-chart" />
          <sidebar-button text="Merchant's Benefit" :url="`/app/dashboard/${$route.params.company_id}/benefits`" icon-class="fe fe-list" />
        </ul>

        <template v-if="user.role_id == 1">
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav d-lg-none d-md-none">
            <sidebar-button text="Subscription" url="/app/subscription"  icon-class="fe fe-grid" />
            <sidebar-button text="Users" url="/app/company"  icon-class="fe fe-users" />
          </ul>
        </template>
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav d-lg-none d-md-none">
            <sidebar-button text="Notaris" url="/app/notaris"  icon-class="fe fe-file-text" />
            <sidebar-button text="PPAT" url="/app/ppat"  icon-class="fe fe-file-text" />

          </ul>
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav d-lg-none d-md-none">
            <sidebar-button text="Client" url="/app/client"  icon-class="fe fe-users" />
          </ul>
        <template v-if="[1].includes(user.role_id)">
          <hr class="navbar-divider my-3">
          <ul class="navbar-nav d-lg-none">
            <sidebar-button text="Logs" :url="{path: '/app/user-log'}" icon-class="fe fe-activity" />
          </ul>
        </template>
        <hr class="navbar-divider my-3">
        <ul class="navbar-nav d-lg-none">
          <sidebar-button text="Account" :url="{path: '/app/account'}" icon-class="fe fe-user" />
        </ul>
      </b-collapse>

      <div class="navbar-nav d-none d-lg-flex align-items-center" style="background-color:white;padding:10px;">
         <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" class="btn btn-white position-relative me-3">
          <i class="fe fe-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{notification.length}}
            <span class="visually-hidden">unread messages</span>
          </span>
        </button>
        <div class="dropdown">
          <a href="#" class="text-dark dropdown-toggle d-flex align-items-center gap-2" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!-- <img src="/assets/img/avatars/profiles/avatar-1.jpg" alt="..." class="avatar-img rounded-circle" /> -->

            <div class="avatar ">
              <img :src="user.photo?user.photo : '/images/default-user.png'" class="avatar-img rounded-circle me-3">
            </div>
            <div class="d-flex ms-2" style="flex-direction:column">
              {{ user.name }}
              <small class="text-muted">{{user.role.name}}</small>
            </div>
          </a>

          <div class="dropdown-menu dropdown-menu-end">
            <router-link :to="{ path: '/app/account' }" class="dropdown-item pl-3">
              <fa icon="user" fixed-width />
              Account
            </router-link>

            <div class="dropdown-divider" />
            <a href="#" class="dropdown-item pl-3" data-bs-toggle="modal" data-bs-target="#logout-modal">
              <fa icon="sign-out-alt" fixed-width />
              {{ 'Logout' }}
            </a>
          </div>
        </div>
      </div>
    </div>
    
  </nav>
</template>

<script>
import SidebarButton from './Sidebar/SidebarButton.vue'
import { mapGetters } from 'vuex'
import axios from 'axios'
import moment from 'moment'

export default {
  components: { SidebarButton },

  data: () => ({
    notification:[]
  }),

  computed: {
    ...mapGetters({
      user: 'user',
    }),
  },

  methods: {
    getLogo(){
      let logo = '/images/logo-home.png'
      return logo
    },
    listenForChanges(){
      Notification.requestPermission();
      window.Echo.connector.pusher.connection.bind('connected', () => {
      console.log('connected');
    });
      if(this.user.role_id == 2){
        try{
          console.log(`prepare for notification-${this.user.id}`);
        // var channel = window.Echo.channel(`notification-company-${this.user.company_id}`)
        var channel = window.Echo.channel(`notification-${this.user.id}`)
        channel.listen(`.new-notification`, post => {
          try{

            Notification.requestPermission( permission => {
              let notification = new Notification(post.title, {
                body: post.message, // content for the alert
                icon: "https://heyjen.heyhi.sg/images/logo-home.png" // optional image url
              });


              this.getNotification()
              // link to page on clicking the notification
              notification.onclick = () => {
                window.open(window.location.origin+'/app'+post.target_url);
              };
            });

          this.$root.toast(post.title,'success')
          this.getNotification()
          }catch(e){
            console.error(`failed to listen : ${err}`);

          }
        })
        }catch(err){
          console.error(`failed to listenForChanges : ${err}`);
        }
      }else if(this.user.role_id == 3){
        for (let index = 0; index < this.user.membership.length; index++) {
          const element = this.user.membership[index];
          var channel = window.Echo.channel(`notification-membership-${element.membership_tier_id}`)
          channel.listen(`.new-notification`, post => {
            this.$root.toast(post.title,'success')
            this.getNotification()
          })
        }
      }
      
    },
     async getNotification(){
      try {
        const response = await axios.get('/api/v1/broadcast/notification',{
          params:{
            type: this.user.role_id == 2? 'company': 'user',
            company_id: this.user.company_id,
            user_id: this.user.id
          }
        });
        console.log(response.data);
        this.notification = response.data.data
      } catch (error) {
        console.log(error)
      }
    },
    parseDate(date){
      return moment(date).format('YYYY-MM-DD HH:mm')
    },
    openNotification(not){
      if(not.link){
        window.location.replace(window.location.origin+not.link)
      }
    }
  },
  mounted(){
    // this.getDepartment()
    this.listenForChanges()
    this.getNotification()
  }
}
</script>

<style scoped>
.navbar{
  margin-left: 280px;
  height: 75px;
}
.navbar-brand-img, .navbar-brand > img{
  max-height: 2.5rem;
}
.navbar-brand{
  margin-left:50px
}
.navbar-nav{
  margin-right: 30px;
}
@media(max-width:720px){
  .navbar-brand{
    margin-left:10px
  }
  .navbar-nav{
    margin-right: 0px;
  }
  .navbar{
    z-index: 9999;
    margin-left: 0px;
  }
}
.form-control-search{
  padding-top:0.5rem;
  padding-bottom:0.5rem;
}
.avatar-sm{
  height: 1.5rem;
}
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
.form-inline .fe {
  font-size: 1.2rem;
  color: #A6BDDE;
  padding-left: 10px;
}
.form-inline i {
  position: absolute;
}
.form-control {
  padding-left: 40px;
}
.topbar .fe {
  font-size: 1.5rem;
  color: #A6BDDE;
}
.navbar {
  background-color: #fff;
}
.navbar-nav .nav-item .nav-link {
  color: black;
}

</style>
