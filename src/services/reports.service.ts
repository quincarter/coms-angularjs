import { Injectable } from '@angular/core';
import { Response } from '@angular/http';
import {Http}           from '@angular/http';
import {Observable}     from 'rxjs/Observable';
import 'rxjs/Rx';

@Injectable()
export class ReportsService {

    constructor (private http: Http) {}

    private reportURL = 'http://tools.lisd.net/dev/coms/reports.json';

  /*  getReportNames () : Observable<string>  {

        /!*const _paramUrl = this.reportURL;

        return this.http.get(_paramUrl)
            .map((res: Response) => {
                //console.log(res.json());
                return res.json();
            });*!/
    }*/

    /*getReportNames(): Observable<ReportsService[]> {
        return this.http.get('https://tools.lisd.net/dev/coms/reports.json')
            .map((res: Response) => {
                //console.log(res.json());
                return res.json();
            });
    }*/

    getReports()
    {
        let url = "http://tools.lisd.net/dev/coms/getreportnamesjson.php";
        return this.http.get(url)
            .map(res => res.json());
    }

    private handleError (error: Response) {
        // in a real world app, we may send the error to some remote logging infrastructure
        // instead of just logging it to the console
        console.error(error);
        return Observable.throw(error.json().error || 'Server error');
    }
}
