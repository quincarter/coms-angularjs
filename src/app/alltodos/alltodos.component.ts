import { Component, AfterViewInit } from '@angular/core';

import { Title }     from '@angular/platform-browser';

import { TdLoadingService } from '@covalent/core';

import { ItemsService, UsersService, ProductsService, AlertsService, ReportsService } from '../../services';

import { multi } from './data';
import { Router } from '@angular/router';
import { Http, Response } from '@angular/http';

import {Observable} from "rxjs";

@Component({
    selector: 'qs-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.scss'],
    viewProviders: [ ItemsService, UsersService, ProductsService, AlertsService ],
    providers: [ReportsService]
})
export class DashboardComponent implements AfterViewInit {

  items: Object[];
  users: Object[];
  products: Object[];
  alerts: Object[];
  reportsArray: Object[];

  //reportsJson: string;
  reportsJson: Array<any>;

  // Chart
  single: any[];
  multi: any[];

  view: any[] = [700, 400];

  // options
  showXAxis: boolean = true;
  showYAxis: boolean = true;
  gradient: boolean = false;
  showLegend: boolean = false;
  showXAxisLabel: boolean = true;
  xAxisLabel: string = '';
  showYAxisLabel: boolean = true;
  yAxisLabel: string = 'Sales';

  colorScheme: any = {
    domain: ['#1565C0', '#2196F3', '#81D4FA', '#FF9800', '#EF6C00'],
  };

  // line, area
  autoScale: boolean = true;

  constructor(private _titleService: Title,
              private _reportsService: ReportsService,
              private _itemsService: ItemsService,
              private _usersService: UsersService,
              private _alertsService: AlertsService,
              private _productsService: ProductsService,
              private _loadingService: TdLoadingService,
              private http: Http,
              private _router: Router) {
                // Chart
                this.multi = multi.map((group: any) => {
                  group.series = group.series.map((dataItem: any) => {
                    dataItem.name = new Date(dataItem.name);
                    return dataItem;
                  });
                  return group;
                });

                /*const reports = this._reportsService.getReports();
                console.log(reports);*/

                setInterval(() => {this._reportsService.getReports()
                                        .subscribe(
                                            data => this.reportsJson = data,
                                            error => alert(error)
                                        );}, 500000);

  }

  getReportNames()
  {
      this._reportsService.getReports()
          .subscribe(
              data => this.reportsJson = data,
              error => alert(error),
              () => console.log(this.reportsJson)
          );
  }
  ngAfterViewInit():
  void
  {
    this._reportsService.getReports()
        .subscribe(
            data => this.reportsJson = data,
            error => alert(error),
            () => console.log(this.reportsJson)
        );
    this._titleService.setTitle( 'COMS' );
    this._loadingService.register('items.load');
    this._itemsService.query().subscribe((items: Object[]) => {
      this.items = items;
      setTimeout(() => {
        this._loadingService.resolve('items.load');
      }, 750);
    }, (error: Error) => {
      this._itemsService.staticQuery().subscribe((items: Object[]) => {
        this.items = items;
        setTimeout(() => {
          this._loadingService.resolve('items.load');
        }, 750);
      });
    });
    this._loadingService.register('alerts.load');
    this._alertsService.query().subscribe((alerts: Object[]) => {
      this.alerts = alerts;
      setTimeout(() => {
        this._loadingService.resolve('alerts.load');
      }, 750);
    });
    this._loadingService.register('products.load');
    this._productsService.query().subscribe((products: Object[]) => {
      this.products = products;
      setTimeout(() => {
        this._loadingService.resolve('products.load');
      }, 750);
    });
    this._loadingService.register('favorites.load');
    this._productsService.query().subscribe((products: Object[]) => {
      this.products = products;
      setTimeout(() => {
        this._loadingService.resolve('favorites.load');
      }, 750);
    });
    this._loadingService.register('users.load');
    this._usersService.query().subscribe((users: Object[]) => {
      this.users = users;
      setTimeout(() => {
        this._loadingService.resolve('users.load');
      }, 750);
    }, (error: Error) => {
      this._usersService.staticQuery().subscribe((users: Object[]) => {
        this.users = users;
        setTimeout(() => {
          this._loadingService.resolve('users.load');
        }, 750);
      });
    });
  }

    logout(): void
    {
        this._router.navigate(['/']);
    }
}