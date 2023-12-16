<?php

namespace App\Http\Controllers;

use Crm\Models\MemberPage;
use Gumamax\Faq\FaqGroup;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index($subdomain=null) {

        session()->forget('subdomain');

        if ($subdomain){

            return $this->subdomainIndex($subdomain);

        }

        return view('home.index');
    }

    public function subdomainIndex($subdomain){

        $page = MemberPage::where('subdomain',$subdomain)->first();

        if ($page){

            subdomain([
                'member_id'	=>$page->member_id,
                'title'		=>$page->headline,
                'name'		=>$page->subdomain,
                'erp_company_id'		=>$page->member->erp_comapny_id,
                'erp_partner_id'		=>$page->member->erp_partner_id,
            ]);

            $member = $page->member;

            return view('member.show', compact('member'));

        }

        return redirect('/');
    }

    public function staticPage($view='') {
        if ($view=='') $view='errors.404';
        if ( view()->exists('static.'.$view) ) {
            return view('static.'.$view);
        } else {
            return view('errors.404');
        }

    }

    public function popup(Request $request, $slug) {
        if($request->ajax()) {
            return view('special_info.'.$slug.'-modal');
        } else {
            return view('special_info.'.$slug);
        }
    }


    public function faq() {

        $faq_groups = FaqGroup::where('active',1)->orderBy('order_index')->get();

        return view('home.faq', compact('faq_groups'));

    }

    public function login(Request $request) {

        $intended = $request->get('intended','');

        if ($intended!==''){
            session(['url.intended'=>$intended]);
        }

        return view('auth.login');
    }

    public function requestReset() {
        $status = session('status');
        if (empty($status))
            return view('auth.request-reset');
        else
            return view('auth.request-reset', compact('status'));
    }

    public function register(Request $request) {

        $intended = $request->get('intended','');

        if ($intended!==''){
            session(['url.intended'=>$intended]);
        }

        return view('auth.register');
    }

    public function reset($token='') {
        if ($token=='')
            return view('errors.404');
        else
            return view('auth.reset', compact('token'));
    }

    public function howToReadTyre() {
        return view ('static.michelin-marketing.how-to-read-tyre');
    }
}
