<?php

namespace TCG\Voyager\Tests;

use Illuminate\Support\Facades\Auth;

class AssetsTest extends TestCase
{
    protected $prefix = '/voyager-assets?path=';

    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    public function testCanOpenFileInAssets()
    {
        $url = route('voyager.dashboard').$this->prefix.'css/app.css';

        $response = $this->call('GET', $url);
        $this->assertEquals(200, $response->status(), $url.' did not return a 200');
    }

    public function testCannotOpenFileOutsideAssets()
    {
        $urls = [
            '../assets/css/app.css',
            '..../assets/css/app.css',
            'images/../css/app.css',
            'images/....//css/app.css',
            '..\assets/css/app.css',
            '....\assets/css/app.css',
            'images/..\css/app.css',
            'images/....\\css/app.css',
        ];

        foreach ($urls as $url) {
            $response = $this->call('GET', route('voyager.dashboard').$this->prefix.$url);
            $this->assertEquals(404, $response->status(), $url.' did not return a 404');
        }
    }
}
