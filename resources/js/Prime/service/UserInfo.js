import axios from 'axios'



export default class UserInfo {


    getUserInfo() {

        return axios.get(route('userinfo')).then(res => res.data)


    }

    saveUserInfo(newName, newEmail) {
        console.log('log new')
        console.log(newName + ' ' + newEmail)
        console.log('log new')


        return axios.post(route('changeuserinfo'), {
            name: newName,
            email: newEmail
        }).then(res => res.status).catch(err => err.response.status);


        // return axios.post(route('changeuserinfo')).then(res => console.log(res.data))
    }

    confirmUserPassword(oldPassword) {

        return axios.post(route('confirmUserPassword'), {
            passwordToConfirm:oldPassword,
        })
            .then(res => res.status).catch((err) => err.response.status)

    }

    resetUserPassword(oldPassword, password) {

        return axios.post(route('resetUserPassword', {
            oldPassword:oldPassword,
            password:password
        })).then( res => res.status).
        catch((err) => err.response.status);
    }

}
