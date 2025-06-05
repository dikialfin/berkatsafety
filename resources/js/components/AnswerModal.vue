<template>
    <div class="modal fade" tabindex="-1" id="modalAnswer">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">Answer Library</h2>
            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
          </div>
          <div class="modal-body ">
            <template v-if="loading">
              <div class="row align-items-center justify-content-center" style="min-height:600px">
                <div class="col-12 text-center">
                  <div class="spinner-border text-primary" role="status">
                  </div>
                  <p>Converting answer, please wait...</p>
                </div>
              </div>
            </template>
            <template v-else>
              <template v-if="step == 'list'">
                <div class="row align-items-center mb-3">
                  <div class="col-4">
                    <!-- <div class="input-group">
                      <input class="form-control">
                    </div> -->
                  </div>
                  <div class="col-8 text-end">
                      <button class="btn btn-primary" @click="step = 'create'">Create Answer</button>
                  </div>
                </div>
                <div class="row">
                  <data-table
                      :fields="fields"
                      :url="'/api/v1/answer/data'"
                      :param="{
                        ...filter,
                      }"
                      :refresh="refresh"
                      :filterable="false"
                    > 
                     <template #action="action">
                       <button class="btn btn-white" @click="selectAnswer(action.action.item)">
                        Select Answer
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
                      <p class="mb-0 fw-bold">Create Answer</p>
                    </div>
                    <hr>
                  </div>
                </div>
                <div class="row">
                  <form  @submit.prevent="(e) => submitDesign(e)" >
                    <div class="row align-items-top">
                      <!-- <div class="col-lg-4">
                        <p class="mb-0 fw-bold">Answer <span class="text-danger">*</span></p>
                      </div> -->
                      <!-- <div class="col-lg-8">
                        <quill-editor :content="params.answer"
                          :options="editorOption"
                          @change="onEditorChange($event)"
                          required>
                        </quill-editor>
                      </div>
                      <div class="col-12">
                        <hr>
                      </div> -->
                       <div class="col-lg-4">
                        <p class="mb-0 fw-bold">Answer <span class="text-danger">*</span></p>
                      </div>
                      <div class="col-lg-8">
                        <textarea class="form-control" v-model="params.answer" required></textarea>
                      </div>
                    </div>
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
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  import axios from 'axios'
  import DataTable from './DataTable.vue'
  import moment from 'moment'
  import 'quill/dist/quill.core.css'
  import 'quill/dist/quill.snow.css'
  import 'quill/dist/quill.bubble.css'
  import { quillEditor } from 'vue-quill-editor'
  import Quill from "quill";
  import QuillImageUploader from 'quill-image-uploader';
  Quill.register('modules/image-uploader', QuillImageUploader);
  
  export function imageHandler () {
    const quill = this.quill;
  
    const input = document.createElement('input');
  
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();
  
    input.onchange = async () => {
      const file = input.files[0];
      const formData = new FormData();
  
      formData.append('image', file);
  
      // Save current cursor state
      const range = quill.getSelection(true);
  
      // Insert temporary loading placeholder image
      quill.insertEmbed(range.index, 'image', 'https://cdn.dribbble.com/users/1341307/screenshots/5377324/morph_dribbble.gif'); 
  
      // Move cursor to right side of image (easier to continue typing)
      quill.setSelection(range.index + 1);
  
      // THIS NEEDS TO BE HOOKED UP TO AN API
      // RESPONSE DATA WILL THEN BE USED TO EMBED THE IMAGE
      // const res = await apiPostNewsImage(formData);
  
      // Remove placeholder image
      quill.deleteText(range.index, 1);
  
      let url = ''
      try {
        console.info('before');
        await axios.post('/api/v1/answer/upload-image',formData).then(res => {
          url = res.data.url
        })
      } catch (err) {
        console.error('error', err)
      }
      // Insert uploaded image
      if(url){
          quill.insertEmbed(range.index, 'image', url);
      }
  
    }
  }
  
  export default {
    props:['selectAnswer','formOnly'],
    components:{
      DataTable,
      quillEditor
    },
    data(){
      return {
        knowledgeBase:[],
        step: 'list',
        params:{
          rubric_table:[
            {
              name:"Component 1",
              max_score:10,
              breakdown:[
                {
                  from_score:1,
                  to_score:10,
                  description:"Description 1"
                }
              ]
            }
          ]
        },
        loading:false,
        loadingFetch:false,
        fields: [
           {
            label: 'Name',
            key: 'answer',
            sortable: true,
          },
           {
            label: 'Created at',
            key: 'created_at',
            tdAttr: {
              style: 'width: 15%'
            },
            sortable: true,
            formatter:function(value){
              return moment(value).format('YYYY-MM-DD HH:mm')
            }
          },
          {
            key: 'action',
            tdAttr: {
              style: 'width: 15%'
            }
          }
        ],
        refresh: 0,
        filter:{},
         editorOption: {
          modules: {
              toolbar: {
                container:[
                  ['image'],
                  [{ 'color': [] }, { 'background': [] },{ 'align': [] }],                           
                  ['bold', 'italic', 'underline', 'strike'],     
                  // ['blockquote', 'code-block'],
  
                  // [{ 'header': 1 }, { 'header': 2 }],          
                  [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'size': ['small', false, 'large', 'huge'] },{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                  // [{ 'script': 'sub'}, { 'script': 'super' }],  
                  // [{ 'indent': '-1'}, { 'indent': '+1' }],          
                  // [{ 'direction': 'rtl' }],    
              ],
              handlers:{
                image:imageHandler
              }
            }
          },
          placeholder: 'Compose an epic...',
          theme: 'snow'
        },
      }
    },
    methods:{
      onEditorChange({ quill, html, text }) {
        this.params.answer = html
      },
      addCriteria(index){
        this.params.rubric_table[index].breakdown.push({
          description:"",
          from_score:1,
          to_score:10
        })
      },
      deleteCriteria(index,index2){
        this.params.rubric_table[index].breakdown.splice(index2,1)
      },
      addComponent(){
        this.params.rubric_table.push({
          name:"",
          max_score:10,
          breakdown:[
            {
              from_score:1,
              to_score:10,
              description:"Description 1"
            }
          ]
        })
      },
      deleteComponent(index){
        this.params.rubric_table.splice(index,1)
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
        this.loading = true
        try {
          const response2 = await axios.post(`/api/v1/answer/add`,this.params)
          this.$root.toast('Answer Created!','success')
          this.loading = false
          if(this.formOnly){
            $('.modal').modal('hide')
          }else{
            this.step = 'list'
            this.refresh += 1
          }
         
        } catch (error) {
          console.log(error)
          this.$root.toast(error.response.message,'error')
          this.loading = false
          
        }
      }
    },
    mounted(){
      if(this.formOnly){
        this.step = 'create'
      }
    }
  }
  </script>