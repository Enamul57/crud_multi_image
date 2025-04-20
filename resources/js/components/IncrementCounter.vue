
 
<template>
  <div>
    <button
    type="button"
    @click="counter++"
    class="p-2 text-white bg-gray-500 rounded"
  >
    Counter is: {{ counter }}
  </button>

  <div>
    <button type='button' ref="myButton" @click="handleClick">Click Me</button>
  </div>
  <div class="mt-5 bg-slate-600 text-white">
    <h2>Name: {{obj.firstName}}</h2>
    <h2>Last Name: {{obj.lastName}}</h2>
    <h2>Age: {{obj.age}}</h2>
    <h3>Country:{{obj.country}}</h3>
  </div>
  <decrement-counter :age="obj.age" :person="obj" @send-person="receiveData"/>
  <div>
    <h2>Store Counter:{{number}}</h2>
    <button class="bg-red-500 text-white px-3 py-1 rounded-md" @click="increment">Increment</button>
  </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import {reactive} from 'vue';
import { computed } from 'vue';
import { useStore } from 'vuex';

const counter = ref(0);
const myButton = ref(null);

const obj = reactive({firstName:'Enamul',lastName:'Haque',age:29,country:''});
function handleClick(){
    myButton.value.textContent = "Clicked";
    obj.firstName  = "Ruqaiya";
}
function receiveData(data){
    obj.firstName = data.firstName;
    obj.lastName = data.lastName;
    obj.age = data.age;
    obj.country = data.country;
 }

 const store = useStore();

// Get the counter value from the Vuex store
const number = computed(() => store.state.Counter);
const increment=()=>{
    store.dispatch('actionIncrement');
}
</script>