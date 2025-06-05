<template>
 <div class="col-12 d-flex align-items-center flex-wrap">
  <button class="btn btn-primary" @click="startRecord" v-if="!recording">Record</button>
  <button class="btn btn-primary bg-danger" @click="stopRecord" v-else>Stop</button>

  <b class="ms-3 text-danger" v-if="recording"><i>Record in progress ...</i></b>
  <b class="ms-3 text-success" v-if="uploading"><i>Uploading audio ...</i></b>
  <template v-if="!recording && !uploading">
    <b class="ms-3" v-if="!audio"><i>No record yet</i></b>
    <template v-else>
      <b-spinner
        small
        variant="primary"
        class="ms-3"
        v-if="loadingPresignedUrl"
      ></b-spinner>
      <audio :key="`refresh-audio-${refreshAudio}`" controls class="pe-0 ms-sm-3 ms-md-3 ms-lg-3 pt-3 pt-sm-0 pt-md-0 pt-lg-0" v-else>
        <source :src="presignedUrl">
        {{ $t('Your browser does not support the audio element(dot)') }}
      </audio>
    </template>
  </template>
</div>
</template>

<script>
window.MediaRecorder = OpusMediaRecorder;

import axios from 'axios'

export default {
  name: 'AudioRecord',
  props: {
    onStop: {
      type: Function,
      default: () => {}
    },
    value:{
      type: String,
      default: ''
    },
    uploading:{
      type: Boolean,
      default: false
    },
    presignedUrl: {
      type: String,
      default: ''
    },
  },
  data() {
    // Polyfill MediaRecorder
    return {
      recorder: null,
      recording: false,
      audio:null,
      workerOptions:{
        OggOpusEncoderWasmPath: 'https://cdn.jsdelivr.net/npm/opus-media-recorder@latest/OggOpusEncoder.wasm',
        WebMOpusEncoderWasmPath: 'https://cdn.jsdelivr.net/npm/opus-media-recorder@latest/WebMOpusEncoder.wasm'
      },
      refreshAudio: 0,
      loadingPresignedUrl: false,
    }
  },
  watch:{
    value: function(){
      this.audio = this.value
      if(this.presignedUrl == '' || !this.presignedUrl) this.getPresignedUrl(this.value)
    }
  },
  methods:{
    startRecord(){
      navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
        let options = { mimeType: 'audio/wave' };
        // Start recording
        this.recorder = new MediaRecorder(stream, options, this.workerOptions);
        this.recorder.start();
        this.recording = true
        // Set record to <audio> when recording will be finished
        var em = this
        this.recorder.addEventListener('dataavailable', (e) => {
          em.audio = URL.createObjectURL(e.data);
          em.onStop(e.data)
        });
      });
    },
    stopRecord(){
      this.recorder.stop()
      this.recorder.stream.getTracks().forEach(i => i.stop());
      this.recording = false
    },
    getPresignedUrl(filename){
      this.loadingPresignedUrl = true
      axios
        .get('/api/quiz/get-audio-presigned-url', {
          params: {
            filename: filename
          }
        })
        .then((e) => {
          this.presignedUrl = e.data.presignedUrl
          this.loadingPresignedUrl = false
          this.refreshAudio++
        })
    },
  }
}
</script>
