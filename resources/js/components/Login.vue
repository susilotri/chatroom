<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4 text-center">Login with Google</h2>
      <GoogleLogin  clientId="8689967453-4lgr5075nku2s0uud98gchg8mb0kafse.apps.googleusercontent.com" :callback="handleGoogleLogin" class="w-full" />
    </div>
  </div>
</template>

<script>
import { GoogleLogin } from "vue3-google-login";
import axios from "axios";

console.log('ini masuk')

export default {
  components: {
    GoogleLogin,
  },
  methods: {
    async handleGoogleLogin(response) {
      
      console.log('ini response', response)
      try {
        const res = await axios.get("/api/auth/google/callback", {
          params: { token: response.credential },
        });
        

        if (res.data.google_id) {
          const nickname = prompt("Enter your nickname:");
          if (!nickname) return alert("Nickname is required!");

          const registerRes = await axios.post("/api/auth/register", {
            google_id: res.data.google_id,
            email: res.data.email,
            name: res.data.name,
            nickname: nickname,
          });

          localStorage.setItem("token", registerRes.data.access_token);
        } else {
          localStorage.setItem("token", res.data.access_token);
        }

        alert("Login successful!");
        window.location.href = "/dashboard";
      } catch (error) {
        console.error("Login failed:", error);
        alert("Login failed!");
      }
    },
  },
};
</script>
