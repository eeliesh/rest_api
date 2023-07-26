<script lang="ts" setup>
  import {ref} from 'vue';
  import {useAuthStore} from '../stores/useAuthStore';

  const form = ref({
    email: 'valentintean@gmail.com',
    password: '123456789',
  });

  const auth = useAuthStore();

  async function handleLogin() {
    const {error} = await auth.login(form.value);
  }
</script>

<template>
  <div>
    <form @submit.prevent="handleLogin">
      <label for="email">Email</label>
      <input v-model="form.email" type="email" id="email" name="email" />

      <label for="password">Password</label>
      <input v-model="form.password" type="password" id="password" name="password" />

      <button>Login</button>
    </form>
    <div>
      <button @click="auth.loginWithGoogle()">Login with Google</button>
    </div>
  </div>
</template>

<style scoped></style>
