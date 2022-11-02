//IMPORTS NECESARIOS
import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//IMPORTAR COMPONENTES
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';
import { UserEditComponent } from './components/user-edit/user-edit.component';
import { CategoryNewComponent } from './components/category-new/category-new.component';
import { PostNewComponent} from './components/post-new/post-new.component';
import { PostDetailComponent} from './components/post-detail/post-detail.component';

//DEFINIR LAS RUTAS
const appRoutes: Routes = [
{path:'', component: HomeComponent},
{path:'inicio', component: HomeComponent},
{path:'login', component: LoginComponent},
{path:'logout/:sure', component: LoginComponent},
{path:'registro', component: RegisterComponent},
{path:'ajustes', component: UserEditComponent},
{path:'crear-categoria', component: CategoryNewComponent},
{path:'crear-entrada', component: PostNewComponent},
{path:'entrada/:id', component: PostDetailComponent},
{path:'**', component: ErrorComponent}          
]; //Siempre al final de todas las rutas la de error

//EXPORTAR CONFIGURACIÓN
export const appRoutingProviders:any[] = [];
export const routing: ModuleWithProviders <any>  = RouterModule.forRoot(appRoutes);