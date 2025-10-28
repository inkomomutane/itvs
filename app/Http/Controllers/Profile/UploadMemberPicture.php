<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use Illuminate\Http\Request;

class UploadMemberPicture
{
    public function __invoke(User $member, Request $request)
    {
        $validated = $request->validate([
            'logo' => 'array',
            'logo.*' => 'image'
        ]);

        foreach ($validated['logo'] as $image){
            $member->addMedia($image)->toMediaCollection('avatar');
        }

        return back();
    }
}
