import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.service';
import  { CategoryService } from './services/category.service';
import { global } from './services/global';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService, CategoryService]
})
export class AppComponent implements OnInit, DoCheck {
  public title = 'Blog de Angular';
  public identity:any;
  public token:any;
  public url:any;
  public categories:any;

  constructor(
    private _userService:UserService,
    private _categoryService:CategoryService
    ){
      this.loadUser();
      this.url = global.url;
  }

  ngOnInit(){
    console.log('Webapp cargada correctamente');
    this.getCategories();
  }


  // este metod se ejecutara cada vez que se realice un cambio

  ngDoCheck(){
      this.loadUser();
  }

  loadUser(){
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }

  getCategories(){
    this._categoryService.getCategories().subscribe(
        response => {
          if(response.status == 'success'){
            this.categories = response.categories;
          }
        },
        error => {
          console.log(<any>error);
        }
    );
  }
}