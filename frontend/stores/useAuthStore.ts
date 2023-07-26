import {defineStore} from "pinia";

type User = {
    id: number;
    name: string;
    email: string;
}

type Credentials = {
    email: string;
    password: string;
}

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null);
    const isLoggedIn = computed(() => !!user.value);

    async function fetchUser() {
        const {data} = await useApiFetch('/api/user');
        user.value = data._rawValue as User;
    }


    async function login(credentials: Credentials) {
        await useApiFetch('/sanctum/csrf-cookie');
    
        const res = await useApiFetch('/api/login', {
          method: 'POST',
          body: credentials,
        });
    
        const userToken = res.data._rawValue.data.token;
    
        const {data} = await useApiFetch('/api/user', {
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${userToken}`,
          }
        });

        user.value = data._rawValue as User;

        return res;
      }
    
    // login with google from backend
    async function loginWithGoogle() {
        // open backend google login page
        window.open('http://localhost:8000/api/oauth/google/login', '_self');
        
    }

    return {user, login, isLoggedIn, fetchUser, loginWithGoogle};
});