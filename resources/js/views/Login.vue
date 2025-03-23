<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4 text-center">Login with Google</h2>
      <GoogleLogin clientId="8689967453-4lgr5075nku2s0uud98gchg8mb0kafse.apps.googleusercontent.com"
        :callback="handleGoogleLogin" class="w-full" />
    </div>
  </div>
</template>

<script>
import { GoogleLogin } from "vue3-google-login";
import axios from "axios";

export default {
  components: {
    GoogleLogin,
  },
 methods: {
  async handleGoogleLogin(response) {
    try {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post("/api/auth/google/callback", {
            credential: response.credential,
          }, {
            withCredentials: true,
          })
          .then(res => {
            if (res.data.google_id) {
              const nickname = prompt("Enter your nickname:");
              if (!nickname) return alert("Nickname is required!");

              axios.post("/api/auth/register", {
                google_id: res.data.google_id,
                email: res.data.email,
                name: res.data.name,
                nickname: nickname,
              })
              .then(registerRes => {
                console.log("Response dari register:", registerRes.data);
                if (registerRes.data.access_token) {
                    localStorage.setItem("token", registerRes.data.access_token);
                    alert("Login successful!");
                    window.location.href = "/";
                } else {
                    console.error("Token tidak ditemukan dalam response:", registerRes.data);
                    alert("Gagal mendapatkan token, silakan coba lagi.");
                }
              });
            } else {
              localStorage.setItem("token", res.data.access_token);
            }
            alert("Login successful!");
            setTimeout(() => {
                window.location.href = "/";
            }, 500);
          });
        });
    } catch (error) {
      console.error("Login failed:", error.message);
      alert(`Login failed: ${error.message}`);
    }
  },
}
};
</script>
