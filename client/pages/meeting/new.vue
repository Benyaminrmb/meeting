<template>
  <div class="col-span-10 col-start-2 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="w-100">
      <form action="#" class="w-100 " @submit.prevent="submitMeeting" method="POST">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">


            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">
                نام جلسه
              </label>
              <div class="mt-1">
                <input v-model="form.name"
                       id="name" name="name" type="text" required
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              </div>
            </div>

            <div class="form-control">
              <label class="label">
                <span class="label-text">تاریخ جلسه</span>
              </label>

              <client-only>
                <date-picker simple class="w-100" v-model="form.date"></date-picker>
              </client-only>
            </div>


            <div class="form-control">
              <label class="label">
                <span class="label-text">Username</span>
              </label>
              <client-only placeholder="loading...">
                <ckeditor-nuxt v-model="form.data" :config="editorConfig"/>
              </client-only>

            </div>


            <file-upload v-if="data.meeting_id"
              class="btn w-100 btn-primary dropdown-toggle"
              :post-action="postAction"
              :multiple="true"
              :extensions="extensions"
              :accept="accept"
              :directory="directory"
              :create-directory="createDirectory"
              :size="size || 0"
              :thread="thread < 1 ? 1 : (thread > 5 ? 5 : thread)"
              :headers="headers"
              :data="data"
              :drop="drop"
              :drop-directory="dropDirectory"
              :add-index="addIndex"
              v-model="files"
              @input-filter="inputFilter"
              @input-file="inputFile"
              ref="upload">
              <i class="fa fa-plus"></i>
              Select
            </file-upload>


            <div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
              <h3>Drop files to upload</h3>
            </div>
            <div v-if="data.meeting_id" class="upload" v-show="!isOption">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Thumb</th>
                    <th>Name</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>Size</th>
                    <th>Speed</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-if="!files.length">
                    <td colspan="9">
                      <div class="text-center p-5">
                        <h4>Drop files anywhere to upload<br/>or</h4>
                        <label :for="name" class="btn btn-lg btn-primary">Select Files</label>
                      </div>
                    </td>
                  </tr>
                  <tr v-for="(file, index) in files" :key="file.id">
                    <td>{{ index }}</td>
                    <td>
                      <img class="td-image-thumb" v-if="file.thumb" :src="file.thumb"/>
                      <span v-else>No Image</span>
                    </td>
                    <td>
                      <div class="filename">
                        {{ file.name }}
                      </div>
                      <div class="progress" v-if="file.active || file.progress !== '0.00'">
                        <div
                          :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}"
                          role="progressbar" :style="{width: file.progress + '%'}">{{ file.progress }}%
                        </div>
                      </div>
                    </td>
                    <td>{{ file.width || 0 }}</td>
                    <td>{{ file.height || 0 }}</td>
                    <td>{{ formatSize(file.size) }}</td>
                    <td>{{ formatSize(file.speed) }}</td>

                    <td v-if="file.error">{{ file.error }}</td>
                    <td v-else-if="file.success">success</td>
                    <td v-else-if="file.active">active</td>
                    <td v-else></td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button">
                          Action
                        </button>
                        <div class="dropdown-menu">
                          <a
                            :class="{'dropdown-item': true, disabled: file.active || file.success || file.error === 'compressing' || file.error === 'image parsing'}"
                            href="#"
                            @click.prevent="file.active || file.success || file.error === 'compressing' ? false :  onEditFileShow(file)">Edit</a>
                          <a :class="{'dropdown-item': true, disabled: !file.active}" href="#"
                             @click.prevent="file.active ? $refs.upload.update(file, {error: 'cancel'}) : false">Cancel</a>

                          <a class="dropdown-item" href="#" v-if="file.active"
                             @click.prevent="$refs.upload.update(file, {active: false})">Abort</a>
                          <a class="dropdown-item" href="#"
                             v-else-if="file.error && file.error !== 'compressing' && file.error !== 'image parsing' && $refs.upload.features.html5"
                             @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})">Retry
                            upload</a>
                          <a
                            :class="{'dropdown-item': true, disabled: file.success || file.error === 'compressing' || file.error === 'image parsing'}"
                            href="#" v-else
                            @click.prevent="file.success || file.error === 'compressing' || file.error === 'image parsing' ? false : $refs.upload.update(file, {active: true})">Upload</a>

                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#" @click.prevent="$refs.upload.remove(file)">Remove</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>

              <button type="button" class="btn btn-success" v-if="!$refs.upload || !$refs.upload.active"
                      @click.prevent="$refs.upload.active = true">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                Start Upload
              </button>
              <button type="button" class="btn btn-danger" v-else @click.prevent="$refs.upload.active = false">
                <i class="fa fa-stop" aria-hidden="true"></i>
                Stop Upload
              </button>

            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
              <button type="submit"
                      class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ثبت
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>


  </div>
</template>
<script>


export default {
  middleware: "checkAuth",
  components: {
    'date-picker': () => {
      if (process.client) {
        return import('vue-persian-datetime-picker')
      }
    },
    'ckeditor-nuxt': () => {
      if (process.client) {
        return import('@blowstack/ckeditor-nuxt')
      }
    },
  },
  data() {
    return {
      selectedFiles: [],
      files: [],
      accept: 'image/png,image/gif,image/jpeg,image/webp,video/mp4',
      extensions: 'gif,jpg,jpeg,png,webp,mp4',
      // extensions: ['gif', 'jpg', 'jpeg','png', 'webp'],
      // extensions: /\.(gif|jpe?g|png|webp)$/i,
      minSize: 1024,
      size: 1024 * 1024 * 10,
      directory: false,
      drop: true,
      dropDirectory: true,
      createDirectory: false,
      addIndex: false,
      thread: 3,
      name: 'file',
      postAction: 'http://localhost:8000/api/upload',
      data: {
        meeting_id: ""
      },
      headers: {
        'Authorization': "Bearer " + this.$cookies.get('token'),
        'X-Requested-With': 'XMLHttpRequest'
      },
      autoCompress: 1024 * 1024,
      uploadAuto: false,
      isOption: false,


      form: {
        name: '',
        date: '',
        data: '',
      },
      editorConfig: {
        input: 'file',
        name: 'file',
        title: {
          placeholder: 'h1'
        },
        simpleUpload: {
          input: 'file',
          name: 'file',
          uploadUrl: 'http://localhost:8000/api/upload',
          headers: {
            'Authorization': "Bearer " + this.$cookies.get('token'),
            'X-Requested-With': 'XMLHttpRequest'
          }
        }
      },

    }
  },
  methods: {
    submitMeeting() {
      this.$store.dispatch('meeting/meeting/newMeeting', {form: this.form})
        .then(response => {
          console.log(response)
        })
    },
    inputFilter(newFile, oldFile, prevent) {

      if (newFile && !oldFile) {
        // Before adding a file
        // 添加文件前
        // Filter system files or hide files
        // 过滤系统文件 和隐藏文件
        if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
          return prevent()
        }
        // Filter php html js file
        // 过滤 php html js 文件
        if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
          return prevent()
        }
        // Automatic compression
        // 自动压缩
        if (newFile.file && newFile.error === "" && newFile.type.substr(0, 6) === 'image/' && this.autoCompress > 0 && this.autoCompress < newFile.size) {
          newFile.error = 'compressing'
          const imageCompressor = new ImageCompressor(null, {
            convertSize: 1024 * 1024,
            maxWidth: 512,
            maxHeight: 512,
          })
          imageCompressor.compress(newFile.file)
            .then((file) => {
              this.$refs.upload.update(newFile, {error: '', file, size: file.size, type: file.type})
            })
            .catch((err) => {
              this.$refs.upload.update(newFile, {error: err.message || 'compress'})
            })
        }
      }
      if (newFile && newFile.error === "" && newFile.file && (!oldFile || newFile.file !== oldFile.file)) {
        // Create a blob field
        // 创建 blob 字段
        newFile.blob = ''
        let URL = (window.URL || window.webkitURL)
        if (URL) {
          newFile.blob = URL.createObjectURL(newFile.file)
        }
        // Thumbnails
        // 缩略图
        newFile.thumb = ''
        if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
          newFile.thumb = newFile.blob
        }
      }
      // image size
      // image 尺寸
      if (newFile && newFile.error === '' && newFile.type.substr(0, 6) === "image/" && newFile.blob && (!oldFile || newFile.blob !== oldFile.blob)) {
        newFile.error = 'image parsing'
        let img = new Image();
        img.onload = () => {
          this.$refs.upload.update(newFile, {error: '', height: img.height, width: img.width})
        }
        img.οnerrοr = (e) => {
          this.$refs.upload.update(newFile, {error: 'parsing image size'})
        }
        img.src = newFile.blob
      }
    },
    // add, update, remove File Event
    inputFile(newFile, oldFile) {
      if (newFile && oldFile) {
        // update
        if (newFile.active && !oldFile.active) {
          // beforeSend
          // min size
          if (newFile.size >= 0 && this.minSize > 0 && newFile.size < this.minSize) {
            this.$refs.upload.update(newFile, {error: 'size'})
          }
        }
        if (newFile.progress !== oldFile.progress) {
          // progress
        }
        if (newFile.error && !oldFile.error) {
          // error
        }
        if (newFile.success && !oldFile.success) {
          // success
        }
      }
      if (!newFile && oldFile) {
        // remove
        if (oldFile.success && oldFile.response.id) {
          // $.ajax({
          //   type: 'DELETE',
          //   url: '/upload/delete?id=' + oldFile.response.id,
          // })
        }
      }
      // Automatically activate upload
      if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        if (this.uploadAuto && !this.$refs.upload.active) {
          this.$refs.upload.active = true
        }
      }
    },
    alert(message) {
      alert(message)
    },
    onEditFileShow(file) {
      this.editFile = {...file, show: true}
      this.$refs.upload.update(file, {error: 'edit'})
    },
    onEditorFile() {
      if (!this.$refs.upload.features.html5) {
        this.alert('Your browser does not support')
        this.editFile.show = false
        return
      }
      let data = {
        name: this.editFile.name,
        error: '',
      }
      if (this.editFile.cropper) {
        let binStr = atob(this.editFile.cropper.getCroppedCanvas().toDataURL(this.editFile.type).split(',')[1])
        let arr = new Uint8Array(binStr.length)
        for (let i = 0; i < binStr.length; i++) {
          arr[i] = binStr.charCodeAt(i)
        }
        data.file = new File([arr], data.name, {type: this.editFile.type})
        data.size = data.file.size
      }
      this.$refs.upload.update(this.editFile.id, data)
      this.editFile.error = ''
      this.editFile.show = false
    },
    // add folder
    onAddFolder() {
      if (!this.$refs.upload.features.directory) {
        this.alert('Your browser does not support')
        return
      }
      let input = document.createElement('input')
      input.style = "background: rgba(255, 255, 255, 0);overflow: hidden;position: fixed;width: 1px;height: 1px;z-index: -1;opacity: 0;"
      input.type = 'file'
      input.setAttribute('allowdirs', true)
      input.setAttribute('directory', true)
      input.setAttribute('webkitdirectory', true)
      input.multiple = true
      document.querySelector("body").appendChild(input)
      input.click()
      input.onchange = (e) => {
        this.$refs.upload.addInputFile(input).then(function () {
          document.querySelector("body").removeChild(input)
        })
      }
    },
    onAddData() {
      this.addData.show = false
      if (!this.$refs.upload.features.html5) {
        this.alert('Your browser does not support')
        return
      }
      let file = new window.File([this.addData.content], this.addData.name, {
        type: this.addData.type,
      })
      this.$refs.upload.add(file)
    }
  }
}
</script>
