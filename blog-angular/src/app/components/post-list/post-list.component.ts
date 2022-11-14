import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
  selector: 'post-list',
  templateUrl: './post-list.component.html',
  styleUrls: ['./post-list.component.css']
})
export class PostListComponent implements OnInit {
  @Input() posts:any;
  @Input() identity:any;
  @Input() url:any;

  @Output()
  eliminar = new EventEmitter<number>();

  constructor() { }

  ngOnInit(): void {
  }

  deletePost(postId:any){
    this.eliminar.emit(postId);
  }

}
