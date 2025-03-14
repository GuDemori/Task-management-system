<template>
    <div class="container">
        <div class="login-box">
            <div class="left-box" @mouseover="hover = true" @mouseleave="hover = false" :class="{ darkMode: hover }">
                <div class="emoji" :class="{ emojiHover: hover }">🤘</div>
                <h1 class="title">A fortuna fica ao lado daquele que ousa.</h1>
                <p class="subtitle">Todo progresso ocorre fora da zona de conforto.</p>
                <div class="image-box">
                    <div class="gradient"></div>
                    <div class="logo-box"></div>
                </div>
            </div>

            <div class="right-box">
                <h2 class="login-title">Login</h2>
                <form @submit.prevent="login">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="email@mail.com.br" v-model="email" required />

                    <label for="password">Senha</label>
                    <input type="password" id="password" placeholder="********" v-model="password" required />

                    <p class="register-link" @click="switchAuthView">Não tenho conta</p>

                    <button type="submit">Acessar</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const email = ref("");
const password = ref("");
const hover = ref(false);

const router = useRouter();

const login = () => {
    if (email.value && password.value) {
        router.push({ name: "taskList" });
    } else {
        alert("Preencha os campos para continuar.");
    }
};

const switchAuthView = () => {
    console.log("Clique detectado em Login.vue - Mudando para Register");
    router.push({ name: "register" });
};
</script>

<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
}

.login-box {
    display: flex;
    flex-direction: row;
    height: 100%;
    width: 100%;
    background: white;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.5s ease-in-out;
}

.left-box {
    width: 50%;
    background: #f5f5f5;
    padding: 50px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
    justify-content: center;
    position: relative;
    transition: all 0.5s ease-in-out;
}

.left-box.darkMode {
    background: black;
}

.left-box.darkMode .title,
.left-box.darkMode .subtitle {
    color: white;
}

.emoji {
    font-size: 3rem;
    position: absolute;
    top: 20px;
    left: 20px;
    transition: transform 0.5s ease-in-out, top 0.5s ease-in-out;
}

.emojiHover {
    transform: translateY(10px);
    top: 30px;
}

.title {
    font-family: Montserrat;
    font-weight: 700;
    font-size: 1.5rem;
    color: #000;
    margin-bottom: 10px;
    transition: color 0.5s ease-in-out;
}

.subtitle {
    font-family: Montserrat;
    font-weight: 400;
    font-size: 0.9rem;
    color: #000;
    max-width: 90%;
    transition: color 0.5s ease-in-out;
}

.image-box {
    display: flex;
    position: absolute;
    bottom: 20px;
    left: 20px;
    align-items: center;
    justify-content: flex-start;
    width: auto;
    margin-top: 20px;
}

.gradient {
    width: 70px;
    height: 70px;
    background: linear-gradient(to right, black, white);
    margin-right: 10px;
    transition: background 0.5s ease-in-out;
}

.logo-box {
    width: 70px;
    height: 70px;
    background: black;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.5s ease-in-out;
}

.logo-box::after {
    content: "";
    width: 30px;
    height: 30px;
    background: white;
    display: block;
}

.left-box.darkMode .gradient {
    background: linear-gradient(to right, white, black);
}

.left-box.darkMode .logo-box {
    background: white;
}

.left-box.darkMode .logo-box::after {
    background: black;
}

.right-box {
    width: 50%;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 0.9rem;
    margin-bottom: 5px;
}

input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 15px;
}

.register-link {
    font-size: 0.8rem;
    color: #777;
    cursor: pointer;
    margin-bottom: 20px;
    text-decoration: underline;
}

button {
    background: black;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #333;
}
</style>