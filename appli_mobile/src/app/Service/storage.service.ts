import { Injectable } from '@angular/core';
import {from, Observable} from 'rxjs';
import {HTTP} from '@ionic-native/http/ngx';
import { Plugins } from '@capacitor/core';
import {Router} from '@angular/router';
const { Storage } = Plugins;

@Injectable({
    providedIn: 'root'
})
export class Service {
    public data;
    token: string;
    refreshToken: string;
    constructor(private http: HTTP, private route: Router) { }

    login(username: string, password: string): void {
        console.log(username)
        const urlLogin = 'http://192.168.1.66/api/login_check';
        this.http.setDataSerializer('json');
        const call = this.http.post(urlLogin, {
            username,
            password
        }, {
            'Content-Type': 'application/json'
        });
        from(call).subscribe(data => {
                if (data.status === 200) {
                    this.data = JSON.parse(data.data);
                    this.setItem('token', this.data.token);
                    this.setItem('refreshToken', this.data.refreshToken);
                    this.route.navigate(['/home']);
                }
            }
            , error => {
                console.log(error);
            });
    }

    async checkIfLogged(refreshToken1 = this.refreshToken): Promise<void> {
        this.token = await this.getItem('token')
        this.refreshToken = await this.getItem('refreshToken')
        if (this.token != null) {
            this.refresh();
        }
    }
    refresh() {
        const url = 'http://192.168.1.66/api/token/refresh';
        this.http.setDataSerializer('json');
        const call = this.http.post(url, {"refreshToken" : this.refreshToken}, {
            'Content-Type': 'application/json'
        });
        from(call).subscribe(data => {
                console.log('data ' + data);
                if (data.status === 200) {
                    this.data = JSON.parse(data.data);
                    this.setItem('token', this.data.token);
                    this.setItem('refreshToken', this.data.refreshToken);
                    this.route.navigate(['/home']);
                }
            }
            , error => {
                console.log(error);
            });
    }
    async setItem(key, value) {
        await Storage.set({
            key,
            value
        });
    }
    async getItem(key) {
        const { value } = await Storage.get({ key });
        return value.toString();
    }
}
