<template>
  <div class="row">
    <div class="col-12">
      <card-form :title="'Role'">
        <form @submit.prevent="save">
        <div class="row align-items-center ">
          <div class="col-lg-2 col-12">
            <label>Name <span class="text-danger">*</span></label>
          </div>
          <div class="col-lg-10 col-12">
            <input class="form-control" v-model="params.name" required>
          </div>
        </div>
        <div class="row align-items-center mt-4">
          <div class="col-lg-12 col-12">
            <hr>
            <label>Permissions</label>
          </div>
          <div class="col-lg-12 col-12 mt-3">
            <b-spinner
            v-if="permission == null"
            />
            <div class="row" v-else>
              <div class="col-3" v-for="(value, key) in permission" :key="`permission${key}`">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <p class="mb-0 text-capitalize">{{key}}</p>
                      <input type="checkbox" class="form-check-input" 
                        @change="changePermissionBulk(value)" :checked="checkPermissionComplete(value)"/>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-switch mb-2" v-for="val in value" :key="`detailpermission${val}`">
                        <input type="checkbox" class="form-check-input" 
                        :checked="params.permissions.includes(val)"
                        @change="changePermission(val)" :id="val"/>
                        <label class="custom-control-label ms-3" :for="val">{{val}}</label>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
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
      </form>
    </card-form>
    </div>
  </div>
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
    this.getPermission()
    if (!isNaN(this.$route.params.id)) {
      this.getData()
    }
  },

  methods: {
    changePermission(permission){
      if(this.params.permissions.includes(permission)){
        var index = this.params.permissions.indexOf(permission);
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
          if(this.params.permissions.includes(permission)){
            var index = this.params.permissions.indexOf(permission);
            if (index !== -1) {
              this.params.permissions.splice(index, 1);
            }
          }
        })
      }else{
        permissions.map(permission => {
          if(!this.params.permissions.includes(permission)){
            this.params.permissions.push(permission)
          }
        })
      }
    },
    checkPermissionComplete(permissions){
      let check = true
      permissions.map(permission => {
        if(!this.params.permissions.includes(permission)){
          check = false
        }
      })
      return check
    },
    async getPermission () {
      await axios.get(`/api/v1/role/permission?department_id=${this.department}`).then(res => {
        this.permission = res.data.data
      }).catch(err => {
        console.log(err)
      })
    },
    async getData () {
      await axios.get(`/api/v1/role/edit/${this.$route.params.id}`).then(res => {
        this.params = res.data.data
        if(res.data.data.photo){
          this.previewImg = res.data.data.photo
        }
        this.params.photo = null
      }).catch(err => {
        console.log(err)
      })
    },

    async save () {
      this.params = {...this.params,department_id:this.department}
      if (!isNaN(this.$route.params.id)) {
        await axios.post(`/api/v1/role/update`, this.params).then(res => {
          this.$router.go(-1)
          this.$root.toast('Data updated!','success')
        }).catch(err => {
          this.$root.toast(err.response.data.message,'error')
        })
      } else {
        await axios.post('/api/v1/role/add', this.params).then(res => {
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
