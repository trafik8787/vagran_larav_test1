<?php
namespace App\Http\Composers;
use App\Menu;
use Illuminate\Contracts\View\View;


class NavigateHeader
{
    /**
     * @param View $view
     */
    public function compose (View $view)
    {
        $view->with(['nav_category' => Menu::all()]);

    }
}