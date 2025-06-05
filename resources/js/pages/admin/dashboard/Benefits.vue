<template>
  <div class="row align-items-center py-2">
    <div class="col-12 col-lg-12 mb-4">
        <div class="d-flex align-items-center gap-4">
          <h2 class="mb-0 fw-bold">{{company.name}} Community Benefits</h2>
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
          <div class="col-12 col-lg-4" v-for="ben in benefit" :key="`member${ben.id}`" v-else>
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 text-center mb-4 justify-content-center align-items-center d-flex" style="height:150px">
                    <img :src="ben.merchant.photo" style="width:150px"/>
                  </div>
                  <div class="col-12 mt-3 text-center">
                    <h4>{{ben.merchant.name}}</h4>
                    <h2 class="mb-0">{{ben.name}}</h2>
                    <p class="mb-0">{{ben.description}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import axios from 'axios';

export default {
    components: {
    },
    computed: mapGetters({
        user: 'user',
        department: 'department'
    }),

    data() {
      return {
        loading:true,
        loadingEdit:false,
        editField:[],
        editParam:{},
        benefit:[],
        company:{
          name:null
        }
      }
    },
    mounted() {
      this.getCompany()
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
      getCompany(){
        axios.get(`/api/v1/company/edit/${this.$route.params.company_id}`).then(res => {
          this.company = res.data.data
        })
      },
      getMembership(){
        this.loading = true
        axios.get(`/api/v1/membership/benefit?company_id=${this.$route.params.company_id}`).then(res => {
          this.loading = false
          this.benefit = res.data.data
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
