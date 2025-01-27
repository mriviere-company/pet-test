<template>
  <div>
    <h1 class="text-xl font-semibold mb-2">Tell us about your dog</h1>

    <div v-if="step === 1">
      <h2 class="text-xl font-semibold mb-2">Step 1: Pet Details</h2>
      <label for="name" class="block">Pet Name</label>
      <input v-model="name" type="text" id="name" class="border p-2 w-full mb-4" />

      <label for="type" class="block">Pet Type</label>
      <select v-model="type" id="type" @change="loadBreeds" class="border p-2 w-full mb-4">
        <option value="" disabled>Select a Pet Type</option>
        <option v-for="typeOption in types" :key="typeOption.name" :value="typeOption.name">
          {{ typeOption.name }}
        </option>
      </select>

      <div>
        <label for="breed" class="block">What breed is it?</label>
        <input
            v-model="breedSearch"
            @input="filterBreeds"
            list="breeds-list"
            id="breed"
            class="border p-2 w-full mb-4"
            placeholder="Start typing to search breeds..."
        />
        <datalist id="breeds-list">
          <option
              v-for="breedOption in filteredBreeds"
              :key="breedOption.name"
              :value="breedOption.name"
          >
          </option>
        </datalist>
      </div>
    </div>

    <div v-if="step === 2">
      <h2 class="text-xl font-semibold mb-2">Step 2: Pet Age</h2>
      <p class="mb-2">Do you know the date of birth?</p>
      <div>
        <label>
          <input type="radio" v-model="ageChoice" value="date" /> Yes
        </label>
        <label class="ml-4">
          <input type="radio" v-model="ageChoice" value="approximate" /> No
        </label>
      </div>

      <div v-if="ageChoice === 'date'" class="mt-4">
        <label for="birthDate" class="block">Date of Birth</label>
        <input v-model="birthDate" type="date" id="birthDate" class="border p-2 w-full" />
      </div>

      <div v-if="ageChoice === 'approximate'" class="mt-4">
        <label for="approxAge" class="block">Approximate Age</label>
        <select v-model="approxAge" id="approxAge" class="border p-2 w-full">
          <option v-for="n in 20" :key="n" :value="n">{{ n }}</option>
        </select>
      </div>
    </div>

    <div v-if="step === 3">
      <h2 class="text-xl font-semibold mb-2">Step 3: Additional Details</h2>
      <label for="gender" class="block">Gender</label>
      <select v-model="gender" id="gender" class="border p-2 w-full mb-4">
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>

      <button @click="submitForm" class="bg-green-500 text-white px-4 py-2 rounded">
        Save Pet
      </button>
    </div>
    <div class="flex justify-between mb-4">
      <button
          v-if="step > 1"
          @click="prevStep"
          class="bg-gray-500 text-white px-4 py-2 rounded"
      >
        Back
      </button>
      <button
          v-if="step < 3"
          @click="nextStep"
          :disabled="!isStepValid"
          class="bg-blue-500 text-white px-4 py-2 rounded"
      >
        Continue
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      step: 1,
      name: "",
      type: null,
      breed: null,
      breedSearch: "",
      filteredBreeds: [],
      birthDate: "",
      approxAge: null,
      ageChoice: null,
      gender: "",
      isDangerous: false,
      types: [],
      breeds: [],
    };
  },
  computed: {
    isStepValid() {
      if (this.step === 1) return this.name && this.type && this.breedSearch;
      if (this.step === 2) return this.ageChoice && (this.ageChoice === "date" ? this.birthDate : this.approxAge);
      return true;
    },
  },
  created() {
    this.loadTypes();
  },
  methods: {
    nextStep() {
      if (this.step < 3) this.step++;
    },
    prevStep() {
      if (this.step > 1) this.step--;
    },
    loadTypes() {
      axios.get('/api/options/types')
          .then(response => {
            this.types = response.data;
          })
          .catch(error => {
            console.error("Error loading pet types:", error);
          });
    },
    loadBreeds() {
      this.breed = null;
      this.breedSearch = "";
      this.filteredBreeds = [];
      if (!this.type) return;
      axios
          .get(`/api/options/breeds?type=${this.type}`)
          .then((response) => {
            this.breeds = response.data;
            this.filteredBreeds = this.breeds;
          })
          .catch((error) => {
            console.error("Error loading breeds:", error);
          });
    },
    filterBreeds() {
      const search = this.breedSearch.toLowerCase();
      this.filteredBreeds = this.breeds.filter((breed) =>
          breed.name.toLowerCase().includes(search)
      );
    },
    submitForm() {
      const data = {
        name: this.name,
        type: this.type,
        breed: this.breedSearch,
        birthDate: this.ageChoice === "date" ? this.birthDate : null,
        approxAge: this.ageChoice === "approximate" ? this.approxAge : null,
        gender: this.gender,
      };
      axios.post("/api/pet/creation", data).then(() => {
        alert("Pet saved successfully!");
      });
    },
  },
};
</script>
