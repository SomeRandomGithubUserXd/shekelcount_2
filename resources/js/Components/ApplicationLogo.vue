<template>
    <div class="flex items-center user-select-none">
        <svg :height="size" style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512" :width="size" x="0px"
             xml:space="preserve"
             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" y="0px">
            <path
                d="M464,160.003h-16v-48c0-8.864-7.168-16-16-16H48c-26.464,0-48,21.536-48,48v288c0,26.464,21.536,48,48,48h416c8.832,0,16-7.168,16-16v-288C480,167.139,472.832,160.003,464,160.003z"
                style="fill:#2196F3;"/>
            <path d="M48,128.003h368v32H48c-8.832,0-16-7.168-16-16S39.168,128.003,48,128.003z" style="fill:#FAFAFA;"/>
            <path
                d="M496,352.003h-96c-26.496,0-48-21.504-48-48l0,0c0-26.528,21.504-48,48-48h96c8.832,0,16,7.168,16,16v64C512,344.835,504.832,352.003,496,352.003z"
                style="fill:#1976D2;"/>
            <path d="M409.888,160.003L350.304,40.835c-3.968-7.904-13.568-11.072-21.472-7.168L76.224,160.003H409.888z"
                  style="fill:#4CAF50;"
            />
            <path d="M32,160.003h313.888L286.304,40.835c-3.968-7.904-13.568-11.072-21.472-7.136L32,150.115V160.003z"
                  style="fill:#81C784;"/>
        </svg>
        <h3 v-if="withAlt" class="text-white text-xl font-semibold ml-3">Shekelcount</h3>
    </div>
</template>

<script>
export default {
    props: {
        size: Number,
        withAlt: {
            required: false,
            default: false,
            type: Boolean
        }
    }
}
</script>

