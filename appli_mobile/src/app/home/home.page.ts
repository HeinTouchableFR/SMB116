import {Component} from '@angular/core';
import {CameraResultType, CameraSource, Plugins} from '@capacitor/core';
import {DomSanitizer, SafeResourceUrl} from '@angular/platform-browser';
import {NavController} from '@ionic/angular';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {

  photo: string;
  // tslint:disable-next-line:max-line-length
  private urlImage: string;
  constructor(public navCtrl: NavController, private sanitizer: DomSanitizer, public http: HttpClient, private alertCtrl: AlertController) {}


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
    const url = 'http://192.168.1.80/photo-app/json.php';
    const postData = new FormData();
    this.urlImage = '';
    postData.append('file', this.photo);
    const data: Observable<any> = this.http.post(url, postData);
    data.subscribe(async (result) => {
       const alert = await this.alertCtrl.create ({
        header: 'Votre image a été importée avec succès',
        subHeader: 'Voici le lien de votre image: ',
        message: result.image_url,
        buttons: ['Valider'],
      });
       await alert.present();
    });
  }
}
