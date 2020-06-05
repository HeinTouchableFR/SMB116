import {Component, OnInit} from '@angular/core';
import {AlertController, NavController} from '@ionic/angular';


import { Service } from '../Service/storage.service';

@Component({
    selector: 'app-login',
    templateUrl: './login.page.html',
    styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

    public data;
    token: string;
    refreshToken: string;
    username = ''
    password = ''

    // tslint:disable-next-line:max-line-length
    constructor(public navCtrl: NavController, private alertCtrl: AlertController, private service: Service) {
        service.checkIfLogged();
    }

    ngOnInit() {
    }
    login() {
        this.service.login(this.username, this.password);
    }

}
