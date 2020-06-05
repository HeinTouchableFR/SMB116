import {Component} from '@angular/core';
import {CameraResultType, CameraSource, Plugins} from '@capacitor/core';
import {DomSanitizer, SafeResourceUrl} from '@angular/platform-browser';
import {NavController} from '@ionic/angular';
import {HttpClient} from '@angular/common/http';
import {from, Observable} from 'rxjs';
import { AlertController } from '@ionic/angular';
import {HTTP} from '@ionic-native/http/ngx';
import { Service } from '../Service/storage.service';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {

  photo: string;
  data;
  token: string;
  // tslint:disable-next-line:max-line-length
  private urlImage: string;
  constructor(public navCtrl: NavController, private sanitizer: DomSanitizer, public http: HTTP, private alertCtrl: AlertController, private service: Service) {
  }


  async takePicture() {
    const image = await Plugins.Camera.getPhoto({
      quality: 100,
      allowEditing: false,
      resultType: CameraResultType.Base64,
      source: CameraSource.Camera,
    });
    this.photo = 'data:image/jpeg;base64,' + image.base64String;
  }


  async gallery() {
    const image = await Plugins.Camera.getPhoto({
      quality: 100,
      allowEditing: false,
      resultType: CameraResultType.Base64,
      source: CameraSource.Photos
    });
    this.photo = 'data:image/jpeg;base64,' + image.base64String;
  }

  async upload() {
    this.token = await this.service.getItem('token')
    const url = 'http://192.168.1.66/api/picture';
    const postData = new FormData();
    this.urlImage = '';
    const image_file = this.photo;
    const filename = 'image.jpg';
    const call = this.http.post(url, {
      filename,
      image_file
    }, {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + this.token
    });
    from(call).subscribe(async data => {
          if (data.status >= 200 && data.status < 300) {
            this.data = JSON.parse(data.data);
            const alert = await this.alertCtrl.create ({
              header: 'Votre image a été importée avec succès',
              subHeader: 'Voici le lien de votre image: ',
              message: 'http://192.168.1.66/image/' + this.data.code,
              buttons: ['Valider'],
            });
            await alert.present();
          }
        }
        , error => {
          console.log(error);
        });
  }
}
