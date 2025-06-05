<template>
  <div class="row align-items-center py-2">
    <div class="col-12 col-lg-12 mb-4">
        <div class="d-flex align-items-center gap-4">
          <h2 class="mb-0 fw-bold">{{member.company.name}} Community</h2>
        </div>
    </div>
    <div class="col-12">
        <!-- <h4>Your Membership</h4> -->
        <div class="row">
          <div class="col-12 text-center" v-if="loading">
            <b-spinner
              variant="primary"
            />
            <h4>Loading</h4>
          </div>
          <template v-else>
            <div class="col-12 col-lg-4"  >
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <member-card
                        :design="member.tier.design"
                        :userData="member.memberField"
                      />
                    </div>
                    <div class="col-12 mt-3 text-center">
                      <button class="btn btn-white" @click="edit(member)">
                        <i class="fe fe-edit"></i> Edit
                      </button>
                      <a :href="formatLink(member)" target="_blank">
                        <button class="btn btn-white ms-2">
                          <i class="fe fe-eye"></i> BioLink
                        </button>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="row align-items-center" style="height:229px">
                    <div class="col-12 text-center">
                    <h2 class="mb-0">No loyalty Program available</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="row align-items-center" style="height:229px">
                    <div class="col-12 text-center">
                      <h1 class="mb-3">0 </h1>
                      <h2 class="mb-0">Upcoming activity</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 text-start mb-3">
                      <div class="d-flex align-items-center">
                        <i class="fe fe-bell me-2"></i>
                        <h3 class="mb-0">Notifications</h3>
                      </div>
                    </div>
                    <div class="col-12 text-start mt-3" v-if="notifications.length == 0">
                      <p class="mb-0">You don't have any notification</p>
                    </div>
                    <template v-else>
                    <div class="col-12">
                      <div class="card mb-3" v-for="notif in notifications" :key="`notif${notif.id}`" style="border: 2px solid whitesmoke;box-shadow:none">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12">
                              <div class="d-flex justify-content-between">
                              <h3 class="mb-3">{{notif.title}}</h3>
                              <p class="text-muted mb-0">{{parseDate(notif.created_at)}}</p>
                              </div>
                              <p class="mb-0" v-html="notif.description"></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div> 
    </div>
  </div>
  <div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="mb-0">Edit Information</h4>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitEdit">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-12 mb-4">
                    <label>Full Name</label>
                    <input class="form-control" v-model="editParam.name" disabled>
                  </div>
                  <div class="col-12 mb-4">
                    <label>Email</label>
                    <input class="form-control" v-model="editParam.email" disabled>
                  </div>
                    <div class="col-6 mb-4">
                    <label>Phone Number</label>
                    <input class="form-control" v-model="editParam.phone" disabled>
                  </div>
                </div>
                <form-builder
                  :field="editField"
                  :setParam="setParam"
                  :getParam="getParam"
                />
              </div>
              <div class="col-12 text-end">
                <hr>
                <button class="btn btn-primary" :disabled="loadingEdit">
                  Save
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import axios from 'axios';
import FormBuilder from '@/js/components/FormBuilder.vue';

export default {
    components: {
        FormBuilder
    },
    computed: mapGetters({
        user: 'user',
        department: 'department'
    }),

    data() {
      return {
        loading:true,
        loadingEdit:false,
        loadingNotif:false,
        editField:[],
        editParam:{},
        membership:[],
        notifications:[],
        member:{
          company:{
            name:null
          }
        }
      }
    },
    mounted() {
      this.getMembership()
    },
    methods: {
      edit(member){
        this.editField = []
        member.tier.fields.map(val => {
          this.editField.push({...val,key:val.label})
        })
        this.editParam = {...member.memberField,link:member.link,id:member.id,membership_tier_id:member.membership_tier_id}
        $('#edit-modal').modal('show')
      },
      submitEdit(){
        this.loadingEdit = true 
        const param = new FormData()
         for ( var key in this.editParam ) {
          if(this.editParam[key] != null) {
            if(!['name','email','phone','membership_tier_id','link'].includes(key)){
              const formattedKey = key.toLowerCase().replace(' ','_')
              param.append(formattedKey, this.editParam[key]);
            }else{
              param.append(key, this.editParam[key]);
            }
          }
        }
        axios.post(`/api/v1/membership/update`,param).then(res => {
          this.loadingEdit =false
          this.$root.toast('Data updated!','success')
          $('#edit-modal').modal('hide')
          this.getMembership()
        })
      },
      getMembership(){
        this.loading = true
        axios.get(`/api/v1/membership/user-detail?company_id=${this.$route.params.company_id}`).then(res => {
          this.loading = false
          this.member = res.data.data 
          this.notifications = res.data.notification
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
      },
      async applyFilter(){
        this.loading = true
        await axios.get(`/api/v1/reportafsf-dashboard/overall-af`,{
          params:{...this.filter}
        }).then(res => {
          this.loading = false
          this.report = {...this.report, ...res.data}
        }).catch(err => {
          this.loading = false
        })
      }, 
      getCurrentWeek() {
        var currentDate = moment();

        var weekStart = currentDate.clone().startOf('isoWeek');
        var weekEnd = currentDate.clone().endOf('isoWeek');

        this.filter.origin_id = 0;
        this.filter.date = [new Date(weekStart),new Date(weekEnd)];
        this.applyFilter()
      },
      setParam (field, value) {
        this.editParam[field] = value
      },

      getParam (field) {
        return this.editParam[field] ? this.editParam[field] : ''
      },
      formatLink(member){
        return `${process.env.MIX_APP_URL}/member/p/${member.company_id}/${member.link}`
      },
      parseDate(date){
        return moment(date).format('YYYY-MM-DD HH:mm')
      }
    }
};
</script>
<style scoped>
  .nav-link{
    border-radius: 10px;
    background-color: white;
    margin-right: 15px;
    padding: 12px 30px 12px 30px;
  }
</style>
