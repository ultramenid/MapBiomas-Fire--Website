<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CmsUiRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_cms_pages_render(): void
    {
        $this->seed();

        // Guest: the login page renders with the new auth layout.
        $this->get('/cms/login')->assertOk();

        // Session-based CMS auth (mirrors LoginComponent's session keys).
        session([
            'id' => DB::table('users')->where('role_id', 1)->value('id'),
            'role_id' => 1,
        ]);

        $newsId = DB::table('news')->value('id');
        $infographicId = DB::table('infographic')->value('id');
        $faqId = DB::table('faq')->value('id');
        $factsheetId = DB::table('factsheet')->value('id');

        $urls = [
            '/cms/dashboard',
            '/cms/listnews',
            '/cms/addnews',
            "/cms/editnews/$newsId",
            "/cms/previewnews/$newsId?lang=id",
            "/cms/previewnews/$newsId?lang=en",
            "/cms/previewcardnews/$newsId",
            '/cms/listinfographic',
            '/cms/addinfographic',
            "/cms/editinfographic/$infographicId",
            '/cms/listfactsheet',
            '/cms/addfactsheet',
            "/cms/editfactsheet/$factsheetId",
            '/cms/listfaq',
            '/cms/addfaq',
            "/cms/editfaq/$faqId",
            '/cms/pageabout',
            '/cms/termofuse',
            '/cms/cmsrefrencemap',
            '/cms/cmsatbd',
            '/cms/cmsdownload',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }
}
