import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs'; //para recoger los datos que devuelve la API
import {User} from '../models/user';
import {global} from './global';

@Injectable()

export class UserService{
	public url: string;
	public identity: any;
	public token: any;

	constructor(
		public _http: HttpClient
	){
		this.url = global.url; //deesta manera tengo la url de la API y puedo reutilizarla en todos los métodos.
	}

	test(){
		return "Hola mundo desde un servicio";
	}

	// método que devuelve un observable con la respuesta de la API

	register(user:any):Observable<any>{
		//convertir el objeto user (JS)  en un string
		let json = JSON.stringify(user); // para que estos datos puedan viajar al backend , es necesario convertirlo así
		let params = 'json='+json;   //el parámetro que le paso a la API.
		let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');  //es como configurar la API desde aqui

		return this._http.post(this.url+'register',params, {headers:headers});
		//especificamos método y parte de la ruta (o método del back). En este caso api/register. Petición Ajax
		//le paso los parámetros y los headers en formato json.
	}

	signup(user:any,gettoken:any=null): Observable<any>{
		if(gettoken!=null){
			user.gettoken = 'true';
		}

		let json = JSON.stringify(user);
		let params = 'json='+json;
		let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');  //es como configurar la API desde aqui

		return this._http.post(this.url+'login',params, {headers:headers}); //peticion ajax
	}

	update(token:any, user:any): Observable<any>{
		let json = JSON.stringify(user); //Convierto objeto JS a json para poder mandárselo a la API
		let params = 'json='+json;

		let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
									   .set('Authorization', token);

		return this._http.put(this.url+ 'user/update', params, {headers: headers});
	}

	getIdentity(){
		let identity = JSON.parse(localStorage.getItem('identity')!); //si no pongo la exclamacion peta y no sé por qué

		if(identity && identity != "undefined"){
			this.identity = identity;
		}else{
			this.identity = null;
		}

		return this.identity;

	}

	getToken(){
		let token = localStorage.getItem('token');
		if(token && token != "undefined"){
				this.token = token;
		}else{
			this.token = null;
		}

		return this.token;

	}



}