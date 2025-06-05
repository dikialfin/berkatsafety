<template>
    <div class="row align-items-center py-2 mx-0">
        <div class="col-12 col-lg-12 mb-4">
            <h1 class="mb-0 fw-bold">Welcome Back, {{user.name}}!</h1>

        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="card">
            <div class="card-body">
              <router-link class="nav-link submenu" :to="'/app/account'">
                <div class="d-flex align-items-center justify-content-between">
                  <h4 class="mb-0">Users</h4>
                  <h1 class="mb-0">{{card.user}}</h1>
                </div>
              </router-link>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="card">
            <div class="card-body">
              <router-link class="nav-link submenu" :to="'/app/contact-us'">
                <div class="d-flex align-items-center justify-content-between">
                  <h4 class="mb-0">Contact</h4>
                  <h1 class="mb-0">{{card.contact}}</h1>
                </div>
              </router-link>
            </div>
          </div>
        </div>
         <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/categories'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Categories</h4>
                    <h1 class="mb-0">{{card.categories}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/brands'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Brands</h4>
                    <h1 class="mb-0">{{card.brands}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/products'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Products</h4>
                    <h1 class="mb-0">{{card.products}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/catalogue'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Catalogue</h4>
                    <h1 class="mb-0">{{card.catalogue}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/csr'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">CSR</h4>
                    <h1 class="mb-0">{{card.csr}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
              <div class="card-body">
                <router-link class="nav-link submenu" :to="'/app/blogs'">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">News</h4>
                    <h1 class="mb-0">{{card.blogs}}</h1>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { Chart as ChartJS, ArcElement, Tooltip, Legend, BarElement,
  CategoryScale,
  LinearScale} from 'chart.js'
import { Pie,Bar } from 'vue-chartjs'
import moment from 'moment'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios'

ChartJS.register(ArcElement, Tooltip, Legend, BarElement,
  CategoryScale,
  LinearScale)

export default {
    components: {
        Pie,
        Bar,
        VueDatePicker
    },
    computed: mapGetters({
        user: 'user',
        department: 'department'
    }),

    data() {
      return {
        loading:false,
        report:{
            shipment_af:0,
            shipment_af_available:0,
            shipment_af_completed:0,

            incoming:0,
            incoming_available:0,
            incoming_completed:0,

            ritase:0,
            ritase_available:0,
            ritase_completed:0,

            mawb:0,
            mawb_available:0,
            mawb_completed:0
        },
        filter:{
          date:[]
        },
        data:{
            labels: ['Available', 'Completed'],
            datasets: [
                {
                backgroundColor: ['red','green'],
                data: [40, 20]
                }
            ]
        },
        dataBar:{
            labels: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ],
            datasets: [
                {
                label: 'Register',
                backgroundColor: '#B6CEE7',
                data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
                }
            ]
        },
        options:{
            responsive: true,
            maintainAspectRatio: false,
        },
        report:{
          total_notaris:0,
          total_ppat_progress:0,
          total_ppat_selesai:0
        },
        card : {
          user : 0,
          contact : 0,
          categories : 0,
          brands : 0,
          products : 0,
          catalogue : 0,
          csr : 0,
          blogs : 0
        }
        
      }
    },
    mounted() {
      this.getData()
    },
    methods: {
      async getData () {
        await axios.get(`/api/v1/dashboard`).then(res => {
          let data = res.data.data
          this.card = {
              user : data.user,
              contact : data.contact,
              categories : data.categories,
              brands : data.brands,
              products : data.products,
              catalogue : data.catalogue,
              csr : data.csr,
              blogs : data.blogs
            }
        }).catch(err => {
          console.log(err)
        })
      },
      getColor(value){
          let color = 'success'
          if(parseFloat(value) <= 50){
              color = 'danger'
          }else if(parseFloat(value) > 50 && parseFloat(value) < 80){
              color = 'warning'
          }else if(parseFloat(value) >= 80){
              color = 'success'
          }
          return 'secondary'
      }
    }
};
</script>
