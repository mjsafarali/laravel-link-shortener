<?php

namespace App\Http\Controllers;

use App\Utility\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinkController extends Controller
{
    const MAX_SHORT_STR_RETRY = 5;

    public function generate(Request $request): ApiResponse
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $shortStr = Str::random(7);
        $retryCount = 0;

        try {
            self::storeShortUrl($request->input('url'), $shortStr, $retryCount);
        } catch (\Exception $e) {
            Log::error('ShortUrlController, generator error: ' . $e->getMessage());
            return new ApiResponse(['message' => 'internal server error'], 500, 500);
        }

        $shortUrl = asset('/s/' . $shortStr);
        return new ApiResponse(['url' => $shortUrl]);
    }

    /**
     * @throws InternalErrorException
     */
    private static function storeShortUrl(string $original_url, string $shortStr, int $retryCount): void
    {
        if ($retryCount > self::MAX_SHORT_STR_RETRY) {
            throw new InternalErrorException("Url shortening limit reached");
        }

        try {
            $link = new Link();
            $link->original_url = $original_url;
            $link->short_url = $shortStr;
            $link->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $retryCount++;
                self::storeShortUrl($original_url, $shortStr, $retryCount);
            }
        }
    }

    /**
     * @throws \Throwable
     */
    public function get(Request $request): ApiResponse
    {
        $shortUrl = $request->get('url');
        $shortStr = str_replace(asset('/s') . '/', '', $shortUrl);

        $link = Link::query()
            ->select('id', 'original_url', 'visit_count')
            ->where('short_url', '=', $shortStr)
            ->first();

        if (is_null($link)) {
            throw new NotFoundHttpException();
        }

        return new ApiResponse(['url' => $link->original_url]);
    }

    public function redirect($short_str): \Illuminate\Http\RedirectResponse
    {
        $link = Link::query()
            ->select('id', 'original_url', 'visit_count')
            ->where('short_url', '=', $short_str)
            ->first();

        if (is_null($link)) {
            throw new NotFoundHttpException();
        }

        return Redirect::to($link->original_url);
    }
}
