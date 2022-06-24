<template>
    <div>
        <v-select
            ref="self"
            :get-option-label="(mutator) => mutator.name"
            :options="mutators"
            multiple
            v-model="modelValue">
            <template v-slot:option="mutator">
                <div class="flex items-center">
                    <span class="ml-2">{{ mutator.name }}</span>
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
    },
    props: {
        mutators: Array,
        modelValue: {
            data() {
                return false
            }
        }
    },
    methods: {
        focus() {
            this.$refs['self'].$el.querySelector('.vs__search').focus()
        },
    },
    watch: {
        modelValue: {
            handler(val) {
                this.$emit('update:modelValue', val)
            }
        }
    }
}
</script>
