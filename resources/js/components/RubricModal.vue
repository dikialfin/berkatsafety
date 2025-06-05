<template>
  <div class="modal fade" tabindex="-1" id="modalRubric">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Rubric Library</h2>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body ">
          <template v-if="loading">
            <div class="row align-items-center justify-content-center" style="min-height:600px">
              <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                </div>
                <p>Converting rubric, please wait...</p>
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
                    <button class="btn btn-primary" @click="step = 'create'">Create Rubric</button>
                </div>
              </div>
              <div class="row">
                <data-table
                    :fields="fields"
                    :url="'/api/v1/rubric/data'"
                    :param="{
                      ...filter,
                    }"
                    :refresh="refresh"
                    :filterable="false"
                  > 
                   <template #action="action">
                     <button class="btn btn-white" @click="selectRubric(action.action.item)">
                      Select Rubric
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
                    <p class="mb-0 fw-bold">Create Rubric</p>
                  </div>
                  <hr>
                </div>
              </div>
              <div class="row">
                <form  @submit.prevent="(e) => submitDesign(e)" >
                  <div class="row align-items-center">
                    <div class="col-lg-4">
                      <p class="mb-0 fw-bold">Rubric Name <span class="text-danger">*</span></p>
                    </div>
                    <div class="col-lg-8">
                      <input class="form-control" name="name" v-model="params.name" required>
                    </div>
                    <div class="col-12">
                      <hr>
                    </div>
                  </div>
                  <div class="card" v-for="rubric,index in params.rubric_table" :key="`rubric${index}`">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-lg-3">
                          <p class="mb-0 fw-bold">Component <span class="text-danger">*</span></p>
                        </div>
                        <div class="col-lg-4 d-flex gap-3">
                          <input class="form-control" name="name" v-model="rubric.name" required>
                        </div>
                         <div class="col-lg-2">
                          <p class="mb-0 fw-bold">Max Mark <span class="text-danger">*</span></p>
                        </div>
                        <div class="col-lg-3 d-flex gap-3">
                          <input class="form-control" type="number" v-model="rubric.max_score" required>
                          <button class="btn btn-trans text-danger" type="button" @click="deleteComponent(index)" v-if="params.rubric_table.length > 1">
                            <i class="fe fe-trash"></i>
                          </button>
                        </div>
                      </div>
                      <div class="row align-items-center mt-3">
                        <div class="col-lg-3">
                          <p class="mb-0 fw-bold">Score Type <span class="text-danger">*</span></p>
                        </div>
                         <div class="col-lg-4 d-flex gap-3">
                          <select class="form-select" name="name" v-model="rubric.score_type" required>
                            <option value="range">Range</option>
                            <option value="fixed">Fixed</option>
                          </select>
                        </div>
                      </div>
                      <div class="row align-items-center">
                        <div class="col-12">
                          <hr>
                        </div>
                      </div>
                       <div class="row align-items-center mb-4" v-for="breakdown,ind in rubric.breakdown" :key="`rubric${index}breakdown${ind}`">
                        <div class="col-lg-3">
                          <p class="mb-0 fw-bold">Criteria <span class="text-danger">*</span></p>
                        </div>
                        <div class="col-lg-4">
                          <textarea class="form-control" name="name" v-model="breakdown.description" required></textarea>
                        </div>
                        <div class="col-lg-2">
                          <p class="mb-0 fw-bold">Mark <span class="text-danger">*</span></p>
                        </div>
                        <div class="col-lg-3 d-flex gap-2">
                          <input class="form-control" type="number" v-model="breakdown.from_score" required v-if="rubric.score_type == 'range'">
                          <input class="form-control" type="number" v-model="breakdown.to_score" required>
                          <button class="btn btn-trans text-danger" type="button" @click="deleteCriteria(index,ind)" v-if="rubric.breakdown.length > 1">
                            <i class="fe fe-trash"></i>
                          </button>
                        </div>
                       </div>
                        <div class="row align-items-center">
                        <div class="col-12 text-end">
                          <button class="btn btn-white" type="button" @click="addCriteria(index)">
                            <i class="fe fe-plus me-3"></i> Add Criteria
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 text-end">
                      <button class="btn btn-white" type="button" @click="addComponent()">
                        <i class="fe fe-plus me-3"></i> Add Component
                      </button>
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
export default {
  props:['selectRubric','formOnly'],
  components:{
    DataTable
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
            score_type:'range',
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
          key: 'name',
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
      filter:{}
    }
  },
  methods:{
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
        score_type:'range',
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
        const response2 = await axios.post(`/api/v1/rubric/add`,this.params)
        this.$root.toast('Rubric Created!','success')
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