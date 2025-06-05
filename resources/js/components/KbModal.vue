<template>
  <div class="modal fade" tabindex="-1" id="modalKb">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- <div class="modal-header">
          <h2 class="modal-title">Content Library</h2>
        </div> -->
        <div class="modal-body ">
          <template v-if="loading">
            <div class="row align-items-center justify-content-center" style="min-height:600px">
              <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                </div>
                <p>Converting content, please wait...</p>
              </div>
            </div>
          </template>
          <template v-else>
            <template v-if="step == 'list'">
              <div class="row align-items-center mb-3">
                <div class="col-4">
                  <h2 class="mb-0">Knowledge Base</h2>

                  <!-- <div class="input-group">
                    <input class="form-control">
                  </div> -->
                </div>
                <div class="col-8 ">
                  <div class="d-flex justify-content-end align-items-center gap-3">
                    <button class="btn btn-primary" @click="step = 'create'">Create Content</button>
                    <button type="button" class="btn btn-close btn-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                </div>
                <div class="col-12">
                  <hr>
                </div>
              </div>
              <div class="row">
                <data-table
                    :fields="fields"
                    :url="'/api/v1/knowledge-base/data'"
                    :param="{
                      ...filter,
                      version:version
                    }"
                    :refresh="refresh"
                    :filterable="false"
                  > 
                   <template #action="action">
                    <button class="btn btn-white me-3" @click="viewKB(action.action.item)">
                      Detail
                     </button>
                     <button class="btn btn-white" @click="selectKb(action.action.item)">
                      Select
                     </button>
                    </template>
                  </data-table>
              </div>
            </template>
            <template v-if="step == 'create'">
              <div class="row">
                <div class="col-12">
                  <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-white" @click="step = 'list'" v-if="!formOnly">
                      <i class="fe fe-chevron-left"></i>
                    </button>
                    <p class="mb-0 fw-bold">Create Content</p>
                  </div>
                  <hr>
                </div>
              </div>
              <div class="row">
                <form  @submit.prevent="(e) => submitDesign(e)" >
                  <div class="row mb-4">
                    <div class="col-lg-4">
                      <p class="mb-0 fw-bold">Title <span class="text-danger">*</span></p>
                      <p class="text-muted small mb-0">Add a title for your content.</p>
                    </div>
                    <div class="col-lg-8">
                      <input class="form-control" name="name" v-model="params.name" required>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-lg-4">
                      <p class="mb-0 fw-bold">Category <span class="text-danger">*</span></p>
                      <p class="text-muted small mb-0">Categorize your content.</p>
                    </div>
                    <div class="col-lg-8">
                      <input class="form-control" name="topic" v-model="params.category" required>
                    </div>
                  </div>
                  <!-- <div class="row mb-4">
                    <div class="col-lg-4">
                      <p class="mb-0"><b>Description</b>  <small>(Optional)</small></p>
                      <p class="text-muted small mb-0">Add a description to describe your knowledge base.</p>
                    </div>
                    <div class="col-lg-8">
                      <textarea class="form-control" v-model="form_pdf.description"></textarea>
                    </div>
                  </div> -->
                  <div class="row mb-4">
                    <div class="col-lg-4">
                      <p class="mb-0 fw-bold">File Upload <span class="text-danger">*</span></p>
                      <p class="text-muted small mb-0">Upload your file in PDF format to be the knowledge base.</p>
                    </div>
                    <div class="col-lg-8">
                      <input name="docfile" class="form-control "
                        accept=".pdf" type="file" id="fileinput" @change="handleChange" required >
                    </div>
                  </div>
                  <!-- <div class="row mb-4">
                    <div class="col-lg-4">
                      <p class="mb-0 fw-bold">Knowledge Base Type <span class="text-danger">*</span></p>
                      <p class="text-muted small mb-0">Type of your knowledge base.</p>
                    </div>
                    <div class="col-lg-8">
                      <select name="kb_type" class="form-control " v-model="form_pdf.kb_type" required >
                        <option value="article">Article</option>
                        <option value="worksheet">Worksheet</option>
                      </select>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col-12 text-end">
                      <button ref="pdf_form" class="btn btn-lg btn-primary mt-3" :disabled="loading">
                        Submit
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </template>
            <template v-if="step == 'view'">
              <div class="row">
                <div class="col-12">
                  <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-white" @click="step = 'list'" v-if="!formOnly">
                      <i class="fe fe-chevron-left"></i>
                    </button>
                    <p class="mb-0 fw-bold">View Content</p>
                  </div>
                  <hr>
                </div>
              </div>
              <div class="row">
                <div class="col-12 d-flex justify-content-center">
                  <div style="width:70%">
                    <PDFViewer
                      :source="selected.source"
                      style="height: 600px; width:100%"
                      @download="handleDownload"
                    />
                  </div>
                </div>
              </div>
            </template>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'
import DataTable from './DataTable.vue'
import PDFViewer from 'pdf-viewer-vue'

export default {
  props:['selectKb','formOnly','version'],
  components:{
    DataTable,
    PDFViewer
  },
  data(){
    return {
      knowledgeBase:[],
      step: 'list',
      params:{},
      loading:false,
      loadingFetch:false,
      fields: [
         {
          label: 'Name',
          key: 'name',
          sortable: true,
        },
         {
          label: 'Category',
          key: 'category',
          sortable: true,
        },
        {
          key: 'action',
          tdAttr: {
            style: 'width: 20%'
          }
        }
      ],
      refresh: 0,
      filter:{},
      selected:{}
    }
  },
  methods:{
    viewKB(data){
      console.log(data)
      this.selected = data
      this.step = 'view'
    },
    async getKnowledgeBase() {
      this.loadingFetch = true
      try {
        const response = await axios.get(`/api/v1/knowledge-base/data`)
        this.loadingFetch = false
        this.knowledgeBase = response.data.data.data
      } catch (error) {
        this.loadingFetch = false
        console.log(error)
      }
    },
    handleChange(e){
      const [file] = e.target.files
      if (file) {
        // if(file.size > 5000000){ //500kb
        //   this.$root.toast('File cannot more than 5mb!','error')
        //   $('#fileinput').val(null)
        //   this.params.docfile = null
        //   return false
        // }
        this.params.docfile = file
      }else{
        this.params.docfile = null
      }
    },
    async submitDesign(form){
       const param = new FormData()
        for ( var key in this.params ) {
          if(this.params[key] != null) param.append(key, this.params[key]);
        }

      this.loading = true
      try {
        const response2 = await axios.post(`/api/v1/knowledge-base/add`,param)
        this.$root.toast('Document uploaded. Please wait for it to be processed', 'success')
        this.loading = false
        if(this.formOnly){
          $('.modal').modal('hide')
        }else{
          this.step = 'list'
          this.getKnowledgeBase()
        }
       
      } catch (error) {
        console.log(error)
        this.$root.toast(error.response.data.message, 'error')
        this.loading = false
        
      }
    }
  },
  mounted(){
    this.getKnowledgeBase()
    if(this.formOnly){
      this.step = 'create'
    }
  }
}
</script>