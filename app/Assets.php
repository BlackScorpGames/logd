<?php
namespace Logd\Core\App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class Assets
{

    const CSS = 'text/css';
    const PNG = 'image/png';
    const JPG = 'image/jpg';
    const JPEG = 'image/jpg';
    const JS = 'application/javascript';
    const JSON = 'application/json';

    private $contentTypes = array(
        'css' => self::CSS,
        'png' => self::PNG,
        'jpg' => self::JPG,
        'jpeg' => self::JPEG,
        'js' => self::JS,
        'json' => self::JSON
    );
    private $path = '';

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param Request $request
     * @param $type
     * @param $file
     * @return BinaryFileResponse
     */
    public function load(Request $request)
    {


        $file = realpath(sprintf("%s%s", $this->path, $request->getPathInfo()));
        if(!$file){
            $response = new Response();
            $response->setStatusCode(404);
            return $response;
        }
        $expireDate = new DateTime();
        $expireDate->modify("+1 month");

        $response = new BinaryFileResponse($file);
        $response->setAutoEtag();
        $response->setAutoLastModified();
        $response->setPublic();
        $response->setExpires($expireDate);
        $response->isNotModified($request);
        $response->headers->set('Content-Type', $this->getContentTypByExtension($response->getFile()->getExtension()));
        $response->prepare($request);
        return $response;
    }

    /**
     * @param string $extension
     */
    private function getContentTypByExtension($extension)
    {
        return isset($this->contentTypes[$extension]) ? $this->contentTypes[$extension] : '';
    }

}