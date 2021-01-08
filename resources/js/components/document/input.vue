<template>
  <div style="width: 100%;">
    <div v-if="file==null " class="el-upload el-upload--picture">
      <div
        :class="`el-upload-dragger ${(state==false)?'error':''}`"
        @click="$refs.file.click()"
        @drop.prevent="addFile"
        @dragover.prevent
      >
        <i class="el-icon-upload icon-load"></i>
        <div class="text-load">
          <em>haz clic para cargar archivo</em>
        </div>
      </div>
      <input
        type="file"
        name="file"
        ref="file"
        accept=".pdf, image/jpeg, image/png, .doc, .docx"
        class="el-upload__input"
        @input.prevent="getFile"
      />
    </div>
    <div v-else class="content-a" @mouseover=" hover = true " @mouseleave=" hover = false ">
      <div
        v-if="hover && $truthty($props.fileExt)"
        class="corner d-flex align-items-end justify-content-center"
        @click.prevent="deleteFile"
      >
        <i class="el-icon-close icon-corner"></i>
      </div>
      <div
        v-else-if="hover"
        class="corner d-flex align-items-end justify-content-center"
        @click.prevent="deleteFile"
      >
        <i class="el-icon-close icon-corner"></i>
      </div>
      <div
        v-else
        :class="'corner bg-'+$props.color+' d-flex align-items-end justify-content-center'"
      >
        <i :class="$props.icon+' icon-corner'"></i>
      </div>

      <div class="el-upload-list__item-name d-flex align-items-center">
        <i class="el-icon-document icon-doc"></i>
        <a
          v-if="$truthty($props.fileExt) && !$props.newFile"
          class="el-upload-list__item-name text-prev"
          :href="'/documents/download/'+$props.fileExt.id"
          target="_blank"
        >{{file.type.name}}</a>
        <a v-else>{{file.name}}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["name", "icon", "color", "fileExt","newFile", "state"],
  data() {
    return {
      hover: false,
      isDragging: false,
      file: (this.$props.fileExt ||this.$props.fileExt!=null )? this.$props.fileExt : null,
    };
  },
  methods: {
    getFile($e) {
      this.file = $e.target.files[0];
      this.$emit("input", this.file);
      $e.target.value = "";
    },
    deleteFile($e) {
      this.file = null;
      this.$emit("input", null);
    },
    addFile($e) {
      let droppedFiles = $e.dataTransfer.files;
      if (!droppedFiles) return;
      let accept = ".pdf, image/jpeg, image/png, .doc, .docx";
      this.file = droppedFiles[0];
      this.$emit("input", this.file);
    },
  },
};
</script>