<?php

namespace App\Http\Controllers;

use App\Models\Entities\UrlEntity;
use App\Models\Forms\UrlForm;
use App\Models\UrlModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Class UrlController
 * @package App\Http\Controllers
 */
class UrlController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('url.index', [
            'linksPagination' => UrlModel::getPaginationList(),
        ]);
    }

    /**
     * @return Factory|View
     */
    public function addUrl()
    {
        $urlEntity = new UrlEntity();
        return $this->formProcess($urlEntity);
    }

    /**
     * @param UrlEntity $urlEntity
     * @return Factory|View
     */
    public function editUrl(UrlEntity $urlEntity)
    {
        return $this->formProcess($urlEntity);
    }

    /**
     * @param UrlEntity $urlEntity
     * @return Factory|View
     */
    private function formProcess(UrlEntity $urlEntity)
    {
        $form = new UrlForm();
        if (request()->has('UrlForm')) {
            $urlData = request()->input('UrlForm');
            $form->setAttributes($urlData);
            $form->setAttributes(['code' => UrlModel::generateHash()]);
            if ($form->validate()) {
                $urlEntity = UrlModel::push($urlEntity, $form->getData());
                session()->flash('shortUrl', $urlEntity->shortUrl);
                return redirect()->route('url.index');
            }

            return redirect()->back()->withErrors($form->getErrors());
        }

        return view('url.index', [
            'form' => $form
        ]);
    }

    /**
     * @param UrlEntity $urlEntity
     * @return JsonResponse
     */
    public function remove(UrlEntity $urlEntity)
    {
        UrlModel::remove($urlEntity);
        return redirect()->route('url.index');
    }

    /**
     * @param $code
     * @return RedirectResponse
     */
    public function redirect($code)
    {
        $urlEntity = Cache::rememberForever("url.{$code}", function () use ($code) {
            return UrlModel::getByCode($code);
        });

        if ($urlEntity) {
            $urlEntity->increment('counter');
            return redirect()->away($urlEntity->url, 301);
        }

        abort(404);
    }
}