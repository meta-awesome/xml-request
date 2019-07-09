<?php

namespace Metawesome\RequestXml\Providers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Metawesome\RequestXml\Exceptions\CouldNotParseXml;
use Metawesome\XmlToArray\XmlToArray;

class RequestXmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerWantsXml();
        $this->registerIsXml();
        $this->registerXml();
    }

    /**
     * Determine if the request wants to receive an xml response.
     *
     * @return void
     */
    protected function registerWantsXml()
    {
        Request::macro('wantsXml', function () {
            return Str::contains($this->header('Accept'), 'xml');
        });
    }

    /**
     * Determine if the request body is xml.
     *
     * @return void
     */
    protected function registerIsXml()
    {
        Request::macro('isXml', function () {
            return Str::contains(strtolower($this->getContentType()), 'xml');
        });
    }

    /**
     * Convert xml in a request to an array.
     *
     * @return void
     */
    protected function registerXml()
    {
        Request::macro('xml', function () {
            if (!$this->isXml()) {
                return [];
            }
            $args = [];
            if (!empty(func_get_args())) {
                $args = func_get_arg(0);
            }
            try {
                return XmlToArray::convert($this->getContent(), false, $args) ?: [];
            } catch (Exception $exception) {
                throw CouldNotParseXml::payload($this->getContent());
            }
        });
    }
}
