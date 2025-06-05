<template>
  <div class="panel_math_quill" :class="{oq:$route.name.includes('onlinequiz')}"  >
   
    <div class="card-columns">
      <div class="card shadow-1">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4>Math Keyboard</h4>
            <div class="remove-mathquill-panel" @click="HideMathQuill">
              <i class="fe fe-x-square"></i>
            </div>
          </div>
        </div>
        <div class="card-body-small">
          <input type="hidden" id="mathTarget" value="" />
          <div id="keyboard">
            <div role="group" aria-label="math functions" class="functions">
              <div class="row">
                <div class="col-12">
                  <button type="button" class="btn btn-default" @click="input(['\\hat{A}'])">
                    \(\hat{A}\)
                  </button>
                  <button type="button" class="btn btn-default" @click="input(['\\widehat{ABC}'])">
                    \(\widehat{ABC}\)
                  </button>
                  <button type="button" class="btn btn-default" @click="input(['\\frac'])">
                    \(\frac{x}{y}\)
                  </button>
                  <button type="button" class="btn btn-default" @click="input(['^\\circ'])">
                    \(\circ\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\angle'])"
                  >
                    \(\angle\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\^','2'])"
                  >
                    \(x^2\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\^','{}'])"
                  >
                    \(x^n\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\times'])"
                  >
                    \(\times\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\div'])"
                  >
                    \(\div\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input([' /'])"
                  >
                    \(/\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['m/s'])"
                  >
                    \(m/s\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['^\\circ C'])"
                  >
                    \(^\circ\)C
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\sqrt'])"
                  >
                    \(\sqrt{a}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\pi'])"
                  >
                    \(\pi\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['ℓ'])"
                  >
                    ℓ
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\Omega'])"
                  >
                    \(\Omega\)
                  </button>
                  <button
                    v-if="!inWhiteboard"
                    id="line-break"
                    type="button"
                    class="btn btn-default"
                    @click= "input(['<', 'br', '>'])"
                  >
                    Linebreak
                  </button>
                  <button
                    v-if="inWhiteboard"
                    id="line-break"
                    type="button"
                    class="btn btn-default"
                    @click= "input(['\\\\'])"
                  >
                    Linebreak
                  </button>

                  <!-- LOAD MORE -->
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\backslash'])"
                  >
                    \(\backslash\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\Leftrightarrow'])"
                  >
                    \(\Leftrightarrow\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\approx'])"
                  >
                    \(\approx\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\perp'])"
                  >
                    \(\perp\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\triangle'])"
                  >
                    \(\triangle\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\|'])"
                  >
                    \(||\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['¢'])"
                  >
                    &cent
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\text', '_____'])"
                  >
                    _____
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\geq'])"
                  >
                    \(\geq\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\leq'])"
                  >
                    \(\leq\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['>'])"
                  >
                    \(>\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['<'])"
                  >
                    \(&lt;\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['_', '2'])"
                  >
                    \(x_{2}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['_', '{}'])"
                  >
                    \(x_{n}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\overrightarrow', '\\rm', 'AB'])"
                  >
                    \(\overrightarrow{\rm AB}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\binom{-5}{2}'])"
                  >
                    \(\binom{-5}{2}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\Theta'])"
                  >
                    \(\Theta\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\pm'])"
                  >
                    \(\pm\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['km/h'])"
                  >
                    \(km/h\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['kg/m', '^', '3'])"
                  >
                    \(kg/m^3\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['g/cm', '^', '3'])"
                  >
                    \(g/cm^3\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\int'])"
                  >
                    \(\int\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['|', 'Z', '|'])"
                  >
                    \(|Z|\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\alpha'])"
                  >
                    \(\alpha\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\beta'])"
                  >
                    \(\beta\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\cup'])"
                  >
                    \(\cup\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\cap'])"
                  >
                    \(\cap\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\in'])"
                  >
                    \(\in\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\notin'])"
                  >
                    \(\notin\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\subset'])"
                  >
                    \(\subset\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\subseteq'])"
                  >
                    \(\subseteq\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\not\subset'])"
                  >
                    \(\not\subset\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['0.', '\\dot', '9'])"
                  >
                    \(0.\dot{9}\)
                  </button>
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="input(['\\overline', '0.333'])"
                  >
                    \(\overline{0.333}\)
                  </button>
                  <button type="button" class="btn btn-default" @click='input(["\\Rightarrow"])'>\(\Rightarrow\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\equiv"])'>\(\equiv\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\neq"])'>\(\neq\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\therefore"])'>\(\therefore\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\because"])'>\(\because\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\rightleftharpoons"])'>\(\rightleftharpoons\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\uparrow"])'>\(\uparrow\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\to"])'>\(\rightarrow\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\gets"])'>\(\leftarrow\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\downarrow"])'>\(\downarrow\)</button>
                  <button type="button" class="btn btn-default" @click='input(["\\parallel"])'>\(\parallel\)</button>
                  <button type="button" class="btn btn-default" @click="input(['\\nthroot','n'])">\(\sqrt[n]{x}\)</button>
                  <button type="button" class="btn btn-default" @click="input(['\\begin{pmatrix} {x} & a_{1,2} & a_{1,3} \\\\ a_{2,1} & a_{2,2} & a_{2,3} \\\\ a_{3,1} & a_{3,2} & a_{3,3} \\end{pmatrix}'])">
                  \begin{pmatrix}
                  a & b \\
                  c & d
                  \end{pmatrix}
                  </button>
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
// import periodicTableVue from './periodic-table.vue';
import iink from 'iink-js'

export default {
  name: 'MathQuill',
  components: {
        // periodicTableVue,
  },
  props: {
    onlinequiz: {
      type: Boolean,
      default: false,
    },
    inWhiteboard: {
      type: Boolean,
      default: false,
    },
  },
  data(){
    return{
      branch_config: {},
      showPeriodic: false,
      editor: null, // Myscript editor
      editorResult: null, // Store response of the editor, used to check myscript state
      matrixDimensions: '',
      matrixValue: [],
      angleName: '',
    }

  },
  computed: {
    matrixRow(){
      const matrixArray = this.matrixDimensions.split(",");
      return matrixArray[0] ? parseInt(matrixArray[0]) : 0;
    },
    matrixColumn(){
      const matrixArray = this.matrixDimensions.split(",");
      return matrixArray[1] ? parseInt(matrixArray[1]) : 0;
    },

  },
  watch:{
    matrixRow(newValue){
      this.matrixValue = []
      for(let i=0; i<=newValue; i++){
        this.matrixValue.push(new Array());
      }
    }
  },
  mounted() {
    MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
  },
  methods: {
    input(latex) {
      const specialMQ = ['\\rightleftharpoons', '\\binom{-5}{2}', '^\\circ C' , '^\\circ', '\\rightarrow_{a}', '\\rightleftarrows',
      '{}']
      const MQ = MathQuill.getInterface(2)
      const spanId = $('#mathTarget').val()
      const spanTarget = document.getElementById(spanId)
      console.log(spanId)
      const mathSpan = MQ(spanTarget)

      latex.map((e) => {
        if(e.includes('pmatrix')){
          this.matrixDimensions = ''
          $('.matrix-container').css('display', 'block');
          $('.panel_math_quill .tabs').css('display', 'none');
          $('#inputmatrix').focus()
          return false

        }

        if(e.includes('\\widehat{ABC}') || e.includes('\\hat{A}')){
          this.angleName = ''
          this.$refs.anglePreview.innerHTML = '';
          $('.angle-container').css('display', 'block');
          $('.panel_math_quill .tabs').css('display', 'none');
          $('#angleName').focus()
          return false
        }

        if (e === '\\times') {
          // Add space before and after \times
          mathSpan.write('\\ \\times\\ ');
        }else if(specialMQ.includes(e)){
          mathSpan.write(e)
        }else{
          mathSpan.cmd(e)
        }
      })
      mathSpan.focus()
    },
    HideMathQuill() {
      $('.panel_math_quill').hide()
    },
    reverseApplyMathJaxFormat(text) {
      if (isNaN(text)) {
        text = text.replace('\\(', '')
        text = text.replace('\\)', '')
        text = text.replace('\\{', '\\left\\{')
        text = text.replace('\\}', '\\right\\}')
        return text
      } else {
        return text
      }
    },

    // Myscript Functions
    initMathPad() {
      const editorElement = this.$refs.editor;
      const MQPrev = MathQuill.getInterface(2)
      const prevSpanId = document.getElementById('mys-preview')
      const previewMathField = MQPrev(prevSpanId)
      this.editor = iink.register(editorElement, {
        recognitionParams: {
          type: 'MATH',
          protocol: 'WEBSOCKET',
          apiVersion: 'V4',
          server: {
            scheme: 'https',
            host: 'cloud.myscript.com',
            applicationKey: '2a3cad03-6ec9-4b7a-8c3f-379e499cf9fb',
            hmacKey: '95cea703-92e5-43b9-8a07-21e97f27ad77'
          },
          iink: {
              math: {
                  mimeTypes: ["application/x-latex", "application/mathml+xml"]
              }
          }
        }
      });
      editorElement.addEventListener("exported", event => {
        let mathResult = event.detail.exports["application/x-latex"];
        this.editorResult = mathResult; // It's to check need to reinitiate or not
        const previewSpanTarget = document.getElementById('mys-preview')
        const MQPrev = MathQuill.getInterface(2)
        $('.mys-prevbar').show();
        if (previewSpanTarget != null) {
          previewSpanTarget.innerHTML = mathResult ? this.reverseApplyMathJaxFormat(mathResult) : null
          const answerMathField = MQPrev.MathField(previewSpanTarget);
        }
      });
    },
    clear() {
      if (this.editorResult != '') {
        this.editor.clear();
      } else {
        console.log('No action to clear')
      }
    },
    undo() {
      this.editor.undo();
    },
    redo() {
      this.editor.redo();
    },
    closeEditor() {
      if (this.editor) {
        this.editor.close();
        this.editor = null;
      }
    },
    pasteToInput() {
      const MQ = MathQuill.getInterface(2)
      this.spanId = $('#mathTarget').val()
      const spanTarget = document.getElementById(this.spanId)
      spanTarget.innerHTML = this.editorResult ? this.reverseApplyMathJaxFormat(this.editorResult) : ''
      const answerMathField = MQ.MathField(spanTarget);
      setTimeout(() => {
          answerMathField.focus()
      },100);
      this.HideMathQuill();
    },
    resetMathPad() {
      console.log("Resetting MathPad")
      // This condition makes the Myscript loaded once and keep the scrible exist
      if (this.editorResult == null) {
        this.initMathPad();
      }
    },
    validateMatrixValue(){
      let empty = false
      if(!this.matrixDimensions.includes(',')){
        this.$toast.open({
          message: `Enter the array dimensions separated by a comma.` ,
          type: 'warning',
          position: 'top',
          duration: 5000,
          dismissible: true,
        })

        empty = true
      }

      const _this = this
      $('.matrixValue').each(function(){
          if($(this).val() == ''){
            _this.$toast.open({
              message: `please fill all matrix value` ,
              type: 'warning',
              position: 'top',
              duration: 5000,
              dismissible: true,
            })

            empty = true
            return false
          }
      });
      if(!empty){
        $('.panel_math_quill .tabs').css('display', 'block');
        $('.matrix-container').css('display', 'none');
        const MQ = MathQuill.getInterface(2)
        const spanId = $('#mathTarget').val()
        const spanTarget = document.getElementById(spanId)
        const mathSpan = MQ(spanTarget)
        let matrixFormula = '\\begin{pmatrix}'

        for(const row in this.matrixValue){
          if(row > 0){
            for(const col in this.matrixValue[row]){
              if(col > 0){
                const andSymbol = col < this.matrixValue[row].length-1 ? '&' : ''
                matrixFormula += this.matrixValue[row][col] + andSymbol
              }
            }
            matrixFormula += row < this.matrixValue.length-1 ? '\\\\' : ''
          }
        }
        // {x} & a_{1,2} & a_{1,3} \\\\ a_{2,1} & a_{2,2} & a_{2,3} \\\\ a_{3,1} & a_{3,2} & a_{3,3}
        matrixFormula += '\\end{pmatrix}'

        mathSpan.cmd(matrixFormula)
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
      }

    },
    anglePreview() {
      let formattedAngleName = `\\(\\widehat{${this.angleName}}\\)`;
      if(this.angleName.length == 1) formattedAngleName = `\\(\\hat{${this.angleName}}\\)`;
      this.$refs.anglePreview.innerHTML = formattedAngleName;
      MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
    },
    inputAngle(){
      $('.panel_math_quill .tabs').css('display', 'block');
      $('.angle-container').css('display', 'none');
      const MQ = MathQuill.getInterface(2)
      const spanId = $('#mathTarget').val()
      const spanTarget = document.getElementById(spanId)
      const mathSpan = MQ(spanTarget)
      if(this.angleName.length == 1){
        mathSpan.cmd(`\\(\\hat{${this.angleName}}\\)`)
      }else{
        mathSpan.cmd(`\\(\\widehat{${this.angleName}}\\)`)
      }
      MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
    },
    goBack(){
      $('.panel_math_quill .tabs').css('display', 'block');
      $('.matrix-container').css('display', 'none');
      $('.angle-container').css('display', 'none');
    }
  },
}
</script>

<style scoped>
.mys-pad {
  border: 1px solid rgb(225, 225, 225);
  background: rgb(246, 248, 250);
  text-align: left;
}
.remove-math-htr {
  position: absolute;
  right: 10px;
  font-size: 21px;
  color: red;
  cursor: pointer;
  z-index: 10;
  transform: translateY(-50%);
}
.mys-control {
  background-color: #0FB0F8;
  display: inline-block;
  padding: 10px;
  padding-top: 5px;
  padding-bottom: 5px;
  border-radius: 5px;
}
.mys-prevbar {
  display: inline-block;
}
.mys-button {
  display: inline-block;
}
.writing-container {
  background-color: white;
  min-height: 250px;
  margin-bottom: 15px;
}
</style>
