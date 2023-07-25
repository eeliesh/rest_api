<script lang="ts" setup>
  const form = ref({
    email: 'valentintean@gmail.com',
    password: '123456789',
  });

  async function handleLogin() {
    await useFetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include',
    });

    const token = useCookie('XSRF-TOKEN');

    const {data} = await useFetch('http://localhost:8000/api/login', {
      method: 'POST',
      credentials: 'include',
      body: form.value,
      watch: false,
      headers: {
        'X-XSRF-TOKEN': token.value as string,
      }
    });

    console.log(data._rawValue.data);
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
  </div>
</template>

<style scoped></style>
