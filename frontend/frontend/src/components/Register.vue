<template>
    <div class="container">
        <div class="login-box">
            <div class="left-box">
                <div class="emoji">🤘</div>
                <h1 class="title">A fortuna fica ao lado daquele que ousa.</h1>
                <p class="subtitle">Todo progresso ocorre fora da zona de conforto.</p>
                <div class="image-box">
                    <div class="gradient"></div>
                    <div class="logo-box"></div>
                </div>
            </div>

            <div class="right-box">
                <h2 class="login-title">Inscreva-se</h2>
                <div v-if="errors.length" class="error-messages">
                    <p v-for="(error, index) in errors" :key="index">{{ error }}</p>
                </div>

                <form @submit.prevent="register">
                    <label for="name">Nome completo</label>
                    <input type="text" id="name" placeholder="Nome completo" v-model="name" required />

                    <label for="email">Endereço de e-mail</label>
                    <input type="email" id="email" placeholder="email@mail.com.br" v-model="email" required />

                    <label for="password">Senha de acesso</label>
                    <input type="password" id="password" placeholder="********" v-model="password" required />

                    <p class="register-link" @click="switchAuthView">Já tenho conta</p>
                    <button type="submit" @click="switchAuthView">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
    background-color: black;
}

.login-box {
    display: flex;
    flex-direction: row-reverse;
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

.emoji {
    font-size: 3rem;
    position: absolute;
    top: 20px;
    left: 20px;
}

.title {
    font-family: Montserrat;
    font-weight: 700;
    font-size: 1.5rem;
    color: #000;
    margin-bottom: 10px;
}

.subtitle {
    font-family: Montserrat;
    font-weight: 400;
    font-size: 0.9rem;
    color: #000;
    max-width: 90%;
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
}

.logo-box {
    width: 70px;
    height: 70px;
    background: black;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-box::after {
    content: "";
    width: 30px;
    height: 30px;
    background: white;
    display: block;
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

.error-messages {
    background: #ffdddd;
    color: #d8000c;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

</style>

<script setup>
import { defineEmits, ref } from "vue";

const name = ref("");
const email = ref("");
const password = ref("");
const errors = ref([]);

const emit = defineEmits(["switchAuthView"]);

const register = () => {
    errors.value = [];

    if (!name.value.trim()) errors.value.push("O nome é obrigatório.");
    if (!email.value.trim() || !/\S+@\S+\.\S+/.test(email.value)) errors.value.push("E-mail inválido.");
    if (!password.value.trim() || password.value.length < 8) errors.value.push("A senha deve ter pelo menos 8 caracteres.");

    if (errors.value.length === 0) {
        console.log("Usuário cadastrado:", name.value, email.value);
        emit("switchAuthView"); // Voltar para a tela de login
    } else {
        console.log("Erros no cadastro:", errors.value);
    }
};

</script>
