<template>
    <v-container>
        <v-row>
            <v-col cols="4" offset-md="4">
                <v-card>
                    <v-card-title class="justify-center">Inicio de sesión</v-card-title>
                    <v-card-text>
                        <v-form v-model="loginForm">
                            <v-text-field
                                v-model="email"
                                :rules="required"
                                filled
                                rounded
                                label="Usuario"
                            ></v-text-field>
                            <v-text-field
                                v-model="password"
                                :rules="required"
                                filled
                                rounded
                                label="Contraseña"
                                type="password"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn :disabled="!loginForm" v-on:click="login()" color="accent">Iniciar sesión</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    export default {
        data() {
            return {
                loginForm: false,

                email: '',
                password: '',

                required: [v => !!v || 'Este campo es requerido.'],
            }
        },

        methods: {
            login: function() {
                axios
                    .post('vuelogin', { email: this.email, password: this.password })
                    .then(res => window.location.replace('/home'))
                    // .then(res => console.log(res))
                    .catch(error => console.log(error))
            },
        },
    }
</script>
