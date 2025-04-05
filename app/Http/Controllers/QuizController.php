<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuizQuestion;
use App\Models\Product;

use App\Models\QuizChoices;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function quiz()
    {
        $quizquestions = QuizQuestion::all();
        $quizchoices = QuizChoices::all();
        $customer = session('customer');

        return view('quiz', compact('quizquestions', 'quizchoices', 'customer'));
    }

    public function addQuizResult(Request $request)
    {
        $customer = session('customer');
        info("heloketombe", $request->all());
        $product = Product::where('quizScore', '>', $request->input('totalScore')-50)->first();
            // ->where('quizScore', '<', 125)
            // ->first(); // Use 'first' to retrieve only one row

        info("hello4455");
        info($request->all());
        info($product->productID);
        // Log::info('Array from your controller:', $product);
        info($customer->all());

        $data['customerID'] = $customer->customerID;
        $data['productID'] = $product->productID;
        $data['submitTime'] = date('Y-m-d H:i:s');
        $data['totalScore'] = $request->input('totalScore');

        $quizresult = QuizResult::create($data);

        $scorepercentage = $request->input('totalScore') / 500 * 100;

        return view('quizresult', compact('product', 'scorepercentage'));
    }
}
