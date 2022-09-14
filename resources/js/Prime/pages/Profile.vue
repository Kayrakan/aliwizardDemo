<template>
    <div class="grid p-fluid">
        <div class="col-12 md:col-12">
            <div class="card p-fluid">
                <h5>Profilinizi duzenleyin</h5>
                <div class="field">
                    <label for="name1">Adiniz</label>
                    <InputText id="name1" type="text" v-model="profile.name"/>
                </div>
                <div class="field">
                    <label for="email1">Email</label>
                    <InputText disabled id="email1" type="text" v-model="profile.email"/>
                </div>
                <div class="field">
                    <label for="old_password">Eski şifre(eğer değiştirecekseniz)</label>
                    <Password :feedback="false" id="old_password" v-model="profile.old_password"/>
                </div>
                <div class="field" >
                    <label for="password">Yeni şifre</label>
                    <Password :toggleMask="true" :feedback="true" id="password"
                              :disabled="!profile.old_password"
                              :class="!passwordsMatch?'p-invalid':''"
                              v-model="profile.password"/>
                </div>
                <div class="field">
                    <label for="password_confirmation">Yeni şifre(tekrar)</label>
                    <Password :toggleMask="true" :feedback="true" id="password_confirmation"
                              :disabled="!profile.old_password"
                              :class="!passwordsMatch?'p-invalid':''"
                              v-model="profile.password_confirmation"/>
                </div>

                <Button :disabled="!passwordsMatch" label="Kaydet" @click="updateProfile()"
                        style="width: 10em;"></Button>
            </div>
        </div>


    </div>
</template>
<script>

import Password from 'primevue/password';

class ProfileService {
    getProfile() {
        return axios.get(route('profile')).then(res => res.data)
    }

    updateProfile(profile) {
        return axios.post(route('profile.update'), profile).then(res => res.data)
    }
}

export default {
    computed: {
        passwordsMatch() {
            return this.profile.password === this.profile.password_confirmation
        }
    },
    data() {
        return {
            profile: {}
        }
    },
    profileService: null,
    created() {
        this.profileService = new ProfileService();

    },
    mounted() {
        this.profileService.getProfile().then(data => this.profile = data);
    },
    methods: {
        updateProfile() {
            this.profileService.updateProfile(this.profile).then(data => {
                if (data.result === 'success') {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'Bilgileriniz kaydedildi',
                        detail: event.data.name,
                        life: 3000
                    });
                } else {
                    this.$toast.add({
                        severity: 'danger',
                        summary: 'Bir hata olustu',
                        detail: event.data.name,
                        life: 3000
                    });
                }

            })
        }
    }
}
</script>
