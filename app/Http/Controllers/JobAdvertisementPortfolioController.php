<?php

namespace App\Http\Controllers;

use App\Models\JobAdvertisementPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class JobAdvertisementPortfolioController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $portfolios = $user->portfolios; 

        return response()->json($portfolios);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'example' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $uploadedFile = $request->file('example');
        $result = Cloudinary::uploadFile($uploadedFile->getRealPath(), [
            'folder' => 'user-portfolio/' . $user->id,
        ]);

        $portfolio = new JobAdvertisementPortfolio();
        $portfolio->user_id = $user->id;
        $portfolio->example_url = $result->getSecurePath();
        $portfolio->example_public_id = $result->getPublicId();
        $portfolio->save();

        return redirect()->back()->with('success', 'Portfolio item uploaded successfully.');
    }

    public function show(JobAdvertisementPortfolio $portfolio)
    {
        if (Auth::id() !== $portfolio->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json($portfolio);
    }

    public function destroy(JobAdvertisementPortfolio $portfolio)
    {
        if (Auth::id() !== $portfolio->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($portfolio->example_public_id) {
            try {
                Cloudinary::destroy($portfolio->example_public_id);
            } catch (\Exception $e) {
            }
        }

        $portfolio->delete();

        return redirect()->back()->with('success', 'Portfolio item deleted successfully.');
    }


    public function update(Request $request, JobAdvertisementPortfolio $portfolio)
    {
        if (Auth::id() !== $portfolio->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'example' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('example')) {
            if ($portfolio->example_public_id) {
                try {
                    Cloudinary::destroy($portfolio->example_public_id);
                } catch (\Exception $e) {
                }
            }

            $uploadedFile = $request->file('example');
            $result = Cloudinary::uploadFile($uploadedFile->getRealPath(), [
                'folder' => 'user-portfolio/' . Auth::id(),
            ]);

            $portfolio->example_url = $result->getSecurePath();
            $portfolio->example_public_id = $result->getPublicId();
        }

        $portfolio->save();

        return redirect()->back()->with('success', 'Portfolio item updated successfully.');
    }
}
