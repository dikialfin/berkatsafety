<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SeoPage, Contact};
use Carbon\Carbon;

class ContactUsController extends Controller
{
    public function index(Request $request){
        $page = SeoPage::where('page', 'contact-us')->first();
        if (isset($page->seo_setting)) {
            $page->seo_setting = json_decode($page->seo_setting, false);
            $page->seo_settings = (object)$page->seo_setting;
        }
        $lang = app()->getLocale();
        $data = [
            'meta_title' => $page->seo_settings->{'meta_title_'.$lang} ?? '',
            'meta_keyword' => $page->seo_settings->{'keyword_'.$lang} ?? '',
            'meta_description' => $page->seo_settings->{'meta_description_'.$lang} ?? '',
            'meta_image' => asset('images/logo-home.png'),
            'lang' => $lang
        ];
        return view('page.contact', $data);
    } 

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname'  => 'required|string|max:255',
            'company'   => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email'     => 'required|email|unique:contacts,email',
            'message'   => 'required|string|min:10'
        ], [
            'fullname.required'  => 'Full Name is required.',
            'company.required'   => 'Company is required.',
            'job_title.required' => 'Job Title is required.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Email must be a valid email address.',
            'email.unique'       => 'This email is already used.',
            'message.required'   => 'Message is required.',
            'message.min'        => 'Message must be at least 10 characters.'
        ]);

        Contact::create($validatedData);

        return back()->with('success', 'Contact saved successfully!');
    }

}
