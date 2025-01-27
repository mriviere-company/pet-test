import './styles/app.css';

import { createApp } from 'vue';
import PetForm from "./components/PetForm.vue";

const app = createApp({});
app.component("pet-form", PetForm);
app.mount("#vue-app");