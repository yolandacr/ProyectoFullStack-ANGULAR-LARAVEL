import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs'; //para recoger los datos que devuelve la API
import {Post} from '../models/post';
import {global} from './global';

@Injectable()

export class PostService{
	public url: string;
	

	constructor(
		private _http: HttpClient
	){
		this.url = global.url; //de esta manera tengo la url de la API y puedo reutilizarla en todos los métodos.
	}


	pruebas(){
		return "Hola desde el servicio de POST";
	}

	create(token:any, post:any):Observable<any>{
		let json = JSON.stringify(post);
		let params = "json="+json;
		let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded')
									   .set('Authorization',token);


		return this._http.post(this.url + 'posts', params, {headers: headers});
	}

	getPosts():Observable <any>{
		let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');
		return this._http.get(this.url+'posts', {headers: headers});
	}

	getPost(id:any):Observable <any>{
		let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');
		return this._http.get(this.url+'posts/'+ id, {headers: headers});
	}

}