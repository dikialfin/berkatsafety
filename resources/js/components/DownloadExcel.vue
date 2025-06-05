<template>
  <button :class="btnClass" :disabled="loading" @click="exportExcel()">
     {{title}}
  </button>
  <div class="modal fade" id="excel-export-modal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(1px);">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-card card" >
          <div class="card-header">
            <div class="row align-items-center">
                <div class="col text-start" style="margin-top:30px;margin-bottom:30px">

                <!-- Title -->
                <h2 class="card-header-title" id="exampleModalCenterTitle">
                   Export Data
                </h2>
            
                </div>
                <div class="col-auto">
                <!-- Close -->
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  -->
            
                </div>
            </div> 
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <h4>Choose field that want to be exported:</h4>
                  <div class="form-switch mb-2" v-for="val in availableField" :key="`detailpermission${val}`">
                      <input type="checkbox" class="form-check-input" 
                      :checked="selectedField.includes(val)"
                      @change="selectField(val)" :id="val"/>
                      <label class="custom-control-label ms-3" :for="val">{{val}}</label>
                  </div>
                </div>
                 
              </div>
          </div>
          <div class="card-footer">
             <div class="col-md-12 text-end">
                  <button class="btn btn-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" :disabled="loading" @click="download" class="btn btn-danger waves-effect waves-light">
                    <b-spinner
                      v-if="loading"
                      small
                    />
                    Export Data
                  </button>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import XLSX from 'xlsx'
import Select2 from './Select2.vue'

export default {
  components: { Select2 },
  name: 'DownloadExcel',
  props: {
    url: {
      type: String,
      default: ''
    },
    urlField: {
      type: String,
      default: ''
    },
    param:{
      type: Object,
      default: {}
    },
    title:{
      type:String,
      default:'Download Excel'
    },
    btnClass:{
      type:String,
      default:'btn btn-primary'
    },
    is_advance:{
      type:Boolean,
      default:false
    }
  },
  data () {
    return {
      loading: false,
      selectedField:[],
      availableField:[]
    }
  },
  methods: {
    selectField(field){
      if(this.selectedField.includes(field)){
        var index = this.selectedField.indexOf(field);
        if (index !== -1) {
          this.selectedField.splice(index, 1);
        }
      }else{
        this.selectedField.push(field)
      }
    },
    exportExcel(){
      if(this.is_advance){
        this.loading = true
        axios.get(this.urlField,this.param).then(res => {
          this.loading = false
          this.availableField = res.data
          res.data.map(val => {
            this.selectedField.push(val)
          })
          $('#excel-export-modal').modal('show')
        })
        .catch(err => {
          this.loading = false
        })
        // $('#excel-export-modal').modal('show')
      }else{
        this.loading = true
        axios.post(this.url,{...this.param}).then(e => {
          this.loading = false
          if(e.data.is_multiple){
            for (let index = 0; index < e.data.excel.length; index++) {
              const sh = e.data.excel[index];
              var wb = XLSX.utils.book_new() 
              sh.sheet.map(val => {
                XLSX.utils.book_append_sheet(
                  wb, 
                  XLSX.utils.json_to_sheet(val.data), 
                  val.name
                ) 
              })
              XLSX.writeFile(wb, sh.sheet_name+'.xlsx') 
            }
          }else{
            var wb = XLSX.utils.book_new() 
            e.data.sheet.map(val => {
              XLSX.utils.book_append_sheet(
                wb, 
                XLSX.utils.json_to_sheet(val.data), 
                val.name
              ) 
            })
            XLSX.writeFile(wb, e.data.sheet_name+'.xlsx') 
          }
          
        })
      }
      
    },
    download(){
      this.loading = true
      axios.post(this.url,{...this.param,selected_field:this.selectedField}).then(e => {
        this.loading = false
        if(e.data.is_multiple){
          for (let index = 0; index < e.data.excel.length; index++) {
            const sh = e.data.excel[index];
            var wb = XLSX.utils.book_new() 
            sh.sheet.map(val => {
              XLSX.utils.book_append_sheet(
                wb, 
                XLSX.utils.json_to_sheet(val.data), 
                val.name
              ) 
            })
            XLSX.writeFile(wb, sh.sheet_name+'.xlsx') 
          }
        }else{
          var wb = XLSX.utils.book_new() 
          e.data.sheet.map(val => {
            XLSX.utils.book_append_sheet(
              wb, 
              XLSX.utils.json_to_sheet(val.data), 
              val.name
            ) 
          })
          XLSX.writeFile(wb, e.data.sheet_name+'.xlsx') 
        }
      })
    }
  }
}
</script>
