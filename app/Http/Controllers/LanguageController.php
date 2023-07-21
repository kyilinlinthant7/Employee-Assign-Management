<?php

namespace App\Http\Controllers;

/**
 * Class LanguageController
 * @author Kyi Lin Lin Thant
 * @create 05/07/2023
 * @return array
 */
class LanguageController extends Controller
{
    /**
     * Change languages to English and Myanmar
     *
     * @author Kyi Lin Lin Thant
     * @create 05/07/2023
     * @param  $locale
     * @return \Illuminate\Http\Response
     */
    public function changeLanguage($locale)
    {   
        if (in_array($locale, ['en', 'my'])) {
            session(['locale' => $locale]);
        }
        
        return redirect()->back();
    }
}
