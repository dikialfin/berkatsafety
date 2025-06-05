<template>
  <div class="row">
    <template v-for="val in field" :key="val.id">
      <div  :class="(val.col ? val.col : 'col-12')+' mb-3'" :hidden="val.hidden">
        <label class="mb-1" >{{ val.label }} <span v-if="val.required" class="text-danger">*</span></label>
        <div :class="(hasError(val.key) ? 'has-validation' : '')">
        <template v-if="val.type === 'text'">
          <input
            type="text"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')"  
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
             :name="val.key"
          >
        </template>
         <template v-if="val.type === 'date'">
          <input
            type="date"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')"  
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
             :name="val.key"
          >
        </template>
        <template v-if="val.type === 'number'">
          <input
            type="number"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')" 
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
             :name="val.key"
          >
        </template>
         <template v-if="val.type === 'textarea'">
          <textarea
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')" 
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
             :name="val.key"
          />
        </template>
        <template v-if="val.type === 'file'">
          <input
            type="file"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')" 
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :name="val.key"
          />
        </template>
        <template v-if="val.type === 'password'">
          <input
            type="password"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')" 
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
          >
        </template>
        <template v-if="val.type === 'email'">
          <input
            type="email"
            :class="'form-control' + (hasError(val.key) ? ' is-invalid' : '')" 
            :required="val.required"
            :disabled="val.disabled"
            :placeholder="val.placeholder"
            :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)"
          >
        </template>
        <template v-if="val.type === 'select2'">
          <v-select
            :options="val.options"
            :value="getParam(val.key)"
            :select="({id, text}) => setParam(val.key, id)"
            :disabled="val.disabled"
            @search="searchSelect"
            :hidden="val.hidden"
          />
        </template>
        <template v-if="val.type === 'phone'">
          <phone-input
            :class="(hasError(val.key) ? ' is-invalid' : '')"
            :inputOptions="{styleClasses:'form-control' + (hasError(val.key) ? ' is-invalid' : '')}"
            :required="val.required"
            :disabled="val.disabled"
            :value="getParam(val.key)"
            :input="(phoneText, phoneObject) => setParam(val.key, phoneObject.number)"
          />
        </template>
        <template v-if="val.type === 'image'">
          <image-input
            ref="pictureInput"
            width="200"
            height="200"
            margin="0"
            accept="image/jpeg,image/png"
            size="10"
            :z-index="1"
            button-class="btn"
            :custom-strings="{
              upload: '<h1>Bummer!</h1>',
              drag: 'Drag a 😺 GIF or GTFO'
            }"
            :change="(value) => setParam(val.key, value)"
          />
        </template>
        <template v-if="val.type === 'checkbox'">
          <div class="form-check mt-2" v-for="opt,index in val.options" :key="opt">
            <input class="form-check-input" type="checkbox" :value="opt.name" :id="`checkbox${index}`" 
              :checked="getParam(val.key).includes(opt.name)"
              @click="setParam(val.key, getParam(val.key).concat([opt.name]))"
            >
            <label class="form-check-label" :for="`checkbox${index}`">
              {{opt.name}}
            </label>
          </div>
        </template>
        <template v-if="val.type === 'radio'">
          <div class="form-check mt-2" v-for="opt,index in val.options" :key="opt">
            <input class="form-check-input" type="radio" :value="opt.name" name="flexRadioDefault" :id="`flexRadioDefault${index}`"
              :checked="getParam(val.key) == opt.name"
              @click="setParam(val.key, opt.name)"
            >
            <label class="form-check-label" :for="`flexRadioDefault${index}`">
              {{opt.name}}
            </label>
          </div>
        </template>
        <template v-if="val.type === 'select'">
          <select class="form-select" :value="getParam(val.key)"
            @input="setParam(val.key, $event.target.value)" >
            <option v-for="opt,index in val.options" :key="`options${index}`">{{opt.name}}</option>
          </select>
        </template>
        <template v-if="hasError(val.key)">
          <div :key="val.id" class="invalid-feedback">
            {{ getError(val.key) }}
          </div>
        </template>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import MySelect2 from 'v-select2-component'

export default {
  name: 'FormBuilder',
  components: {
    MySelect2
  },
  props: {
    field: {
      type: Array,
      default: () => []
    },
    setParam: {
      type: Function,
      default: () => {}
    },
    getParam: {
      type: Function,
      default: () => {}
    },
    dataErrors: {
      type: Array,
      default: () => ([{
        // key: 'name',
        // message: ''
      }])
    }
  },
  methods: {
    hasError(key) {
      const findError = this.dataErrors.findIndex(it=>it.key === key);
      return findError >= 0;
    },
    getError(key) {
      const error = this.dataErrors.find(it=>it.key === key);
      return error ? error.message : null;
    },
    searchSelect(search,loading){
      console.log(search)
    }
  }
}
</script>
