import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs'; //para recoger los datos que devuelve la API
import {Category} from '../models/category';
import {global} from './global';

@Injectable()

export class CategoryService{
	public url: string;
	

	constructor(
		private _http: HttpClient
	){
		this.url = global.url; //de esta manera tengo la url de la API y puedo reutilizarla en todos los métodos.
	}

	create(token:any, category:any):Observable<any>{
		let json= JSON.stringify(category);
		let params = "json="+json;

		let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
									   .set('Authorization',token);

	    return this._http.post(this.url + 'category', params, {headers: headers});
	}

	getCategories():Observable<any>{
		let headers = new HttpHeaders().set('Content-Type','application/x-www-form-urlencoded');
		return this._http.get(this.url + 'category', {headers:headers});
	}
}