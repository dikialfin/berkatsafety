<template>
  <form @submit.prevent="save">
    <div class="row">
      <div class="col-12">
        <card-form :title="'Subscription'">
          <div class="row align-items-center ">
            <div class="col-lg-2 col-12">
              <label>Name <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-10 col-12">
              <input class="form-control" v-model="params.name" required>
            </div>
          </div>
        </card-form>
      </div>
      <div class="col-12">
        <div class="row align-items-center ">
            <div class="col-lg-12 col-12">
              <label>Feature</label>
            </div>
            <div class="col-lg-12 col-12 mt-3">
              <b-spinner
              v-if="permission == null"
              />
              <div class="row" v-else>
                <div class="col-4" v-for="(value, key) in permission" :key="`permission${key}`">
                  <div class="card">
                    <div class="card-header">
                      <div class="d-flex justify-content-between">
                        <p class="mb-0 text-capitalize">{{removeUnderScore(key)}}</p>
                        <input type="checkbox" class="form-check-input" 
                          @change="changePermissionBulk(value)" :checked="checkPermissionComplete(value)"/>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex align-items-center"  v-for="val in value" :key="`detailpermission${val.name}`">
                        <div class="form-switch mb-2">
                            <input type="checkbox" class="form-check-input" 
                            :checked="isPermissionInclude(val)"
                            @change="changePermission(val)" :id="val.name"/>
                            <label class="custom-control-label ms-3" :for="val">{{removeUnderScore(val.name)}}</label>
                        </div>
                        <input @change="changePermissionValue(val)" min="0" class="form-control ms-auto" style="flex:0.4;" v-if="val.has_value" v-model="val.value" :disabled="!isPermissionInclude(val)">
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
      </div>
      <div class="col-12">
        <div class="row">
            <div class="col-12">
              <hr>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary">
                  Submit
                </button>
              </div>
            </div>
          </div>
      </div>
    </div>
  </form>
</template>

<script>
import axios from 'axios'
import CardForm from '../../../components/CardForm.vue'
import { mapGetters } from 'vuex'
import Select2 from '../../../components/Select2.vue'

export default {
  components: {
    CardForm,
    Select2
  },

  metaInfo () {
    return { title: 'Form' }
  },

  data: function () {
   
    return {
      params: {
        permissions:[]
      },
      permission:null,
      image: null,
      previewImg:'/images/default-user.png'
    }
  },

  computed: {
    ...mapGetters({
      user: 'user',
      department: 'department'
    }),
    hasID () {
      return !isNaN(this.$route.params.id)
    },
    title () {
      if (!isNaN(this.$route.params.id)) {
        return 'Edit'
      } else {
        return 'Create'
      }
    },
  },

  async created () {
    await this.getPermission()
    if (!isNaN(this.$route.params.id)) {
      this.getData()
    }
  },

  methods: {
    removeUnderScore(val){
      return val.replaceAll('_', ' ');
    },
    changePermissionValue(permission){
      var index = this.params.permissions.findIndex(val => val.name == permission.name);
      if(index > -1){
        this.params.permissions[index].value = permission.value;
      }
    },
    changePermission(permission){
      const isInclude = this.isPermissionInclude(permission)
      if(isInclude){
        var index = this.params.permissions.findIndex(val => val.name == permission.name);
        if (index !== -1) {
          this.params.permissions.splice(index, 1);
        }
      }else{
        this.params.permissions.push(permission)
      }
    },
    changePermissionBulk(permissions){
      if(this.checkPermissionComplete(permissions)){
         permissions.map(permission => {
          if(this.isPermissionInclude(permission)){
            console.log(permission)
            const index = this.params.permissions.findIndex(val => val.name == permission.name);
            const idxGroup = this.params.permissions.findIndex(val => val.name == permission.group);
            if (index !== -1) {
              this.params.permissions.splice(index, 1);
            }
            if(idxGroup > -1){
              this.params.permissions.splice(idxGroup,1);
            }
          }
        })
      }else{
        permissions.map(permission => {
          if(!this.isPermissionInclude(permission)){
            this.params.permissions.push(permission)
          }
        })
      }
    },
    checkPermissionComplete(permissions){
      let check = true
      permissions.map(permission => {
        if(!this.isPermissionInclude(permission)){
          check = false
        }
      })
      return check
    },
    isPermissionInclude(permission){
      var index = this.params.permissions.findIndex(val => val.name == permission.name);
      if (index !== -1) {
        return true
      }
      return false
    },
    async getPermission () {
      await axios.get(`/api/v1/subscription/permission`).then(res => {
        this.permission = res.data.data
      }).catch(err => {
        console.log(err)
      })
    },
    async getData () {
      await axios.get(`/api/v1/subscription/edit/${this.$route.params.id}`).then(res => {
        this.params = res.data.data
        let newPerm = []
        Object.keys(this.permission).forEach(key => {
          this.permission[key].map(val => {
            this.params.permissions.map(perm => {
              if(perm.name == val.name){
                val.value = perm.value
              }
            })
          })
        });
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      this.params = {...this.params,department_id:this.department}
      if (!isNaN(this.$route.params.id)) {
        console.log(this.params);
        await axios.post(`/api/v1/subscription/update`, this.params).then(res => {
          this.$router.go(-1)
          this.$root.toast('Data updated!','success')
        }).catch(err => {
          this.$root.toast(err.response.data.message,'error')
        })
      } else {
        await axios.post('/api/v1/subscription/add', this.params).then(res => {
          this.$router.go(-1)
          this.$root.toast('Data created!','success')
        }).catch(err => {
         this.$root.toast(err.response.data.message,'error')
        })
      }
    },

    changePreview(e){
      const [file] = e.target.files
      if (file) {
        if(file.size > 500000){ //500kb
          this.$root.toast('File cannot more than 500kb!','error')
          $('#fileinput').val(null)
          this.previewImg = '/images/default-user.png'
          this.params.photo = null
          return false
        }
        this.params.photo = file
        this.previewImg = URL.createObjectURL(file)
      }else{
        this.params.photo = null
        this.previewImg = '/images/default-user.png'
      }
    },

    setParam (field, value) {
      this.params[field] = value
    },

    getParam (field) {
      return this.params[field] ? this.params[field] : ''
    }
  }
}
</script>
