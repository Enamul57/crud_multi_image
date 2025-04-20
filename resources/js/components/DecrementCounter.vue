<template>
    <div>
        <h3>Name: {{person.firstName}}</h3>
        <h3>Age: {{person.Age}}</h3>
        <button class="bg-green-500 text-white px-3 py-1 rounded-md" @click="passPerson">Change</button>
    </div>
</template>
<script setup>
import {ref} from 'vue';
import {reactive} from 'vue';
const emit =  defineEmits();
const country = ref('Bangladesh');
const sendPerson = reactive({
    firstName: 'Ruqaiya',
    lastName:'Sayma',
    age:21,
    country:'Bangladesh',
});
defineProps({
    age:{ type: [Number],required:true},
    person: Object,
});
function passPerson(){
   emit('send-person',sendPerson);
}
</script>