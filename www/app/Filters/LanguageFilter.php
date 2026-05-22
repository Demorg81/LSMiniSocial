<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LanguageFilter implements FilterInterface
{
    private array $supported = ['en', 'ca'];

    public function before(RequestInterface $request, $arguments = null)
    {
        $lang = $this->detectLanguage($request);
        app('config')->get('App')->defaultLocale = $lang;
        service('request')->setLocale($lang);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function detectLanguage(RequestInterface $request): string
    {
        $header = $request->getServer('HTTP_ACCEPT_LANGUAGE') ?? 'en';

        $parts = explode(',', $header);

        foreach ($parts as $part) {
            $tag = trim(explode(';', $part)[0]);
            $lang = strtolower(substr($tag, 0, 2));

            if (in_array($lang, $this->supported, true)) {
                return $lang;
            }
        }

        return 'en';
    }
}
