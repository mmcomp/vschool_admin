<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Agent;
use App\Company;


class CompanyController extends Controller
{
    public function create(Request $request) {
        $id = (int)$request->input('id',0);
        $company = Company::find($id);
        if(!$company) {
            $company = new Company;
        }
        $company->entity = $request->input('entity');
        $company->registration_number = $request->input('registration_number');
        $company->name = $request->input('name');
        $company->services_id = $request->input('services_id');
        $company->ownerships_id = $request->input('ownerships_id');
        $company->economic_code = $request->input('economic_code');
        $company->office_establishment_license = $request->input('office_establishment_license');
        $company->license_number = $request->input('license_number');
        $company->agency_name = $request->input('agency_name');
        $company->national_id = $request->input('national_id');
        $company->address = $request->input('address');
        $company->postal_code = $request->input('postal_code');
        $company->cities_id = $request->input('cities_id');
        $company->tells = $request->input('tells');
        $company->mobile = $request->input('mobile');
        $company->ceo_agents_id = $request->input('ceo_agents_id');
        $company->save();
        dump($company);
        $request->session()->flash('msg_success', 'پیمانکار مورد نظر با موفقیت ثبت شد');
        $request->session()->flash('company_add', true);
        return redirect('/protocols/create');
    }
}
