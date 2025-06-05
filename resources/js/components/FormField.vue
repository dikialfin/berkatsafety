<template>
  <label class="mb-1" >{{ field.label }} <span v-if="field.is_required" class="text-danger">*</span></label>
  <template v-if="field.type === 'text'">
    <input
      type="text"
      :class="'form-control'"  
      :required="field.is_required"
      readonly
      :placeholder="field.placeholder"
      :value="getParam(field.key)"
      @input="setParam(field.key, $event.target.value)"
      :name="field.key"
    >
  </template>
  <template v-if="field.type === 'select'">
    <select
      type="text"
      :class="'form-select'"  
      :required="field.is_required"
      readonly
      :placeholder="field.placeholder"
      :value="getParam(field.key)"
      @input="setParam(field.key, $event.target.value)"
      :name="field.key"
    >
      <option v-for="opt,index in field.options" :key="opt.name" :selected="index == 0">
        {{opt.name}}
      </option>
    </select>
  </template>
  <template v-if="field.type === 'textarea'">
    <textarea
      type="text"
      :class="'form-control'"  
      :required="field.is_required"
      readonly
      :placeholder="field.placeholder"
      :value="getParam(field.key)"
      @input="setParam(field.key, $event.target.value)"
      :name="field.key"
    ></textarea>
  </template>
  <template v-if="field.type === 'checkbox'">
    <div class="form-check mt-2" v-for="opt,index in field.options" :key="opt">
      <input class="form-check-input" type="checkbox" :value="opt.name" :id="`checkbox${index}`">
      <label class="form-check-label" :for="`checkbox${index}`">
        {{opt.name}}
      </label>
    </div>
  </template>
  <template v-if="field.type === 'radio'">
    <div class="form-check mt-2" v-for="opt,index in field.options" :key="opt">
      <input class="form-check-input" type="radio" :value="opt.name" name="flexRadioDefault" :id="`flexRadioDefault${index}`">
      <label class="form-check-label" :for="`flexRadioDefault${index}`">
        {{opt.name}}
      </label>
    </div>
  </template>
  <template v-if="field.type === 'date'">
    <input
      type="date"
      :class="'form-control'"  
      :required="field.is_required"
      readonly
      :placeholder="field.placeholder"
      :value="getParam(field.key)"
      @input="setParam(field.key, $event.target.value)"
      :name="field.key"
    >
  </template>
</template>

<script>
import MySelect2 from 'v-select2-component'

export default {
  name: 'FormField',
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
