import { Injectable } from '@angular/core';
import { Response } from '@angular/http';
import {Http}           from '@angular/http';
import {Observable}     from 'rxjs/Observable';
import 'rxjs/Rx';

@Injectable()
export class ReportsService {

    constructor (private http: Http) {

    }

    private url = "https://tools.lisd.net/comsrestfulservices/getReportNamesjson.php";

    getReports()
    {
        //let url = "./assets/php/getreportnamesjson.php";
        return this.http.get(this.url)
            .map(res => res.json().reports);
    }

    private handleError (error: Response) {
        // in a real world app, we may send the error to some remote logging infrastructure
        // instead of just logging it to the console
        console.error(error);
        return Observable.throw(error.json().error || 'Server error');
    }
}
